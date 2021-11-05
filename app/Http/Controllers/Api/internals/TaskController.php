<?php

namespace App\Http\Controllers\Api\internals;

use App\Http\Controllers\BaseApiController;
use App\Models\Comment;
use App\Models\Tarea;
use App\Models\TareaFotos;
use App\Models\ZonasBuses;
use App\Traits\EmailNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends BaseApiController
{
    use EmailNotification;

    public function getTaskDetails(Request $request)
    {
        $task = Tarea::find($request->id);
        $coordination = Carbon::parse($task->created_at);

        $preview = array();

        foreach ($task->tareaFotos as $detail) {
            $preview[] = \Config::get('filesystems.disks.s3.url') . '/' . $detail->image;
        }

        foreach (json_decode($task->zonapublicitaria_id) as $zona) {
            $zonas[] = $zona->name;
        }

        $comments = $task->comment()->get();
        $comments_data = [];
        foreach ($comments as $comment) {
            $coordination = Carbon::parse($comment->created_at);
            $user = User::find($comment->user_id);

            $comments_data[] = [
                'id' => $comment->id,
                'body' => $comment->body,
                'parent_id' => $comment->parent_id,
                'user' => $user->name,
                'created_at' => $coordination->isoFormat('x'),
            ];
        }

        $user_task = User::where('id', '=', $task->installer->user_id)->first();

        $data = [
            'campaing_name' => $task->campaing->name,
            'campaing_notes' => $task->campaing->notes,
            'installer' => $task->installer->first_name . ' ' . $task->installer->last_name,
            'company_name' => $task->campaing->cliente->company_name,
            'bus_plate' => $task->bus->fullPlate,
            'brand' => $task->bus->carroceria->name,
            'start_date' => $task->campaing->start_date,
            'end_date' => $task->campaing->end_date,
            'notes' => $task->notes,
            'coordination_date' => $coordination->isoFormat('x'),
            'status' => $this->taskStatus($task->status),
            'images' => $preview,
            'zonas_instalacion' => $zonas,
            'location' => 'San Jose',
            'comments' => $comments_data,
            'image_profile_installer' => ($user_task->image_profile) ? \Config::get('filesystems.disks.s3.url') . '/' . $user_task->image_profile : asset('images/default_image.png'),
            'sections' => $this->getTaskSections($task, 'sections'),
            'approved' => $task->approved,
            'is_picked_up' => $task->is_picked_up
        ];

        return $this->successResponse(['data' => $data], 200);

    }

    private function getTaskSections($task, $tag = null)
    {
        $sections = array();

        foreach (json_decode($task->zonapublicitaria_id) as $installations) {

            $installation_zone_active[] = $installations->name;
            $hola = \DB::table('installations_bookings_log')->select('*')
                ->where('bus_id', '=', $task->bus->id)
                ->where('zona_bus_id', '=', $installations->zonasbuses_id)
                ->where('status', '=', 'Instalado')->first();


            $zonas = ZonasBuses::where('id', '=', $installations->zonasbuses_id)->first();

            if ($tag == 'sections') {

                $images = TareaFotos::where('tarea_id', '=', $task->id)->where('section_id', '=', $zonas->id)->get();
                $task_images = array();

                $task_images[] = [
                    'image' => null,
                    'tag' => 'receiving',
                ];
                $task_images[] = [
                    'image' => null,
                    'tag' => 'installation',
                ];


                if ($hola == null) {
                    foreach ($images as $image) {
                        if ($image->tag == 'receiving') {
                            $task_images[0] = [
                                'image' => \Config::get('filesystems.disks.s3.url') . '/' . $image->image,
                                'tag' => 'receiving',
                            ];
                        }

                        if ($image->tag == 'installation') {
                            $task_images[1] = [
                                'image' => \Config::get('filesystems.disks.s3.url') . '/' . $image->image,
                                'tag' => 'installation',
                            ];
                        }
                    }

                    $sections[] = [
                        'section_id' => $zonas->id,
                        'name' => $zonas->slug,
                        'images' => $task_images
                    ];
                } else {

                    $task_images[] = [
                        'image' => null,
                        'tag' => 'desinstallation',
                    ];


                    foreach ($images as $image) {
                        if ($image->tag == 'receiving') {
                            $task_images[0] = [
                                'image' => \Config::get('filesystems.disks.s3.url') . '/' . $image->image,
                                'tag' => 'receiving',
                            ];
                        }

                        if ($image->tag == 'installation') {
                            $task_images[1] = [
                                'image' => \Config::get('filesystems.disks.s3.url') . '/' . $image->image,
                                'tag' => 'installation',
                            ];
                        }

                        if ($image->tag == 'desinstallation') {
                            $task_images[2] = [
                                'image' => \Config::get('filesystems.disks.s3.url') . '/' . $image->image,
                                'tag' => 'desinstallation',
                            ];
                        }
                    }
                    $sections[] = [
                        'section_id' => $zonas->id,
                        'name' => $zonas->slug,
                        'images' => $task_images
                    ];
                }
            } else {
                $sections[] = $installations->name;
            }
        }

        return $sections;

    }

    private function taskStatus($status)
    {
        $data = '';
        switch ($status) {
            case 1:
                $data = 'Creada';
                break;
            case 2:
                $data = 'Asignada';
                break;
            case 3:
                $data = 'Aceptada';
                break;
            case 4:
                $data = 'Iniciada';
                break;
            case 5:
                $data = 'Finalizada';
                break;
            case 6:
                $data = 'Cancelada';
                break;
            case 7:
                $data = 'Cambio de bus';
                break;
        }

        return $data;
    }

    public function declineOrAcceptTask(Request $request)
    {
        $task = Tarea::find($request->task_id);

        if ($request->task_status == 'decline') {

            $request->validate([
                'body' => 'required',
                'task_id' => 'required',
            ]);

            $data = [
                'user_id' => backpack_auth()->user()->id,
                'task_id' => $request->task_id,
                'body' => $request->body
            ];

            Comment::create($data);
            $task->approved = 0;
        } else {
            $task->approved = 1;
        }
        $task->save();

        return $this->successResponse(['data' => 'success'], 200);
    }

    public function approvedPhotoToReports(Request $request)
    {
        $task = Tarea::find($request->task_id);

        $task->is_picked_up = $request->photos_approved;
        $task->save();

        return $this->successResponse(['data' => 'success'], 200);
    }

}
