<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NotificationsRequest;
use App\Models\NotificationUser;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class NotificationsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class NotificationsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Notifications');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/notificaciones');
        $this->crud->setEntityNameStrings('notifications', 'notifications');

    }

    protected function setupListOperation()
    {
        $this->crud->removeButton('create');
        $this->crud->removeButton('update');
        if (!backpack_user()->hasRole('developer')) {
            $this->crud->addClause('whereHas', 'users', function ($query) {
                $query->where('user_id', '=', backpack_user()->id);
            });
        }
        $this->crud->addColumn([
            'name' => 'title',
            'label' => 'Titulo',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'message',
            'label' => 'Mensaje',
            'type' => 'textarea',
        ]);

        $this->crud->addColumn([
            'name' => 'email_address',
            'label' => 'Email',
            'type' => 'email',
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(NotificationsRequest::class);

        $this->crud->addField([
            'name' => 'title',
            'label' => 'Titulo',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'message',
            'label' => 'Mensaje',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'name' => 'email_address',
            'label' => 'Email',
            'type' => 'email',
        ]);

        $this->crud->addField([
            'label' => 'Usuarios',
            'type' => 'select2_multiple',
            'name' => 'users',
            'entity' => 'users',
            'attribute' => 'name',
            'model' => User::class,
            'pivot' => true,
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

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
}
