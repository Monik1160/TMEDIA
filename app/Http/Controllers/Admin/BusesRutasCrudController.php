<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BusesRutasRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BusesRutasCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BusesRutasCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\BusesRutas');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/busesrutas');
        $this->crud->setEntityNameStrings('busesrutas', 'Rutas de Buses');
        $this->crud->denyAccess(['update', 'create', 'delete']);
        $this->crud->setListView('admin.buses.ruta_bus');
    }

    protected function setupListOperation()
    {
        $this->crud->removeButton('show');
        $this->crud->removeButton('revisions');
        $this->crud->removeButton('update');

        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->setFromDb();
    }

    protected function setupCreateOperation()
    {
//        $this->crud->setValidation(BusesRutasRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
