<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EjecutivoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EjecutivoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EjecutivoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Ejecutivo');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/ejecutivo');
        $this->crud->setEntityNameStrings('ejecutivo', 'ejecutivos');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'label' => 'Nombre',
            'name' => 'name',
            'type' => 'text',
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(EjecutivoRequest::class);

        $this->crud->addField([
            'label' => 'Nombre:',
            'name' => 'name',
            'type' => 'text',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
