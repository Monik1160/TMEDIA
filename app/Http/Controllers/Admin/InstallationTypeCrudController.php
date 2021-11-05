<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InstallationTypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class InstallationTypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class InstallationTypeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\InstallationType');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/installationtype');
        $this->crud->setEntityNameStrings('Tipo de Instalación', 'Tipo de Instalación');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'label' => 'Tipo de instalación',
            'name' => 'name',
            'type' => 'text',
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(InstallationTypeRequest::class);

        $this->crud->addField([
            'label' => 'Tipo de instalación',
            'name' => 'name',
            'type' => 'text',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
