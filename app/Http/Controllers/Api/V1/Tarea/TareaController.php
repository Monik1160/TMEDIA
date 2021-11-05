<?php

namespace App\Http\Controllers\Api\V1\Tarea;

use App\Http\Controllers\BaseApiController;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Tarea;
use App\Models\TareaFotos;
use App\Models\ZonasBuses;
use App\Traits\EmailNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TareaController extends BaseApiController
{
    use EmailNotification;

    /**
     * The logged in user.
     *
     * @var \App\Models\User
     */
    protected $currentUser;
    protected $task;
    protected $has_active_task;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(Tarea $tarea)
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = auth('api')->user();
            return $next($request);
        });
        $this->task = $tarea;
    }


    public function tasksAssignedByInstaller()
    {
        $contact = Contact::where('user_id', '=', $this->currentUser->id)->first();
        $tasks = $this->task->getTareasInstaller($contact->id);
        $active_task = $this->task->where('installer_id', '=', $contact->id)->where('status', '=', 4)->first();
        $active = [];

        if (!$active_task == '') {
            if (!$active_task->is_picked_up_date == null) {
                $is_picked_up_date = Carbon::parse();
                $is_picked_up_date_format = $is_picked_up_date->isoFormat('x');
            } else {
                $is_picked_up_date_format = null;
            }
            $details_images_active = json_decode($active_task->montaje);


            foreach ($details_images_active as $detail) {
                $preview_active[] = \Config::get('filesystems.disks.s3.url') . '/' . $detail->image;
            }

            $coordination_active = Carbon::parse($active_task->created_at);

            $active = [
                'task_id' => $active_task->id,
                'campaing_name' => $active_task->campaing->name,
                'company_name' => $active_task->campaing->cliente->company_name,
                'bus' => $active_task->bus->fullPlate,
                'brand' => $active_task->bus->carroceria->name,
                'start_date' => $active_task->campaing->start_date,
                'end_date' => $active_task->campaing->end_date,
                'preview' => $preview_active,
                'status' => $active_task->status,
                'is_picked_up_date' => $is_picked_up_date_format,
                'coordination_date' => $coordination_active->isoFormat('x'),
                'location' => $active_task->ruta->name,
                'comments' => count($active_task->comment),
                'notes' => $active_task->notes,
                'installation_zones' => $this->getTaskSections($active_task),
                'sections' => $this->getTaskSections($active_task, 'sections'),
                'task_type' => 'Installation'
            ];
        }
        foreach ($tasks as $task) {
            $coordination = Carbon::parse($task->created_at);

            $details_images = json_decode($task->montaje);

            $installation_zone = array();
            $preview = array();

            foreach ($details_images as $detail) {
                $preview[] = \Config::get('filesystems.disks.s3.url') . '/' . $detail->image;
            }

            foreach (json_decode($task->zonapublicitaria_id) as $installations) {
                $installation_zone[] = $installations->name;
            }


            $datos[] = [
                'task_id' => $task->id,
                'campaing_name' => $task->campaing->name,
                'company_name' => $task->campaing->cliente->company_name,
                'bus' => $task->bus->fullPlate,
                'brand' => $task->bus->carroceria->name,
                'start_date' => $task->campaing->start_date,
                'end_date' => $task->campaing->end_date,
                'preview' => $preview,
                'status' => $task->status,
                'coordination_date' => $coordination->isoFormat('x'),
                'location' => $task->ruta->name,
                'notes' => $task->notes,
                'installation_zones' => $this->getTaskSections($task),
                'sections' => $this->getTaskSections($task, 'sections'),
                'approved' => ($task->approved === 0) ? false : true,
                'task_type' => $task->tareatype->name
            ];
        }

        if (empty($datos)) {
            $datos = [];
        }
        $data = [
            'active_task' => (count($active) == 0) ? null : $active,
            'tasks' => (count($datos) == 0) ? [] : $datos,
        ];

        if (count($tasks) >= 1 || $active_task) {
            return $this->successResponse(['data' => $data], 200);
        }
        return $this->errorResponse('There are not Tasks for you', 404);

    }

    public function isPickedUpTaskByInstaller(Request $request)
    {
        if (!$this->currentUser == null) {
            $installer = $this->task->where('installer_id', $this->currentUser->userable_id)->get();

            if ($installer->isEmpty()) {
                return $this->errorResponse('Not have tasks assigned to you', 404);
            }
            $tasks_arr = json_decode($request->task_id);

            for ($i = 0; $i < count($tasks_arr); $i++) {
                $tasks = $this->task->find($tasks_arr[$i]);

                if (empty($tasks)) {
                    return $this->errorResponse('No Tasks', 409);
                }

                if ($tasks->status == 3) {
                    return $this->errorResponse('This task was already picked up', 409);
                } else {
                    $tasks->is_picked_up_date = Carbon::now();
                    $tasks->status = 3;
                    $tasks->save();
                }
            }
        }

        return $this->successResponse(['data' => 'Task is picked up'], 200);
    }

    public function declineTaskByInstaller(Request $request)
    {
        if (!$this->currentUser == null) {
            $this->validate($request, [
                'task_id' => 'required',
                'message' => 'required'
            ]);

            $tasks = $this->task->find($request->task_id);

            if ($tasks->status == 3 || $tasks->status == 4 || $tasks->status == 5 || $tasks->status == 6) {
                return $this->errorResponse('You can only decline the task if it has not been started ', 409);
            }

            if (!$tasks->installer_id == $this->currentUser->userable_id) {
                return $this->errorResponse('Now you decline this task', 404);
            } else {
                $tasks->installer_id = null;
                $tasks->status = 1;
                $tasks->decline_message = $request->message;
                $tasks->save();

                //$this->sendNotification($tasks, 'logistica', 'Tarea Declinada', $request->message, 'Task');

                return $this->successResponse(['data' => 'success'], 200);
            }
            //enviar correo al encargado de asignar tareas
        }
        return $this->errorResponse('You must be login first', 500);
    }

    public function startTaskByInstaller(Request $request)
    {
        if (!$this->currentUser == null) {
            $tasks = $this->task->find($request->id);
            $active_task = $this->task->where('status', '=', 4)->first();

            if (!$active_task == null) {
                return $this->errorResponse('You already have an active task, you cannot start another', 409);
            }

            if ($tasks->status == 1 || $tasks->status == 5 || $tasks->status == 6) {
                return $this->errorResponse('error', 409);
            }

            if ($tasks->status == 2) {
                return $this->errorResponse('First, you need to accept the task to start', 409);
            }

            if ($tasks->status == 4) {
                return $this->errorResponse('Tarea ya fue iniciada', 409);
            }

            $section = $this->getTaskSections($tasks, 'sections');

            if (count($section) == 1) {
                $validador = Validator::make($request->all(), [
                    'image' => 'required|image|mimes:jpeg,png,jpg',
                ]);

                if ($validador->passes()) {
                    $file = $request->file('image');
                    $name = time() . $file->getClientOriginalName();
                    $filePath = 'tareas/tarea_' . $request->id . '/' . $name;
                    Storage::disk('s3')->put($filePath, file_get_contents($file));

                    //Format Data
                    $task_data = [
                        'image' => 'tareas/tarea_' . $request->id . '/' . $name,
                        'tarea_id' => $request->id,
                        'section_id' => $section[0]['section_id'],
                        'tag' => 'receiving'
                    ];

                    TareaFotos::create($task_data);
                } else {
                    return $this->errorResponse($validador->errors()->all(), 404);
                }
            }

            $this->sendNotification($tasks, 'logistica', 'Tarea Iniciada', 'Tarea Iniciada', 'Task');
            $tasks->status = 4;
            $tasks->save();


            return $this->successResponse(['data' => 'success'], 200);
        }
        return $this->errorResponse('You must be login first', 500);
    }

    public function cancelTaskWithReason(Request $request)
    {
        if (!$this->currentUser == null) {
            $this->validate($request, [
                'message' => 'required'
            ]);

            $tasks = $this->task->find($request->id);

            if ($tasks->status == 6) {
                return $this->errorResponse('Task has already been canceled', 409);
            }
            $tasks->installer_id = null;
            $tasks->status = 6;
            $tasks->cancel_message = $request->message;
            $tasks->save();
            //$this->sendNotification($tasks, 'logistica', 'Tarea Cancelada', $request->message, 'Task');

            return $this->successResponse(['data' => 'success'], 200);
        }
        return $this->errorResponse('You must be login first', 500);
    }

    public function addImagesToTask(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'section_id' => 'required',
            'task_id' => 'required',
            'tag' => 'required'
        ]);

        $active_task = $this->task->where('id', '=', $request->task_id)->first();

        if (!$this->currentUser == null) {

            $file = $request->file('image');
            $name = time() . $file->getClientOriginalName();
            $filePath = 'tareas/tarea_' . $request->task_id . '/' . $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            //Format Data
            $task_data = [
                'image' => $filePath,
                'tarea_id' => $request->task_id,
                'section_id' => $request->section_id,
                'tag' => $request->tag
            ];
            //Save image
            TareaFotos::create($task_data);

            $task_data['image'] = \Config::get('filesystems.disks.s3.url') . '/' . $filePath;

            return $this->successResponse($task_data, 200);

        }
        return $this->errorResponse('You must be login first', 500);
    }

    public function deleteImagesToTask(Request $request)
    {
        $request->validate([
            'section_id' => 'required',
            'tag' => 'required',
            'task_id' => 'required',
        ]);

        if (!$this->currentUser == null) {
            //Save image
            $imagen = TareaFotos::where('section_id', '=', $request->section_id)->where('tag', '=', $request->tag)->where('tarea_id', '=', $request->task_id)->first();

            if (empty($imagen)) {
                return $this->errorResponse('We cannot find that image', 400);
            }
            $filePathtoDisk = 'tareas/tarea_' . $imagen->tarea_id . '/' . $imagen->image;

            Storage::disk('s3')->delete($filePathtoDisk);
            $imagen->delete();

            return $this->successResponse(['data' => 'Image deleted successfully'], 200);

        }
        return $this->errorResponse('You must be login first', 500);
    }

    public function getDetailTask(Request $request)
    {
        if (!$this->currentUser == null) {
            $task = $this->task->find($request->id);
            $coordination = Carbon::parse($task->created_at);

            if (!$task->is_picked_up_date == null) {
                $is_picked_up_date = Carbon::parse();
                $is_picked_up_date_format = $is_picked_up_date->isoFormat('x');
            } else {
                $is_picked_up_date_format = null;
            }

            $details_images = json_decode($task->montaje);

            foreach ($details_images as $detail) {
                $preview[] = \Config::get('filesystems.disks.s3.url') . '/' . $detail->image;
            }

            $data = [
                'campaing_name' => $task->campaing->name,
                'company_name' => $task->campaing->cliente->name,
                'bus_plate' => $task->bus->fullPlate,
                'brand' => $task->bus->carroceria->name,
                'start_date' => $task->campaing->start_date,
                'end_date' => $task->campaing->end_date,
                'notes' => $task->notes,
                'is_picked_up' => $task->is_picked_up,
                'is_picked_up_date' => $is_picked_up_date_format,
                'coordination_date' => $coordination->isoFormat('x'),
                'status' => $task->status,
                'preview_image' => $preview,
                'location' => $task->ruta->name,
                'installation_zones' => $this->getTaskSections($task),
                'section' => $this->getTaskSections($task, 'sections'),
                'approved' => ($task->approved === 0) ? false : true,
                'comments_count' => count($task->comment),
                'task_type' => 'Installation'
            ];

            return $this->successResponse(['data' => $data], 200);
        }
        return $this->errorResponse('You must be login first', 500);
    }

    public function getCommentsTask(Request $request)
    {
        $request->validate([
            'task_id' => 'required'
        ]);

        if (!$this->currentUser == null) {
            $task = $this->task->find($request->task_id);
            $comments = $task->comment()->get();
            $data = [];

            if ($task->status == 4 || $task->approved === 0) {

                foreach ($comments as $comment) {

                    $coordination = Carbon::parse($comment->created_at);
                    $user = User::find($comment->user_id);

                    $data[] = [
                        'id' => $comment->id,
                        'body' => $comment->body,
                        'parent_id' => $comment->parent_id,
                        'user' => $user->name,
                        'created_at' => $coordination->isoFormat('x'),
                    ];
                }

                return $this->successResponse(['data' => $data], 200);
            } else {
                return $this->errorResponse('This task is not active', 409);
            }

        }
        return $this->errorResponse('You must be login first', 500);
    }

    public function addCommentsToTaskByInstaller(Request $request)
    {
        if (!$this->currentUser == null) {
            $task = $this->task->find($request->task_id);

            $request->validate([
                'body' => 'required',
                'task_id' => 'required',
            ]);

            $data = [
                'user_id' => $this->currentUser->id,
                'task_id' => $request->task_id,
                'parent_id' => $request->parent_id,
                'body' => $request->body
            ];

            $this->sendNotification($task, 'logistica', 'Comentario nuevo del Instalador', $request->body, 'Task');

            Comment::create($data);

            return $this->successResponse(['data' => 'success'], 200);
        }
        return $this->errorResponse('You must be login first', 500);
    }

    public function endTaskByInstaller(Request $request)
    {
        if (!$this->currentUser == null) {
            $task = $this->task->find($request->id);
            if ($task->status == 4 || $task->approved === 0) {

                foreach (json_decode($task->zonapublicitaria_id) as $zona) {
                    DB::table('installations_bookings_log')
                        ->where('zona_bus_id', '=', $zona->zonasbuses_id)
                        ->where('bus_id', '=', $task->bus_id)
                        ->where('campaaign_id', '=', $task->campaing->id)
                        ->update([
                            'status' => 'Instalado',
                        ]);
                }
                $task->status = 5;
                $task->approved = null;
                $task->save();

                return $this->successResponse(['data' => 'success'], 200);
            }
            return $this->errorResponse('This task can not ended because is not active', 409);
        }
        return $this->errorResponse('You must be login first', 500);
    }

    public function getHistoryTaskByUser(Request $request)
    {
        $tasks = Tarea::where('installer_id', '=', $this->currentUser->userable_id)->get();
        $datos = [];

        if ($request->frecuency == '') {
            return $this->errorResponse('frecuency is missing', 400);
        }


        foreach ($tasks as $task) {
            $coordination = Carbon::parse($task->created_at);

            $details_images = json_decode($task->montaje);


            foreach ($details_images as $detail) {
                $preview[] = $detail->image;
            }

            $date = Carbon::parse($task->created_at);
            $now = Carbon::now();
            $diff = $date->diffInDays($now);

            if ($request->frecuency == 'all') {
                $datos[] = [
                    'task_id' => $task->id,
                    'campaing_name' => $task->campaing->name,
                    'company_name' => $task->campaing->cliente->name,
                    'bus' => $task->bus->fullPlate,
                    'brand' => $task->bus->carroceria->name,
                    'start_date' => $task->campaing->start_date,
                    'end_date' => $task->campaing->end_date,
                    'preview' => $preview,
                    'status' => $task->status,
                    'coordination_date' => $coordination->isoFormat('x'),
                    'location' => 'San Jose',
                    'notes' => $task->notes,
                ];
            } elseif ($request->frecuency == 'weekly') {
                if ($diff <= 7) {
                    $datos[] = [
                        'task_id' => $task->id,
                        'campaing_name' => $task->campaing->name,
                        'company_name' => $task->campaing->cliente->name,
                        'bus' => $task->bus->fullPlate,
                        'brand' => $task->bus->carroceria->name,
                        'start_date' => $task->campaing->start_date,
                        'end_date' => $task->campaing->end_date,
                        'preview' => $preview,
                        'status' => $task->status,
                        'coordination_date' => $coordination->isoFormat('x'),
                        'location' => 'San Jose',
                        'notes' => $task->notes,
                    ];
                }
            } elseif ($request->frecuency == 'monthly') {
                if ($diff <= 30) {
                    $datos[] = [
                        'task_id' => $task->id,
                        'campaing_name' => $task->campaing->name,
                        'company_name' => $task->campaing->cliente->name,
                        'bus' => $task->bus->fullPlate,
                        'brand' => $task->bus->carroceria->name,
                        'start_date' => $task->campaing->start_date,
                        'end_date' => $task->campaing->end_date,
                        'preview' => $preview,
                        'status' => $task->status,
                        'coordination_date' => $coordination->isoFormat('x'),
                        'location' => 'San Jose',
                        'notes' => $task->notes,
                    ];
                }
            }

        }
        return $this->successResponse(['data' => $datos], 200);
    }

    public function changeBus(Request $request)
    {
        $this->validate($request, [
            'body' => 'required',
            'task_id' => 'required',
        ]);
        $tasks = $this->task->find($request->task_id);

        if ($this->currentUser == null) {
            return $this->errorResponse('You must be login first', 500);
        }

        $tasks = $this->task->find($request->task_id);
        if ($tasks->status == 7) {
            return $this->errorResponse('Bus change request has already been sent ', 402);
        } else {
            $this->sendNotification($tasks, 'logistica', 'Solicitud de Cambio de Bus', $request->body, 'Task');
            $tasks->status_changed = $tasks->status;
            $tasks->status = 7;
            $tasks->save();
        }

        return $this->successResponse(['data' => 'Bus change request sent'], 200);
    }

    public function privacyPolicy()
    {
        return view('admin.privacy-policy.privacy');
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


                if ($task->tareatype->id == 1 ) {
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
                        'name' => $zonas->name,
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

    public function sendContactForm(Request $request)
    {
        if (!$this->currentUser == null) {

            $request->validate([
                'feedback' => 'required',
            ]);

            $user = $this->currentUser;

            Mail::raw("{$request->feedback}", function ($message) use ($user) {
                $message
                    ->to('admin@publimediacr.com')
                    ->subject("Feedback del Instalador {$user->first_name} {$user->last_name}");
            });

            return $this->successResponse(['data' => 'Feedback send'], 200);
        }
        return $this->errorResponse('You must be login first', 500);
    }

}
