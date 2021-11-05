<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IdentificationTypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class IdentificationTypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class IdentificationTypeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\IdentificationType');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tipo-de-identificacion');
        $this->crud->setEntityNameStrings('Tipo de Identificación', 'Tipos de Identificación');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'label' => "Nombre",
            'type' => "text",
            'name' => 'name',
        ]);

        $this->crud->addColumn([
            'name' => 'validation_regx', // The db column name
            'label' => "Validación", // Table column heading
            'type' => 'text'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(IdentificationTypeRequest::class);

        $this->crud->addField([
            'label' => "Nombre",
            'type' => "text",
            'name' => 'name',
        ]);

        $this->crud->addField([
            'name' => 'validation_regx', // The db column name
            'label' => "Validación", // Table column heading
            'type' => 'text'
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
