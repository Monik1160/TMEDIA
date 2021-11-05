<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ZonaPublicitariaRequest;
use App\Models\Bus;
use App\Models\ZonaPublicitaria;
use App\Traits\ApiResponder;
use http\Env\Response;
use Illuminate\Http\Request;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

/**
 * Class ZonaPublicitariaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ZonaPublicitariaCrudController extends CrudController
{
    use ApiResponder;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\ZonaPublicitaria');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/zonapublicitaria');
        $this->crud->setEntityNameStrings('zona publicitaria', 'zonas publicitarias');
    }

    protected function setupListOperation()
    {
        $this->crud->removeButton('show');

        $this->crud->addColumn([
            'name' => 'id',
            'label' => "Codigo",
        ]);
        $this->crud->addColumn([
            'name' => 'name',
            'label' => "Nombre",
        ]);

        $this->crud->addColumn([  // Select2
            'label' => "Zonas Buses",
            'type' => 'select',
            'name' => 'zonasbuses_id', // the db column for the foreign key
            'entity' => 'zonasbuses', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ZonaPublicitariaRequest::class);

        $this->crud->addField([   // Text
            'name' => 'name',
            'label' => "Nombre",
            'type' => 'text',
        ]);

        $this->crud->addField([  // Select2
            'label' => "Zonas Buses",
            'type' => 'select2',
            'name' => 'zonasbuses_id', // the db column for the foreign key
            'entity' => 'zonasbuses', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user

            // optional
            'model' => "App\Models\ZonasBuses", // foreign key model
            'default' => 2, // set the default value of the select2
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

}
