<?php

namespace App\Http\Controllers\Api\internals;

use App\Http\Controllers\BaseApiController;
use App\Models\Autobusero;
use App\Models\AutobuserosRutas;
use App\Models\Bus;
use App\Models\BusesRutas;
use App\Models\BusesZonasPublicitarias;
use App\Models\CampaignDesigns;
use App\Models\CampaignDetails;
use App\Models\Campaing;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\FinanceCampaign;
use App\Models\Ruta;
use App\Models\Tarea;
use App\Models\Zona;
use App\Models\ZonaPublicitaria;
use App\Traits\EmailNotification;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CampaignController extends BaseApiController
{
    use EmailNotification;

    public function getClients()
    {
        $clients = Client::all();

        $data = array();

        foreach ($clients as $client) {
            $name = '';

            if ($client->company_name == '') {
                $name = $client->first_name . ' ' . $client->last_name;
            } else {
                $name = $client->company_name;
            }

            $data[] = [
                'id' => $client->id,
                'name' => $name,
            ];
        }


        return $this->successResponse($data, 200);
    }

    public function getZonasPublicitarias()
    {
        $zonas_publicitarias = ZonaPublicitaria::all();

        return $this->successResponse($zonas_publicitarias, 200);
    }

    public function getZonas()
    {
        $zonas = Zona::all();
        $data = [];
        foreach ($zonas as $zona) {
            $data[] = [
                'id' => $zona->id,
                'name' => $zona->description . ' | ' . $zona->provincia->name,
            ];
        }

        return $this->successResponse($data, 200);
    }

    public function getRutas()
    {
        $rutas = Ruta::all();

        return $this->successResponse($rutas, 200);
    }

    public function getCampaigns(Request $request)
    {
        $campaigns = Campaing::find($request->campaign_id);
        $details = [];
        $finances = [];
        $artes = [];

        foreach ($campaigns->arte as $arte) {
            $artes[] = [
                'id' => $arte->id,
                'name' => $arte->name,
                'image' => \Config::get('filesystems.disks.s3.url') . '/' . $arte->image
            ];
        }

        foreach ($campaigns->campaign_details as $detail) {
            $details[] = [
                'id' => $detail->id,
                'ruta' => $detail->ruta,
                'zona' => $detail->zona,
                'zona_publicitaria' => json_decode($detail->zona_publicitaria),
                'arte' => $detail->arte,
                'notes' => $detail->notes,
                'bus' => $detail->bus ? $detail->bus : null,
                'autobusero' => $detail->bus ? $detail->bus->autobusero : null,
            ];
        }

        foreach ($campaigns->finance_campaign as $finance) {
            $finances[] = [
                'id' => $finance->id,
                'odc' => $finance->odc,
                'odf' => $finance->odf,
                'comision' => $finance->comision,
                'monto' => $finance->monto,
                'start_date' => $finance->start_date,
                'end_date' => $finance->end_date,
                'notes' => $finance->notes,
            ];
        }

        if ($campaigns->cliente->company_name == '') {
            $name = $campaigns->cliente->first_name . ' ' . $campaigns->cliente->last_name;
        } else {
            $name = $campaigns->cliente->company_name;
        }
        $clientes = [
            'id' => $campaigns->cliente->id,
            'name' => $name,
        ];


        $data = [
            'campaign_id' => $campaigns->id,
            'client' => $clientes,
            'name' => $campaigns->name,
            'status' => ($campaigns->status == 5) ? true : false,
            'date' => [Carbon::parse($campaigns->start_date, 'UTC'), $campaigns->end_date],
            'possible_renovation' => $campaigns->possible_renovation,
            'requerie_desinstallion' => $campaigns->requerie_desinstallion,
            'notes' => $campaigns->notes,
            'details' => $details,
            'finances' => $finances,
            'artes' => $artes,
            'comision' => $campaigns->comision,
            'monto' => $campaigns->monto,
        ];

        return $this->successResponse($data, 200);
    }

    public function removeDetailsCampaign(Request $request)
    {
        CampaignDetails::find($request->id)->delete();

        return $this->successResponse('success', 200);
    }

    public function removeFinanceCampaign(Request $request)
    {
        FinanceCampaign::find($request->id)->delete();

        return $this->successResponse('success', 200);
    }

    public function change_status($id, Request $request)
    {
        $campaign = Campaing::find($id);

        if ($request->status == 2 && !backpack_user()->hasRole('ejecutivo')){
            return $this->errorResponse('Acción solo para Ejecutivos',400);
        }
        if ($request->status == 3 && !backpack_user()->hasRole('financiero')){
            return $this->errorResponse('Acción solo para Finanzas',400);
        }

        if ($campaign->status != 1) {

            if ($request->status == 4) {
                $details = $campaign->campaign_details()->get();
                foreach ($details as $detail) {
                    if (empty($detail->bus_id)) {
                        return $this->errorResponse('Faltan Buses de Asginar', 409);
                    }
                }
            }

            if ($request->status == 5) {
                $details = $campaign->campaign_details()->get();
                foreach ($details as $detail) {
                    if ($detail->design()->get()->isEmpty()) {
                        return $this->errorResponse('Debes completar todos los diseños para continuar', 409);
                    }
                }
            }
        }

        $campaign->status = $request->status;
        $this->emailNotification($request->status, $campaign);
        $campaign->save();

        return $this->successResponse('success', 200);
    }

    public function emailNotification($status, $campaign)
    {

        switch ($status) {
            case 2:
                $subject = $campaign->name . ' esta lista para agregar información Financiera';
                $message = 'La campaña esta lista para agregarle la información financiera';
                $this->sendNotification($campaign, 'financiero', $subject, $message);
                break;
            case 3:
                $subject = $campaign->name . ' esta lista para asignación de Buses';
                $message = 'La campaña esta lista para proceder con la asignación de buses';
                $this->sendNotification($campaign, 'logistica', $subject, $message);
                break;
            case 4:
                $subject = $campaign->name . ' esta lista para agregar agregar diseños';
                $message = 'La campaña esta lista para subir los diseños de los buses';
                $this->sendNotification($campaign, 'diseño', $subject, $message);
                break;
            case 5:
                $subject = $campaign->name . ' esta lista para activar y creación de tarea';
                $message = 'La campaña esta lista para que las tareas sean creadas y activadas';
                $this->sendNotification($campaign, 'ejecutivo', $subject, $message);
                break;
            case 6:
                $subject = $campaign->name . ' en instalaciones';
                $message = 'La campaña esta en instalaciones';
                $this->sendNotification($campaign, '', $subject, $message);
                break;
        }
    }

    public function addBusToCampaign(Request $request)
    {
        $detail_campaign = CampaignDetails::find($request->detail_id);

        $campaign = Campaing::find($detail_campaign->campaign_id);

        foreach ($request->zona_publicitaria as $zona) {
            if ($request->previous_bus) {
                DB::table('installations_bookings_log')
                    ->where('zona_bus_id', '=', $zona)
                    ->where('bus_id', '=', $request->previous_bus['id'])
                    ->update([
                        'bus_id' => $request->bus_id,
                        'campaaign_id' => $detail_campaign->campaign_id,
                        'status' => 'Reservado',
                        'zona_bus_id' => $zona,
                        'start_date' => $campaign->start_date,
                        'end_date' => $campaign->end_date,
                    ]);
            } else {
                DB::table('installations_bookings_log')->insert([
                    'bus_id' => $request->bus_id,
                    'campaaign_id' => $detail_campaign->campaign_id,
                    'status' => 'Reservado',
                    'zona_bus_id' => $zona,
                    'start_date' => $campaign->start_date,
                    'end_date' => $campaign->end_date,
                ]);
            }
        }

        $detail_campaign->bus_id = $request->bus_id;
        $detail_campaign->save();


        if (isset($request->bus_change_task) == true) {
            $task = Tarea::find($request->task_id);
            $task->bus_id = $request->bus_id;
            $task->status = $task->status_changed;
            $task->status_changed = 0;
            $task->save();
        }

        return $this->successResponse('success', 200);
    }

    public function getBus(Request $request)
    {
        $zonas_publicitaria = $request->zonas_publicitaria;

        $data = Bus::where('autobusero_id', '=', $request->id)->where('activo', '=', 1)->wherehas('zonaspublicitarias')->with('zonaspublicitarias')->get();

        $targetBus = $data->filter(function ($item) use ($zonas_publicitaria) {
            $flag = true;

            $zones = $item->zonaspublicitarias()->pluck('zonas_buses_id')->toArray();

            for ($i = 0; $i < count($zonas_publicitaria); $i++) {
                if (!in_array($zonas_publicitaria[$i], $zones)) {
                    $flag = false;
                    break;
                }
            }

            return ($flag) ? $item : null;
        });


        $target_bus_ids = $targetBus->pluck('id')->toArray();

        $campaign = Campaing::find($request->campaign_id);

        $start_date = $campaign->start_date;
        $end_date = $campaign->end_date;

        $buses_not_available = DB::table('installations_bookings_log')->select('bus_id')
            ->whereIn('bus_id', $target_bus_ids)
            ->whereIn('zona_bus_id', $zonas_publicitaria)
            ->where(function ($query) {
                $query->where('status', '=', 'Instalado');
                $query->orWhere('status', '=', 'Reservado');
            })
            ->where(function ($query) use ($start_date, $end_date) {
                $query->where(function ($query) use ($start_date, $end_date) {
                    $query->where('start_date', '>=', $start_date);
                    $query->Where('end_date', '<=', $end_date);
                });
                $query->where(function ($query) use ($start_date, $end_date) {
                    $query->where('start_date', '<=', $start_date);
                    $query->Where('end_date', '>=', $end_date);
                });
                $query->orWhere(function ($query) use ($end_date) {
                    $query->where('start_date', '<=', $end_date);
                    $query->Where('end_date', '>=', $end_date);
                });
                $query->orWhere(function ($query) use ($start_date, $end_date) {
                    $query->where('start_date', '<=', $start_date);
                    $query->Where('end_date', '>=', $end_date);
                });
            })->distinct()->pluck('bus_id')->toArray();


        $buses_available = array_diff($target_bus_ids, $buses_not_available);

        $busAllow = [];

        foreach ($buses_available as $bus) {

            $bus_data = Bus::find($bus);

            $bandera = false;

            if (count($bus_data->rutas) > 0) {
                foreach ($bus_data->rutas as $ruta) {
                    if ($ruta->id == $request->route) {
                        $bandera = true;
                        break;
                    }
                }
            }


            if ($bandera == true) {
                $busAllow[] = $bus_data;
            }

        }


        $buses = [];

        foreach ($busAllow as $bus) {
            $bus_fotos = [];

            foreach ($bus->fotografias as $bus_foto) {
                $bus_fotos[] = [
                    'image' => \Config::get('filesystems.disks.s3.url') . '/' . $bus_foto->image,
                ];
            }

            $buses[] = [
                'id' => $bus->id,
                'placa' => $bus->placa,
                'tipo_placa' => $bus->tipo_placa,
                'carroceria' => $bus->carroceria->name,
                'fullPlate' => $bus->tipo_placa . ' ' . $bus->placa,
                'fotografias' => $bus_fotos,
                'observaciones' => $bus->observaciones,
                'tipo_contrato' => $bus->tipo_contrato
            ];
        }


        return $this->successResponse($buses, 200);

    }

    public function getAutobuseros(Request $request)
    {
        $route_id = $request->route_id;

        $autobuseros = Contact::whereHas('ruta', function ($q) use ($route_id) {
            $q->where('rutas_id', '=', $route_id);
        })->get();

        foreach ($autobuseros as $client) {
            if ($client->company_name == '') {
                $name = $client->first_name . ' ' . $client->last_name;
            } else {
                $name = $client->company_name;
            }

            $data[] = [
                'id' => $client->id,
                'name' => $name,
            ];
        }

        return $this->successResponse($data, 200);
    }

    public function getBusInformation(Request $request)
    {
        $bus = Bus::find($request->bus_id);

        $buses_designs = $bus->designs()->where('campaign_id', '=', $request->campaign_id)->get();
        $designs = [];
        $bus_fotos = [];


        foreach ($buses_designs as $design) {
            $designs[] = [
                'default' => 1,
                'highlight' => 1,
                'name' => $design->image,
                'path' => \Config::get('filesystems.disks.s3.url') . '/' . $design->image,
                'design_id' => $design->id,
                'status' => $design->status,
                'decline_message' => $design->decline_message,
            ];
        }

        foreach ($bus->fotografias as $bus_foto) {
            $bus_fotos[] = [
                'image' => \Config::get('filesystems.disks.s3.url') . '/' . $bus_foto->image,
            ];
        }

        $data = [
            'carroceria' => $bus->carroceria->name,
            'fullPlate' => $bus->tipo_placa . ' ' . $bus->placa,
            'fotografias' => $bus_fotos,
            'observaciones' => $bus->observaciones,
            'bus_id' => $bus->id,
            'designs' => $designs
        ];

        return $this->successResponse($data, 200);

    }

    public function addDesignsToBusCampaign(Request $request)
    {

        $file = $request->file('file');
        $name = time() . $file->getClientOriginalName();
        $filePath = 'campañas/campaña_' . $request->campaign_id . '/designs/bus_' . $request->bus_id . '/' . $name;
        Storage::disk('s3')->put($filePath, file_get_contents($file));

        $data = [
            'bus_id' => $request->bus_id,
            'campaign_id' => $request->campaign_id,
            'image' => $filePath,
            'detail_id' => $request->detail_id,
        ];

        $hola = CampaignDesigns::create($data);

        return $this->successResponse($hola, 200);
    }

    public function changeDesignStatus(Request $request)
    {
        $design = CampaignDesigns::find($request->design_id);

        $design->status = $request->status;
        if ($request->status == 3) {
            $design->decline_message = $request->decline_message;
        }

        $design->save();

        return $this->successResponse('Success', 200);
    }

    public function removeDesignsToBusCampaign(Request $request)
    {
        $design = CampaignDesigns::find($request->design_id);
        $design->delete();

        return $this->successResponse('success', 200);
    }

    public function createCampaignTasks($id, Request $request)
    {
        $campaign = Campaing::find($id);

        $campaign_details = $campaign->campaign_details()->get();

        foreach ($campaign_details as $details) {
            if ($details->bus_id == '') {
                $this->sendNotification($campaign, 'logistica', 'Faltan Buses por asignar', 'Por favor agregar buses a los detalles faltantes', 'Campaign');
                return $this->errorResponse('Faltan Buses por Asignar, notificacando a logistica', 400);
            }
            if ($details->design()->get()->isEmpty()) {
                $this->sendNotification($campaign, 'diseño', 'Faltan Diseños por agregar', 'Por favor completar los diseños faltantes', 'Campaign');
                return $this->errorResponse('Faltan Diseños por agregar, notificacando a diseño', 400);
            }
        }
        foreach ($campaign_details as $details) {

            $designs = $details->design()->get();
            $hola = array();

            foreach ($designs as $design) {
                $hola[] = [
                    'image' => $design->image
                ];
            }

            $desinstallion_tasks = $this->getDesinstallationTasks( $details );

            $data = [
                'bus_id' => $details->bus_id,
                'campaing_id' => $details->campaign_id,
                'campaing_detail_id' => $details->id,
                'arte_id' => $details->arte_id,
                'notes' => $campaign->notes,
                'zonapublicitaria_id' => $details->zona_publicitaria,
                'ruta_id' => $details->ruta_id,
                'desinstallion_data' => $desinstallion_tasks,
                'montaje' => json_encode($hola),
                'tarea_type_id' => count($desinstallion_tasks) > 1 ? 3 : 1
            ];

            Tarea::create($data);
        }

        $status = new Request([
            'status' => 6
        ]);

        $this->change_status($id, $status);

        return $this->successResponse('Success', 200);
    }

    public function getAutobuserosAll()
    {
        $autobuseros = Autobusero::all();

        $data = array();

        foreach ($autobuseros as $autobusero) {
            if ($autobusero->company_name == '') {
                $name = $autobusero->first_name . ' ' . $autobusero->last_name;
            } else {
                $name = $autobusero->company_name;
            }

            $data[] = [
                'id' => $autobusero->id,
                'name' => $name,
            ];
        }


        return $this->successResponse($data, 200);
    }

    public function getRoutes(Request $request, $id)
    {
        if (isset($request->bus) == 'true') {
            $rutas_selected = BusesRutas::where('buses_id', '=', $id)->get();
        } else {
            $rutas_selected = AutobuserosRutas::where('contact_id', '=', $id)->get();
        }

        $data_routes_selected = array();

        foreach ($rutas_selected as $ruta_selected) {
            $data_routes_selected[] = [
                'code' => $ruta_selected->rutas->id,
                'label' => $ruta_selected->rutas->name,
            ];
        }

        if (isset($request->autobusero_id)) {
            $rutas = AutobuserosRutas::where('contact_id', '=', $request->autobusero_id)->get();

            $data_rutas = array();

            foreach ($rutas as $ruta) {
                $data_rutas[] = [
                    'code' => $ruta->rutas->id,
                    'label' => $ruta->rutas->name,
                ];
            }
        } else {
            $rutas = Ruta::all();

            $data_rutas = array();

            foreach ($rutas as $ruta) {
                $data_rutas[] = [
                    'code' => $ruta->id,
                    'label' => $ruta->name,
                ];
            }
        }


        $data = array(
            'routes_selected' => $data_routes_selected,
            'routes_all' => $data_rutas
        );

        return $this->successResponse($data, 200);
    }

    public function getBusesAutobuseros($autobusero_id)
    {
        $buses = Bus::where('autobusero_id', '=', $autobusero_id)->get();

        $data = [];

        foreach ($buses as $bus) {
            $data[] = [
                'id' => $bus->id,
                'placa' => $bus->placa,
                'tipo_placa' => $bus->tipo_placa,
                'carroceria' => (!isset($bus->carroceria->name)) ? '' : $bus->carroceria->name,
                'fullPlate' => $bus->tipo_placa . ' ' . $bus->placa,
                'fotografias' => $bus->fotografias,
                'observaciones' => $bus->observaciones,
                'tipo_contrato' => $bus->tipo_contrato,
                'bucket' => \Config::get('filesystems.disks.s3.url'),
            ];
        }

        return $this->successResponse($data, 200);
    }

    public function saveAutobuserosRutas(Request $request)
    {
        DB::beginTransaction();
        try {
            $autobuseros = AutobuserosRutas::where('contact_id', '=', $request->autobusero_id);

            $checking = $this->verificationRouteBuses($autobuseros, $request->rutas_selected);

            if ($checking == false) {
                $autobuseros->delete();
            } else {
                return $this->errorResponse('Existen rutas relacionadas a Buses', 500);
            }

            foreach ($request->rutas_selected as $ruta) {
                AutobuserosRutas::create([
                    'rutas_id' => $ruta['code'],
                    'contact_id' => $request->autobusero_id,
                ]);
            }
            DB::commit();
            return $this->successResponse('success', 200);
        } catch (ValidationException $e) {
            DB::rollback();
            return $this->errorResponse($e, 403);
        }
    }

    public function saveBusesRutas(Request $request)
    {
        DB::beginTransaction();
        try {
            BusesRutas::where('buses_id', '=', $request->bus_id)->delete();

            foreach ($request->rutas_selected as $ruta) {
                BusesRutas::create([
                    'rutas_id' => $ruta['code'],
                    'buses_id' => $request->bus_id,
                ]);
            }
            DB::commit();
            return $this->successResponse('success', 200);
        } catch (ValidationException $e) {
            DB::rollback();
            return $this->errorResponse($e, 403);
        }
    }

    public function getCampaignsDetails(Request $request)
    {
        $details = CampaignDetails::where('id', '=', $request->detail_id)->first();

        $data = array();

        $data[] = [
            'id' => $details->id,
            'ruta' => $details->ruta,
            'zona' => $details->zona,
            'zona_publicitaria' => json_decode($details->zona_publicitaria),
            'arte' => $details->arte,
            'notes' => $details->notes,
            'bus' => $details->bus ? $details->bus : null,
            'autobusero' => $details->bus ? $details->bus->autobusero : null,
        ];

        return $this->successResponse($data, 200);
    }

    public function verificationRouteBuses($rutas_autobuseros, $rutas_seleccionadas)
    {
        $rutas_eliminadas = [];
        $flag = false;

        $merge = array_map(function ($values) {
            return $values['code'];
        }, $rutas_seleccionadas);


        foreach ($rutas_autobuseros->get() as $ruta_autobuseo) {
            if (!in_array($ruta_autobuseo->rutas_id, $merge)) {
                $rutas_eliminadas[] = $ruta_autobuseo->rutas_id;
            }
        }

        if (!empty($rutas_eliminadas)) {
            $buses_with_routes = BusesRutas::whereIn('rutas_id', array_values($rutas_eliminadas));
            $flag = count($buses_with_routes->get()) > 0;
        }

        return $flag;
    }

    private function getDesinstallationTasks( $campaign_details ){

        $desinstallation_tasks = [];

        $zonas_publicitarias = json_decode($campaign_details->zona_publicitaria);

        foreach ( $zonas_publicitarias as $zona_publicitaria ) {

            $installed_publicity = $this->getInstalledPublicity($campaign_details->bus_id , $zona_publicitaria->zonasbuses_id );

            if($installed_publicity){

                $task = Tarea::where("campaing_id" , $installed_publicity->campaaign_id )->where("bus_id" ,$installed_publicity->bus_id )->first();

                $desinstallation_tasks[$task->id."_".$zona_publicitaria->zonasbuses_id] =
                [
                    "image" => $campaign_details->arte->image ,
                    "task_id" => $task->id ,
                    "section_id" =>  $zona_publicitaria->zonasbuses_id,
                    "section_name" =>  $zona_publicitaria->name
                ];
            }

        };

        return $desinstallation_tasks;
    }


    private function getInstalledPublicity( $bus_id , $zona_publicitaria_id){

        return DB::table('installations_bookings_log')
        ->where('zona_bus_id', '=', $zona_publicitaria_id)
        ->where('bus_id', '=', $bus_id)
        ->where('status', '=', 'Instalado')
        ->orderBy('id', 'DESC')
        ->first();
    }
}
