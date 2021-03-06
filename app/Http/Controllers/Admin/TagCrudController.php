<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TagRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TagCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TagCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Tag');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tag');
        $this->crud->setEntityNameStrings('tag', 'tags');
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->addColumn([
            'label' => "Etiqueta",
            'type' => "text",
            'name' => 'name',
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(TagRequest::class);

        $this->crud->addField([
            'label' => "Etiqueta",
            'type' => "text",
            'name' => 'name',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
