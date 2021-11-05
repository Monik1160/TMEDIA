<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RutaRequest;
use App\Models\Autobusero;
use App\Models\Bus;
use App\Models\Ruta;
use App\Models\Zona;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;

use App\Models\ZonaPublicitaria;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

/**
 * Class RutaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RutaCrudController extends CrudController
{
    use ApiResponder;

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Ruta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/ruta');
        $this->crud->setEntityNameStrings('ruta', 'rutas');
        $this->crud->removeAllButtons();
    }

    protected function setupListOperation()
    {
        $this->crud->removeAllButtons();

        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Nombre de la ruta',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'cod_mopt',
            'label' => 'Codigo del MOPT',
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'label' => 'Zona',
            'type' => "select",
            'name' => 'zona_id',
            'entity' => 'zona',
            'attribute' => "name",
            'model' => 'App\Models\Zona',
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(RutaRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->addField([
            'name' => 'name',
            'label' => 'Nombre de la zona',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'cod_mopt',
            'label' => 'Codigo del MOPT',
            'type' => 'text',
        ]);

        $this->crud->addField([  // Select2
            'label' => "Zona",
            'type' => 'select',
            'name' => 'zona_id', // the db column for the foreign key
            'entity' => 'zona', // the method that defines the relationship in your Model
            'attribute' => 'description', // foreign key attribute that is shown to user
            'model' => "App\Models\Zona", // foreign key model

        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function getRutas($ruta_id)
    {
        $ruta_autobusero = Ruta::whereHas('autobuseros', function ($query) use ($ruta_id) {
            $query->where('ruta_contacto.contact_id', $ruta_id);
        })->get();


        return $ruta_autobusero;

    }

    public function addRutaToBus($ruta_id, $autobusero_id, $bus_id)
    {

        $bus = Bus::find($bus_id);
        $ruta_name = Ruta::find($ruta_id);
        $exists = DB::table('ruta_bus')
                ->where('buses_id', $bus_id)
                ->where('rutas_id', $ruta_id)
                ->count() > 0;

        $datas = '<tr class="array-row" id="ruta_' . $ruta_id . '"><td>' . $ruta_name->name . '</td><td>

                          <a data-id=' . $ruta_id . ' id="delete_ruta"
                            class="btn btn-xs btn-default delete_ruta" data-button-type="delete">
                            <i class="fa fa-trash"></i> Eliminar Zona
                        </a>
                    </td> </tr>';

        if ($exists == false) {
            $bus->rutas()->attach($ruta_id);
            return $datas;
        } else {
            return $this->errorResponse($datas,403);
        }

    }

    public function removeRutaFromBus(Request $request, $ruta_id)
    {
        $bus = Bus::find($request->bus_id_add);
        $bus->rutas()->detach($ruta_id);

        return $this->successResponse($bus,200);
    }
}
