<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InstallerRequest;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class InstallerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class InstallerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Installer');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/installer');
        $this->crud->setEntityNameStrings('instalador', 'instaladores');
    }

    protected function setupListOperation()
    {
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');
        $this->crud->removeButton('update');

        $this->crud->addColumn([
            'name' => 'displayname',
            'label' => 'Nombre',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'identification',
            'label' => "Identificación",
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'email',
            'label' => "Correo Electronico",
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'label' => 'Tipo de identificación',
            'type' => 'select',
            'name' => 'identification_type_id',
            'entity' => 'identification_type',
            'attribute' => 'name',
            'model' => "App\Models\IdentificationType",
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(InstallerRequest::class);

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

        $this->crud->addField([   // Hidden
            'name' => 'is_provider',
            'type' => 'hidden',
            'value' => 1,
        ]);

        $this->crud->addField([   // Hidden
            'name' => 'provider_type',
            'type' => 'hidden',
            'value' => 'Instalador',
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

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;


        if ($request->provider_type == 'Instalador') {
            $user = new User([
                'email' => $this->data['entry']->email,
                'first_name' => $this->data['entry']->first_name,
                'last_name' => $this->data['entry']->last_name,
                'password' => bcrypt('secret'),
            ]);

            $this->data['entry']->user()->save($user);
            $this->data['entry']->user->assignRole('Instalador');
            $this->data['entry']->user_id = $user->id;
            $this->data['entry']->save();
        }


        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();


        return $this->crud->performSaveAction($item->getKey());
    }
}
