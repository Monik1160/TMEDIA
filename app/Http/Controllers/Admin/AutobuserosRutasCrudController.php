<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AutobuserosRutasRequest;
use App\Models\Autobusero;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AutobuserosRutasCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AutobuserosRutasCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\AutobuserosRutas');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/autobuseros_rutas');
        $this->crud->setEntityNameStrings('y Actualizar Rutas de los Autobuseros', 'Rutas Autobuseros');
        $this->crud->denyAccess(['update', 'create', 'delete']);
        $this->crud->setListView('admin.autobuseros.ruta_autobusero');
    }

    protected function setupListOperation()
    {
        $this->crud->removeButton('show');
        $this->crud->removeButton('revisions');
        $this->crud->removeButton('update');

        $this->crud->addColumn([
            'label' => "Autobusero",
            'type' => "select",
            'name'      => 'contact_id',
            'entity'    => 'contact',
            'attribute' => 'displayname',
            'model'     =>  Autobusero::class,
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('contact', function ($q) use ($column, $searchTerm) {
                    $q->where('company_name', 'like', '%'.$searchTerm.'%');
                });
            }

        ]);
        $this->crud->addColumn([
            'label' => "Zona Publicitaria",
            'type' => "select",
            'name'      => 'rutas_id', // the method that defines the relationship in your Model
            'entity'    => 'rutas', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => "App\Models\Ruta", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('rutas', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AutobuserosRutasRequest::class);

        $this->crud->addField([
            'label'     => 'Autobusero',
            'type'      => 'select2',
            'name'      => 'contact_id',
            'entity'    => 'contact',
            'attribute' => 'displayname',
            'model'     =>  Autobusero::class,
        ]);

        $this->crud->addField([
            'label'     => 'Ruta de Autobusero',
            'type'      => 'select2',
            'name'      => 'rutas_id', // the method that defines the relationship in your Model
            'entity'    => 'rutas', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => "App\Models\Ruta", // foreign key model
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
