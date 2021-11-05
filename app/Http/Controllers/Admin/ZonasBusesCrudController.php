<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ZonasBusesRequest;
use App\Models\Bus;
use App\Models\ZonaPublicitaria;
use App\Models\ZonasBuses;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ZonasBusesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ZonasBusesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\ZonasBuses');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/zonasbuses');
        $this->crud->setEntityNameStrings('zonasbuses', 'zonas_buses');
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->removeAllButtons();
        $this->crud->setFromDb();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ZonasBusesRequest::class);

        $this->crud->addField([   // Text
            'name' => 'name',
            'label' => "Nombre",
            'type' => 'text',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function addZonaToBus(Request $request, Bus $bus)
    {

        $bus = Bus::find($request->bus_id_add);
        $zona_name = ZonasBuses::find($request->zona_id_add);

        $exists = DB::table('buses_zonas')
                ->where('buses_id', $request->bus_id_add)
                ->where('zonas_buses_id', $request->zona_id_add)
                ->count() > 0;

        $datas = '<tr class="array-row" id="contact_relation_' . $request->zona_id_add . '"><td>' . $zona_name->name . '</td><td>

                          <a data-id=' . $request->zona_id_add . '
                            class="btn btn-xs btn-default delte" data-button-type="delete">
                            <i class="fa fa-trash"></i> Eliminar Zona
                        </a>
                    </td> </tr>';
        if ($exists == false) {
            $bus->zonaspublicitarias()->attach($request->zona_id_add);
            return $datas;
        } else {
            return $this->errorResponse('Please Contact Support Team', 404);
        }

    }

    public function removeZonaFromBus(Request $request, Bus $bus)
    {
        $bus = Bus::find($request->bus_id_add);
        $bus->zonaspublicitarias()->detach($request->zona_id_add);

        return response()->json(['status' => strtolower(trans('common.success'))]);
    }
}
