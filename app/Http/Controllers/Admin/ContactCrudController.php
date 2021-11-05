<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewUser;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\BackpackUser;
use App\Models\Contact;
use App\Models\IdentificationType;
use App\Models\Tag;
use App\Traits\ApiResponder;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class ContactCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ContactCrudController extends CrudController
{
    use ApiResponder;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }

    public function setup()
    {
        $this->crud->setModel('App\Models\Contact');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/contact');
        $this->crud->setEntityNameStrings('contacto', 'contactos');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'id',
            'label' => '#',
        ]);

        $this->crud->addColumn([
            'name' => 'provider_type',
            'label' => "Tipo de Contacto",
            'type' => 'text',
            'attributes' => [
                'autocomplete' => 'off',
            ],
        ]);

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

        $this->crud->addColumn([
            'label' => 'Tipo de identificación',
            'type' => 'select',
            'name' => 'identification_type_id',
            'entity' => 'identification_type',
            'attribute' => 'name',
            'model' => IdentificationType::class,
        ]);

        $this->crud->addColumn([
            'name' => 'is_client',
            'label' => "Cliente",
            'type' => 'boolean'
        ]);

        $this->crud->addColumn([
            'name' => 'is_provider',
            'label' => "Provedor",
            'type' => 'boolean'
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ContactRequest::class);


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

        /**
         * Dirección
         */
        $this->crud->addField([
            'name' => 'address',
            'label' => "Dirección",
            'type' => 'textarea',
            'tab' => 'Dirección',
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
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
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
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
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
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
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
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'post_office_box',
            'label' => "Apartado Postal",
            'type' => 'text',
            'tab' => 'Dirección',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'attributes' => [
                'placeholder' => 'P.O.B'
            ],
        ]);

        /**
         * Ventas Y Compras
         */

        $this->crud->addField([
            'name' => 'bank_name',
            'label' => 'Nombre de Banco',
            'type' => 'text',
            'tab' => 'Compras - Ventas',
        ]);

        $this->crud->addField([
            'name' => 'bank_account',
            'label' => 'Cuenta de Banco',
            'type' => 'text',
            'tab' => 'Compras - Ventas',
        ]);

        $this->crud->addField([
            'name' => 'bank_currency',
            'label' => 'Moneda de Pago',
            'type' => 'text',
            'tab' => 'Compras - Ventas',
        ]);

        $this->crud->addField([   // CustomHTML
            'name' => 'ventas_separator',
            'type' => 'custom_html',
            'value' => '<br><h4>Ventas</h4><hr>',
            'tab' => 'Compras - Ventas',
        ]);

        $this->crud->addField([
            'label' => 'Cliente',
            'name' => 'is_client',
            'type' => 'radio',
            'inline' => true,
            'options' => [
                0 => 'No',
                1 => 'Si'
            ],
            'default' => 0,
            'tab' => 'Compras - Ventas',
        ]);
        $this->crud->addField([   // CustomHTML
            'name' => 'compras_separator',
            'type' => 'custom_html',
            'value' => '<br><h4>Compras</h4><hr>',
            'tab' => 'Compras - Ventas',
        ]);

        $this->crud->addField([
            'label' => 'Provedor',
            'name' => 'is_provider',
            'type' => 'toggle',
            'inline' => true,
            'options' => [
                0 => 'No',
                1 => 'Si'
            ],
            'default' => 0,
            'hide_when' => [
                0 => ['is_agency', 'provider_type', 'ruta'],
            ],
            'tab' => 'Compras - Ventas',
        ]);

        $this->crud->addField([
            'name' => 'is_agency',
            'label' => 'Agencia',
            'type' => 'radio',
            'options' => [
                0 => 'No',
                1 => 'Si'
            ],
            // optional
            'inline' => true,
            'tab' => 'Compras - Ventas',
        ]);

        $this->crud->addField([
            'name' => 'provider_type',
            'label' => "Tipo de Provedor",
            'type' => 'select_from_array',
            'options' => ['General' => 'General', 'Autobusero' => 'Autobusero', 'Instalador' => 'Instalador'],
            'allows_null' => false,
            'default' => 'General',
            'tab' => 'Compras - Ventas',
        ]);


        $this->crud->addField([
            'name' => 'observation',
            'label' => "",
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

        $this->crud->setValidation(UpdateContactRequest::class);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return string
     */
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $user = BackpackUser::where('userable_id', '=', $id)->first();
        if ($user) {
            $user->delete();
        }

        return $this->crud->delete($id);
    }

    /**
     * Update the specified resource in the database.
     *
     * @return Response
     */
    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // update the row in the db
        $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        if ($request->provider_type != 'Instalador') {
            $this->data['entry']->user()->delete();
        } else {
            $exist_user = BackpackUser::find($this->data['entry']->id);
            if (empty($exist_user)) {
                $user = new User([
                    'email' => $this->data['entry']->email,
                    'first_name' => $this->data['entry']->first_name,
                    'last_name' => $this->data['entry']->last_name,
                    'password' => bcrypt('secret'),
                ]);

                $this->data['entry']->user()->save($user);

                $this->data['entry']->user->assignRole('Instalador');
            }
        }

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
}
