<?php

namespace App\Http\Controllers\Admin\Campaign;

use App\Http\Requests\CampaingRequest;
use App\Mail\CampaignEmailNotification;
use App\Models\Arte;
use App\Models\CampaignDetails;
use App\Models\Campaing;
use App\Models\Client;
use App\Models\Ejecutivo;
use App\Models\FinanceCampaign;
use App\Traits\ApiResponder;
use App\Traits\EmailNotification;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function GuzzleHttp\Promise\all;
use PDF;
use App\Models\Tarea;
use App\Models\TareaFotos;

/**
 * Class CampaingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CampaingCrudController extends CrudController
{
    use ApiResponder;
    use EmailNotification;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Campaing');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/campañas');
        $this->crud->setEntityNameStrings('campaña', 'campañas');
        $this->crud->addButtonFromView('line', 'request_campaign', 'Campaign/request_campaign', 'top');
        $this->crud->addButtonFromView('line', 'aprove_campaign', 'Campaign/aprove_campaign', 'top');
        $this->crud->addButtonFromView('line', 'add_buses', 'Campaign/add_buses', 'top');
        $this->crud->addButtonFromView('line', 'design', 'Campaign/design', 'top');
        $this->crud->addButtonFromView('line', 'add_task', 'Campaign/add_task', 'top');
        $this->crud->addButtonFromView('line', 'create_report', 'Campaign/create_report', 'top');

        //PASARLO A UN TRAIT
        $this->crud->addButtonFromView('line', 'logistica', 'Campaign/Actions/logistica', 'top');
        $this->crud->addButtonFromView('line', 'diseno', 'Campaign/Actions/diseno', 'top');
        $this->crud->addButtonFromView('line', 'ejecutivo', 'Campaign/Actions/ejecutivo', 'top');
        $this->crud->addButtonFromView('line', 'finanzas', 'Campaign/Actions/finanzas', 'top');

        $this->crud->setCreateView('admin.campaign.index');
        $this->crud->setUpdateView('admin.campaign.edit');

    }

    protected function setupListOperation()
    {
        $this->crud->removeButton('show');
        $this->crud->removeButton('revisions');
        $this->crud->removeButton('delete');
        $this->crud->removeButton('update');


        $this->crud->addColumn([
            'label' => "Nombre de la campaña:",
            'name' => 'name',
            'type' => 'text',
            'attributes' => [
                'autocomplete' => 'off',
            ],
        ]);

        $this->crud->addColumn([
            'name' => 'start_date',
            'label' => "Fecha de Inicio",
            'type' => 'date',
        ]);
        $this->crud->addColumn([
            'name' => 'end_date',
            'label' => "Fecha de Final",
            'type' => 'date',
        ]);

        $this->crud->addColumn([
            'name' => 'status',
            'label' => "Estado",
            'type' => 'select_from_array',
            'options' => [1 => 'Borrador', 2 => 'Finanzas', 3 => 'Buses', 4 => 'Diseño', 5 => 'Tareas', 6 => 'Instalación'],
        ]);

        $this->crud->addField([
            'type' => 'relationship',
            'name' => 'task', // the relationship name in your Model
            'entity' => 'task', // the relationship name in your Model
            'attribute' => 'name', // attribute on Article that is shown to admin
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);
    }

    public function store(Request $request)
    {
        $data = json_decode($request->data);

        $data_campaign = [
            'name' => $data->name,
            'client_id' => $data->client,
            'notes' => $data->notes,
            'requerie_desinstallion' => $data->requerie_desinstallion,
            'possible_renovation' => $data->posible_renovation,
            'start_date' => Carbon::parse($data->date[0]),
            'end_date' => Carbon::parse($data->date[1]),
            'status' => 1,
            'monto' => $data->monto,
            'comision' => $data->comision,
        ];

        $campaign = Campaing::create($data_campaign);

        //Artes
        $artes_images = $request->files;
        $artes_data = $data->artes;

        $files = $this->saveImageArts($artes_images, $campaign->id);

        $this->artes($campaign, $artes_data, $files);

        //Details
        $details = $data->details;
        $this->details_campaign($campaign, $details);


        return $this->successResponse('success', 200);
    }

    //Revisar
    public function update(Request $request)
    {
        $data_request = json_decode($request->data);

        $campaigns = Campaing::find($data_request->campaign_id);

        $data = [
            'name' => $data_request->name,
            'client_id' => $data_request->client,
            'notes' => $data_request->notes,
            'requerie_desinstallion' => $data_request->requerie_desinstallion,
            'possible_renovation' => $data_request->posible_renovation,
            'start_date' => Carbon::parse($data_request->date[0])->toDateString(),
            'end_date' => Carbon::parse($data_request->date[1])->toDateString(),
            'comision' => $data_request->comision ? $data_request->comision : $campaigns->comision,
            'monto' => $data_request->monto ? $data_request->monto : $campaigns->monto,
        ];

        //Finances
        $finance = isset($data_request->finances);

        if (!$finance == null) {
            $finance = $data_request->finances;
            $finances_flag = $this->addFinanceToCampaign($campaigns, $finance);

            if ($finances_flag != false){
                return $finances_flag;
            }
        }
        //Details Campaign
        $details = $data_request->details;
        $this->details_campaign($campaigns, $details);

        //Arts
        $artes_images = $request->files;
        $artes_data = $data_request->artes;

        $files = $this->saveImageArts($artes_images, $campaigns->id);
        $this->artes($campaigns, $artes_data, $files);

        $campaigns->update($data);

        return $this->successResponse('success', 200);
    }

    
    /**
     * generateCampaignReport
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function generateCampaignReport(Request $request , $id){

        
        $campaign = Campaing::with("cliente")->find($id);

        if($campaign->status < 6){
            return $this->errorResponse('La campaña no cuenta con el estatus para poder generar el reporte', 403);
        }

        $campaign_details = CampaignDetails::where("campaign_id", $id)->with("ruta" , "bus")->first();
        $bus = $campaign_details->bus;
        $route = $campaign_details->ruta;

        $tasks = $campaign->task()->where( "status" , 5)->with("bus" , "tareaFotos" , "ruta")->get();

        if(count($tasks) == 0){
            return $this->errorResponse('La campaña no cuenta con tareas finalizadas para poder generar el reporte', 403);
        }

        $installion_tasks = $this->getInstallionTasks( $tasks );
        
        $desinstallion_tasks = $this->getDesinstallionTasks($installion_tasks);

        $data = [
            'title' => $campaign->name,
            'client_name' => $campaign->cliente->clientName,
            'installation_tasks_count' => count($installion_tasks),
            'installation_tasks' => $installion_tasks,
            'desinstallation_tasks_count' => count( $desinstallion_tasks ),
            'desinstallation_tasks' => $desinstallion_tasks ,
            'image_s3_path' =>  \Config::get('filesystems.disks.s3.url')
        ];
        
        $pdf = PDF::setOptions([
             'dpi' => 96 ,
             'defaultFont' => "sans-serif" ,
             'isHtml5ParserEnabled' => true,
             'isRemoteEnabled' => true,
             'isPhpEnabled' => true
             ])
        ->loadView('reports.campaign_report', $data)
        ->setPaper('a4', 'landscape');
        
        // return $pdf->download();
        return $pdf->stream();
    }

    private function saveImageArts($files, $campaign_id)
    {
        $output = [];

        foreach ($files as $key => $file) {
            foreach ($file as $index => $image) {
                if (is_numeric($index)) {
                    $name = time() . $image->getClientOriginalName();
                    $filePath = 'campañas/campaña_' . $campaign_id . '/' . $name;
                    Storage::disk('s3')->put($filePath, file_get_contents($image));
                    $output[$index] = $filePath;
                }
            }
        }

        return $output;
    }

    private function artes($campaign, $artes, $files)
    {
        foreach ($artes as $arte) {
            if (isset($arte->on_camping_id)) {
                $data = [
                    'name' => $arte->name,
                    'on_camping_id' => $arte->on_camping_id,
                    'image' => $files[$arte->on_camping_id],
                ];
                $campaign->arte()->create($data);
            }
        }
    }

    private function details_campaign($campaign, $details)
    {
        $details_campaign[] = [];

        foreach ($details as $detail) {
            if (isset($detail->arte->on_camping_id)) {
                $on_campaign_id = $detail->arte->on_camping_id;
            } else {
                $on_campaign_id = null;
            }
            $arte_id_ecist = $detail->arte;

            if ($on_campaign_id == null) {
                $arte_id = Arte::find($arte_id_ecist->id);
            } else {
                $arte_id = Arte::where('on_camping_id', '=', $on_campaign_id)->first();
            }

            $detail_id = isset($detail->id);

            $details_campaign = [
                'id' => $detail_id ? $detail_id : null,
                'ruta_id' => $detail->ruta->id,
                'zona_publicitaria' => json_encode($detail->zona_publicitaria),
                'notes' => $detail->notes,
                'arte_id' => $arte_id->id,
            ];

            if ($detail_id != null) {
                CampaignDetails::find($detail->id)->update($details_campaign);
            } else {
                if ($campaign->status >= 3){
                    $this->sendNotification($campaign,'logistica','Nueva Ruta Añadida','Agregar Bus a las nuevas rutas','Campaign');
                }
                $campaign->campaign_details()->create($details_campaign);
            }
        }

        //Mejorar
        $artes = Arte::all();
        foreach ($artes as $arte) {
            $arte->on_camping_id = null;
            $arte->save();
        }
    }

    private function addFinanceToCampaign($campaign, $finance)
    {
        foreach ($finance as $finances) {
            $finances_array = [
                'id' => $finances->id ? $finances->id : null,
                'monto' => $finances->monto,
                'odc' => $finances->odc ? $finances->odc : null,
                'odf' => $finances->odf,
                'start_date' => Carbon::parse($finances->start_date)->toDateString(),
                'end_date' => Carbon::parse($finances->end_date)->toDateString(),
                'notes' => $finances->notes ? $finances->notes : null,
            ];


            if (!$finances->id == null) {
                FinanceCampaign::find($finances->id)->update($finances_array);
            } else {
                $odf_exist = FinanceCampaign::where('odf', '=', $finances->odf)->first();

                if (!empty($odf_exist)) {
                    return $this->errorResponse("Existe una solicitud de factura con el # {$odf_exist->odf}",422);
                }

                $campaign->finance_campaign()->create($finances_array);

                return false;
            }
        }
    }

    public function status($id, $status)
    {
        $campaign = Campaing::find($id);

        $view = '';

        switch ($status) {
            case 'logistica':
                $view = 'admin.campaign.Actions.logistica';
                break;
            case 'diseno':
                $view = 'admin.campaign.Actions.diseno';
                break;
            case 'ejecutivo':
                $view = 'admin.campaign.Actions.ejecutivo';
                break;
            case 'finanzas':
                $view = 'admin.campaign.Actions.finanzas';
                break;
        }

        return view($view, compact('campaign'));
    }
    
    /**
     * Getting the TareaFotos of the desinstallion_data on each installion_tasks
     *
     * @param  int $installion_tasks
     * @return Array
     */
    private function getDesinstallionTasks( $installion_tasks  ){

        $desinstallation_tasks = [];

        foreach($installion_tasks as $installion_task ){

            foreach($installion_task->desinstallion_data as $task_section_slug => $desinstallion_data ){

                $task = $this->getTareaByFoto($desinstallion_data->task_id , $desinstallion_data->section_id);
                
                if($task){
                    $task->section_name = $desinstallion_data->section_name;
                    $desinstallation_tasks[$task_section_slug] = $task;
                }
            }
        }

        return $desinstallation_tasks;
    }


    /**
     * Getting the TareaFotos of the installion_data on each installion_tasks
     *
     * @param  int $installion_tasks
     * @return Array
     */
    private function getInstallionTasks( $installion_tasks  ){

        $installation_tasks = [];

        foreach($installion_tasks as $installion_task ){

            $zona_publicitarias = json_decode($installion_task->zonapublicitaria_id);

            foreach( $zona_publicitarias as $zona_publicitaria ){

                $task = $this->getTareaByFoto($installion_task->id , $zona_publicitaria->zonasbuses_id);
                
                if( $task ){
                    $task->section_name = $zona_publicitaria->name;
                    $installation_tasks[$installion_task->id."_".$zona_publicitaria->zonasbuses_id ] = $task;
                }
            }
        }

        return $installation_tasks;
    }


    /**
     * Getting the TareaFotos of the desinstallion_data on each installion_tasks
     *
     * @param  int $tasks_id
     * @param  int $section_id
     * @return Model|null
     */
    private function getTareaByFoto($task_id , $section_id){

        $task = Tarea::where( "id" , $task_id)->with(['tareaFotos' => function ($query) use ($task_id , $section_id) {
            $query
            ->where("tarea_id" , $task_id)
            ->where("section_id" , $section_id);
        }])->first();

        if($task && count($task->tareaFotos) > 0 ){

            return $task;
        }

        return false;

    }
}
