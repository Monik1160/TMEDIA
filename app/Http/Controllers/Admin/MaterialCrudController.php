<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MaterialRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MaterialCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MaterialCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Material');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/materiales');
        $this->crud->setEntityNameStrings('material', 'materiales');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'name',
            'label' => "Nombre del material:",
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => "Descripción",
            'type' => 'text',
        ]);
        $this->crud->addColumn([
        'name' => 'need_desinstallation',
        'label' => 'Requiere desinstalación?',
        'type' => 'radio',
        'options' => [
            0 => 'No',
            1 => 'Si'
        ],
        // optional
        'inline' => true,
    ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MaterialRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'label' => "Nombre del material:",
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => "Descripción",
            'type' => 'textarea',
        ]);
        $this->crud->addField([
            'name' => 'need_desinstallation',
            'label' => 'Requiere desinstalación?',
            'type' => 'radio',
            'options' => [
                0 => 'No',
                1 => 'Si'
            ],
            // optional
            'inline' => true,
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
