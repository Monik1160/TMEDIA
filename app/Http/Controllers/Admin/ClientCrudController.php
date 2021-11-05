<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClientRequest;
use App\Models\IdentificationType;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ClientCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ClientCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Client');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/clientes');
        $this->crud->setEntityNameStrings('cliente', 'clientes');
    }

    protected function setupListOperation()
    {
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');
//        $this->crud->removeButton('update');

        $this->crud->addColumn([
            'name' => 'displayName',
            'label' => 'Nombre',
            'type' => 'text',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('parent', function ($q) use ($searchTerm) {
                    $q->where('company_name', 'like', '%' . $searchTerm . '%');
                });
                $query->orWhere('first_name', 'like', '%' . $searchTerm . '%');
                $query->orWhere('last_name', 'like', '%' . $searchTerm . '%');
            },
        ]);

        $this->crud->addColumn([
            'name' => 'identification',
            'label' => "Identificación",
            'type' => 'text'
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ClientRequest::class);

        $this->crud->addField([
            'name' => 'first_name',
            'label' => "Nombre",
            'type' => 'text',
            'attributes' => [
                'autocomplete' => 'off',
            ],
        ]);

        $this->crud->addField([

            'name' => 'contact_type',
            'type' => 'hidden',
            'default' => 'individual',
        ]);

        $this->crud->addField([
            'name' => 'contact_type',
            'type' => 'hidden',
            'default' => 'individual',
        ]);

        $this->crud->addField([
            'name' => 'last_name',
            'label' => "Apellido",
            'type' => 'text',
            'attributes' => [
                'autocomplete' => 'off',
            ],
        ]);

        $this->crud->addField([
            'label' => 'Tipo de identificación',
            'type' => 'select2',
            'name' => 'identification_type_id', // the db column for the foreign key
            'entity' => 'identification_type', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\IdentificationType", // foreign key model
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
                'style' => 'float: left;',
            ]
        ]);

        $this->crud->addField([
            'name' => 'identification',
            'label' => "Identificación",
            'type' => 'text',
            'attributes' => [
                'autocomplete' => 'off',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
                'style' => 'float: left;',
            ]
        ]);

        $this->crud->addField([
            'name' => 'first_name',
            'label' => 'Nombre',
            'type' => 'text',
            'attributes' => [
                'autocomplete' => 'off',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
                'style' => 'float: left;',
            ],
        ]);

        $this->crud->addField([
            'name' => 'last_name',
            'label' => 'Apellidos',
            'type' => 'text',
            'attributes' => [
                'autocomplete' => 'off',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
                'style' => 'float: left;',
            ],
        ]);

        $this->crud->addField([
            'name' => 'company_name',
            'label' => 'Nombre de Compañia',
            'type' => 'text',
            'attributes' => [
                'autocomplete' => 'off',
            ],
        ]);

        $this->crud->addField([   // Hidden
            'name' => 'is_provider',
            'type' => 'hidden',
            'value' => 0,
        ]);

        $this->crud->addField([   // Hidden
            'name' => 'is_client',
            'type' => 'hidden',
            'value' => 1,
        ]);

        $this->crud->addField([
            'name' => 'website',
            'label' => "Sitio Web",
            'type' => 'url',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
                'style' => 'float: left;',
            ],
            'attributes' => [
                'autocomplete' => 'off',
            ],
        ]);

        $this->crud->addField([
            'name' => 'email',
            'label' => "Correo Eléctronico",
            'type' => 'email',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
                'style' => 'float: left;',
            ],
            'attributes' => [
                'autocomplete' => 'off',
            ],
        ]);

        $this->crud->addField([
            'name' => 'phone',
            'label' => "Teléfono",
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
                'style' => 'float: left;',
            ],
            'attributes' => [
                'autocomplete' => 'off',
            ],
        ]);

        $this->crud->addField([
            'name' => 'mobile',
            'label' => "Móvil",
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
                'style' => 'float: left;',
            ],
            'attributes' => [
                'autocomplete' => 'off',
            ],
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
            'tab' => 'Dirección',
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
            'tab' => 'Dirección',
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
            'tab' => 'Dirección',
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
            'tab' => 'Dirección',
        ]);


        /**
         * Ventas Y Compras
         */

        $this->crud->addField([
            'name' => 'bank_name',
            'label' => 'Nombre de Banco',
            'type' => 'text',
            'tab' => 'Información Bancaria',
        ]);

        $this->crud->addField([
            'name' => 'bank_account',
            'label' => 'Cuenta de Banco',
            'type' => 'text',
            'tab' => 'Información Bancaria',
        ]);

        $this->crud->addField([
            'name' => 'bank_currency',
            'label' => 'Moneda de Pago',
            'type' => 'text',
            'tab' => 'Información Bancaria',
        ]);


        $this->crud->addField([
            'name' => 'observation',
            'label' => "Notas del Cliente:",
            'type' => 'textarea',
            'tab' => 'Notas internas',
            'attributes' => [
                'placeholder' => 'Notas internas'
            ],
        ]);

        $this->crud->addField([
            'name' => 'create-update-script',
            'type' => 'view',
            'view' => 'admin/contacts/create-update-script',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
