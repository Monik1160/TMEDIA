<?php

namespace App\Http\Controllers\Admin\Campaign;

use App\Http\Requests\CampaingRequest;
use App\Models\Campaing;
use App\Models\Client;
use App\Models\Ruta;
use App\Models\Zona;
use App\Models\ZonaPublicitaria;
use App\Traits\ApiResponder;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class CampaignDetailsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CampaignDetailsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use ApiResponder;

    public function setup()
    {
        $this->crud->setModel('App\Models\Campaing');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/campaigndetails');
        $this->crud->setEntityNameStrings('Solicitud de Campaña', 'Solicitudes de Campañas');

    }

    protected function setupListOperation()
    {

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CampaingRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'client' => 'required',
            'name' => 'required',
            'date' => 'required',
            'requerie_desinstallion' => 'required',
            'posible_renovation' => 'required',
            'details' => 'required',
            'notes' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'client_id' => $request->client,
            'ejecutivo_id' => backpack_auth()->user()->id,
            'notes' => $request->notes,
            'requerie_desinstallion' => $request->requerie_desinstallion,
            'possible_renovation' => $request->posible_renovation,
            'start_date' => Carbon::parse($request->date[0]),
            'end_date' => Carbon::parse($request->date[1]),
            'status' => 1,
        ];
        $campaign = Campaing::create($data);

        $details = $request->details;

        foreach ($details as $detail) {
            $campaign->campaign_details()->create($detail);
        }


        return $this->successResponse('success', 200);
    }

    public function getClients()
    {
        $clients = Client::all();

        return $this->successResponse($clients, 200);
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
}
