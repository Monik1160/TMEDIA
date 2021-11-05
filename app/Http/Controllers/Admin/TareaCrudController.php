<?php

namespace App\Http\Controllers\Admin;

use App\Events\MoveMontajes;
use App\Events\TareasPath;
use App\Http\Requests\TareaRequest;
use App\Http\Requests\UpdateTareaRequest;
use App\Models\BackpackUser;
use App\Models\Bus;
use App\Models\CampaignDetails;
use App\Models\Campaing;
use App\Models\Contact;
use App\Models\Installer;
use App\Models\Notifications;
use App\Models\NotificationUser;
use App\Models\Tarea;
use App\Models\Tareatype;
use App\Models\ZonaPublicitaria;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class TareaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TareaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }

    public function setup()
    {
        $this->crud->setModel('App\Models\Tarea');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tarea');
        $this->crud->setEntityNameStrings('tarea', 'tareas');

//        $this->crud->setUpdateView('admin.task.edit');
    }

    protected function setupListOperation()
    {
        $this->crud->removeButton('delete');
        $this->crud->removeButton('create');
        //$this->crud->removeButton('update');
        $this->crud->removeButton('show');

        $this->crud->addButtonFromView('line', 'logistica', 'Task/Actions/change_bus', 'beginning');
        $this->crud->addButtonFromView('line', 'asign-task', 'asign-task', 'beginning');
        $this->crud->addButtonFromView('line', 'progress', 'Task/Actions/progress', 'beginning');

        $this->crud->addColumn([  // Select2
            'label' => "Campaña",
            'type' => "select",
            'name' => 'campaing_id', // the db column for the foreign key
            'entity' => 'campaing', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => Campaing::class
        ]);
        $this->crud->addColumn([  // Select2
            'label' => "Tipo de Tarea",
            'type' => 'select',
            'name' => 'tarea_type_id', // the db column for the foreign key
            'entity' => 'tareatype', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => Tareatype::class, // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'zonapublicitaria_id', // The db column name
            'label' => "Zonas de Instalación", // Table column heading
            'type' => 'json'
        ]);

        $this->crud->addColumn([  // Select2
            'label' => "Instalador Asignado",
            'type' => 'select',
            'name' => 'installer', // the db column for the foreign key
            'entity' => 'installer', // the method that defines the relationship in your Model
            'attribute' => 'first_name', // foreign key attribute that is shown to user
            'model' => Installer::class, // foreign key model
        ]);

        $this->crud->addColumn([
            // select_from_array
            'name' => 'status',
            'label' => "Status",
            'type' => 'select_from_array',
            'options' => [1 => 'Creada', 2 => 'Asignada', 3 => 'Aceptada', 4 => 'Iniciada', 5 => 'Finalizada', 6 => 'Cancelada', 7 => 'Cambio de Bus'],
        ]);

        $this->crud->addColumn([
            'name' => 'monto',
            'label' => 'Monto',
            'type' => 'number',
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(TareaRequest::class);

        $this->crud->addField([  // Select2
            'label' => "Tipo de Tarea:",
            'type' => 'select2',
            'name' => 'tarea_type_id', // the db column for the foreign key
            'entity' => 'tareatype', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => Tareatype::class, // foreign key model
        ]);

        $this->crud->addField([  // Select2
            'label' => "Campaña",
            'type' => "select2_from_ajax",
            'name' => 'campaing_id', // the db column for the foreign key
            'entity' => 'campaing', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'data_source' => url("api/internal/campana"), // url to controller search function (with /{id} should return model)
            'placeholder' => 'Seleccione Campaña', // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results,
        ]);

        $this->crud->addField([
            'label' => 'Arte',
            'type' => "select2_from_ajax",
            'name' => 'arte_id', // the column that contains the ID of that connected entity
            'entity' => 'arte', // the method that defines the relationship in your Model
            'attribute' => "description", // foreign key attribute that is shown to user
            'data_source' => url("api/internal/arte"), // url to controller search function (with /{id} should return model)
            'placeholder' => 'Seleccione Arte', // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'dependencies' => ['campaing_id'], // when a dependency changes, this select2 is reset to null
            'method' => 'GET', // optional - HTTP method to use for the AJAX call (GET, POST),
        ]);

        $this->crud->addField([  // Select2
            'label' => "Zona de instalación",
            'type' => "select2_from_ajax",
            'name' => 'zonapublicitaria_id', // the db column for the foreign key
            'entity' => 'zonapublicitaria', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'data_source' => url("api/internal/zonas"), // url to controller search function (with /{id} should return model)
            'placeholder' => 'Seleccione Zonas Publicitaria', // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results,
        ]);

        $this->crud->addField([
            'label' => "Seleccione Autobus",
            'type' => "select2_from_ajax",
            'name' => 'bus_id', // the db column for the foreign key // the column that contains the ID of that connected entity
            'entity' => 'bus', // the method that defines the relationship in your Model
            'attribute' => "placa", // foreign key attribute that is shown to user
            'data_source' => url("api/internal/bus"), // url to controller search function (with /{id} should return model)
            'placeholder' => 'Seleccione Bus', // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'dependencies' => ['zonapublicitaria_id'], // when a dependency changes, this select2 is reset to null
            'method' => 'GET', // optional - HTTP method to use for the AJAX call (GET, POST),
        ]);

        $this->crud->addField([  // Select2
            'label' => "Seleccione Material",
            'type' => 'select2',
            'name' => 'material_id', // the db column for the foreign key
            'entity' => 'material', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Material", // foreign key model
        ]);

        $this->crud->addField([   // Textarea
            'name' => 'notes',
            'label' => 'Notas:',
            'type' => 'textarea'
        ]);

        $this->crud->addField([
            'label' => "Montaje:",
            'name' => "montaje",
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
            'disk' => 's3', // in case you need to show images from a different disk
        ]);

    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(TareaRequest::class);
        $this->crud->addField([  // Select2
            'label' => "Campaña",
            'type' => "select2_from_ajax",
            'name' => 'campaing_id', // the db column for the foreign key
            'entity' => 'campaing', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'data_source' => url("api/internal/campana"), // url to controller search function (with /{id} should return model)
            'placeholder' => 'Seleccione Campaña', // placeholder for the select
            'minimum_input_length' => 0,
            'attributes' => [
            'disabled'  => 'disabled',
            ],
        ]);

        $this->crud->addField([
            // select_from_array
            'name' => 'status',
            'label' => "Status",
            'type' => 'select_from_array',
            'options' => [1 => 'Creada', 2 => 'Asignada', 3 => 'Aceptada', 4 => 'Iniciada', 5 => 'Finalizada', 6 => 'Cancelada', 7 => 'Cambio de Bus'],
            'attributes' => [
                'disabled'  => 'disabled',
            ],
        ]);

        $this->crud->addField([
            'name' => 'monto',
            'label' => 'Monto',
            'type' => 'number',
        ]);
    }

    public function addInstallerToTarea(Request $request)
    {
        $user = BackpackUser::where('userable_id','=', $request->installer)->first();

        $tarea = Tarea::find($request->idTarea);
        $tarea->status = 2;
        $tarea->installer_id = $request->installer;
        $tarea->save();

        $data = [
            'title' => 'Nueva Tarea Asignada',
            'message' => 'Te hemos asignado una nueva tarea.',
            'email_address' => 'admin@publimediacr.com'
        ];

        $notification = Notifications::create($data);

        $data = [
            'user_id' => $user->id,
            'notifications_id' => $notification->id,
            'read_at' => null
        ];
        NotificationUser::create($data);
    }


    public function zonas_list(Request $request)
    {
        $search_term = $request->input('q');

        $options = ZonaPublicitaria::query();

        if ($search_term) {
            $results = $options->where('name', 'LIKE', '%' . $search_term . '%')->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $results;
    }


    public function busesWithZonas(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = Bus::query();

        if (!$form['zonapublicitaria_id']) {
            return [];
        }

        if ($form['zonapublicitaria_id']) {
            $options = $options->whereHas('zonaspublicitarias', function ($q) use ($form) {
                $q->where('zonaspublicitarias_id', $form['zonapublicitaria_id'])->where('campaing_id', '=', null);
            });
        }

        if ($search_term) {
            $results = $options->where('description', 'LIKE', '%' . $search_term . '%')->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $results;
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TareaRequest $request)
    {
        // do something before validation, before save, before everything
        $response = $this->traitStore();
        $montaje_name = $request->montaje;

        event(new TareasPath($this->data['entry']));
        event(new MoveMontajes($this->data['entry'], $montaje_name));

        // do something after save
        return $response;
    }

    public function changeBus($id, $status)
    {
        $tarea = Tarea::find($id);

        return view('admin.task.change_bus',compact('tarea'));
    }

    public function progressTask($id)
    {
        $tarea = Tarea::find($id);

        return view('admin.task.progress',compact('tarea'));
    }
}
