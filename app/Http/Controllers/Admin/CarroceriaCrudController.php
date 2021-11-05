<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CarroceriaRequest;
use App\Models\Bus;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CarroceriaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CarroceriaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as traitDestroy;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Carroceria');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/carroceria');
        $this->crud->setEntityNameStrings('carroceria', 'carrocerias');
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
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CarroceriaRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
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
        $bus = Bus::where('carroceria_id', $id)->count();

        if ($bus == 0) {
            return $this->crud->delete($id);
        }

        return response('We can not delete this item', 406);
    }
}
