<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ZonaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ZonaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ZonaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Zona');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/zona');
        $this->crud->setEntityNameStrings('zona', 'zonas');
    }

    protected function setupListOperation()
    {
        $this->crud->removeAllButtons();

        $this->crud->setValidation(ZonaRequest::class);


        $this->crud->addColumn([
            'name' => 'description',
            'label' => 'Nombre de la zona',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'label' => trans('edgcarmu::crgeodata.provincia.field'),
            'type' => "select",
            'name' => 'provincia_id', // the column that contains the ID of that connected entity
            'entity' => 'provincia', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'data_source' => url("api/internal/provincia"), // url to controller search function (with /{id} should return model)
            'placeholder' => trans('edgcarmu::crgeodata.provincia.placeholder'), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results,

        ]);


    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ZonaRequest::class);


        $this->crud->addField([
            'name' => 'cod_cacerio',
            'label' => 'Codigo de Cacerio',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => 'Nombre de la Ruta',
            'type' => 'text',
        ]);


        $this->crud->addField([
            'label' => trans('edgcarmu::crgeodata.provincia.field'),
            'type' => "select2_from_ajax",
            'name' => 'provincia_id', // the column that contains the ID of that connected entity
            'entity' => 'provincia', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'data_source' => url("api/internal/provincia"), // url to controller search function (with /{id} should return model)
            'placeholder' => trans('edgcarmu::crgeodata.provincia.placeholder'), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results,

        ]);

        $this->crud->addField([
            'label' => trans('edgcarmu::crgeodata.canton.field'),
            'type' => "select2_from_ajax",
            'name' => 'canton_id', // the column that contains the ID of that connected entity
            'entity' => 'canton', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'data_source' => url("api/internal/canton"), // url to controller search function (with /{id} should return model)
            'placeholder' => trans('edgcarmu::crgeodata.canton.placeholder'), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'dependencies' => ['provincia_id'], // when a dependency changes, this select2 is reset to null
            'method' => 'GET', // optional - HTTP method to use for the AJAX call (GET, POST),

        ]);

        $this->crud->addField([
            'label' => trans('edgcarmu::crgeodata.distrito.field'),
            'type' => "select2_from_ajax",
            'name' => 'distrito_id', // the column that contains the ID of that connected entity
            'entity' => 'distrito', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'data_source' => url("api/internal/distrito"), // url to controller search function (with /{id} should return model)
            'placeholder' => trans('edgcarmu::crgeodata.distrito.placeholder'), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'dependencies' => ['canton_id'], // when a dependency changes, this select2 is reset to null
            'method' => 'GET', // optional - HTTP method to use for the AJAX call (GET, POST),
        ]);

        $this->crud->addField([
            'label' => trans('edgcarmu::crgeodata.barrio.field'),
            'type' => "select2_from_ajax",
            'name' => 'barrio_id', // the column that contains the ID of that connected entity
            'entity' => 'barrio', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'data_source' => url("api/internal/barrio"), // url to controller search function (with /{id} should return model)
            'placeholder' => trans('edgcarmu::crgeodata.barrio.placeholder'), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'dependencies' => ['distrito_id'], // when a dependency changes, this select2 is reset to null
            'method' => 'GET', // optional - HTTP method to use for the AJAX call (GET, POST),

        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
