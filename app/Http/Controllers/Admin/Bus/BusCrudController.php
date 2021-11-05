<?php

namespace App\Http\Controllers\Admin\Bus;

use App\Models\BusFotos;
use App\Http\Requests\BusRequest;
use App\Http\Requests\UpdateBusRequest;
use App\Models\Autobusero;
use App\Models\Bus;
use App\Models\Carroceria;
use App\Models\Ruta;
use App\Models\Zona;
use App\Models\ZonaPublicitaria;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Class BusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BusCrudController extends CrudController
{
    use ApiResponder;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Bus');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/bus');
        $this->crud->setEntityNameStrings('bus', 'buses');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            // show both text and email values in one column
            // this column is here to demo and test the custom searchLogic functionality
            'name' => 'model_function',
            'label' => 'Placa', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getFullPlate', // the method in your Model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('tipo_placa', 'like', '%' . $searchTerm . '%');
                $query->orWhere('placa', 'like', '%' . $searchTerm . '%');
            }
        ]);

        $this->crud->addColumn([
            'label' => "Carroceria",
            'type' => "select",
            'name' => 'carroceria_id',
            'entity' => 'carroceria',
            'attribute' => "name",
            'model' => Carroceria::class,
        ]);

        $this->crud->addColumn([
            'label' => "Autobusero",
            'type' => "select",
            'name' => 'contact_id',
            'entity' => 'autobusero',
            'attribute' => "displayname",
            'model' => Autobusero::class,
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('autobusero', function ($q) use ($column, $searchTerm) {
                    $q->where('company_name', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        $this->crud->addColumn([
            'name' => 'tipo_contrato',
            'label' => 'Tipo de contrato',
            'type' => 'select2_from_array',
            'options' => [
                '' => 'Sin tipo de contrato',
                'Fijo' => 'Contrato Fijo',
                'Consumo' => 'Contrato Consumo',
            ],
            'tab' => 'General',
        ]);

        $this->crud->addColumn([
            'name' => 'activo', // The db column name
            'label' => "Activo", // Table column heading
            'type' => 'boolean'
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(BusRequest::class);

        $this->crud->addField([
            'name' => 'tipo_placa',
            'label' => 'Tipo de placa',
            'type' => 'select2_from_array',
            'options' => [
                'PUBLI' => 'Sin tipo de placa',
                'AB' => 'AB',
                'AP' => 'AP',
                'CB' => 'CB',
                'CP' => 'CP',
                'GB' => 'GB',
                'GP' => 'GP',
                'HB' => 'HB',
                'HP' => 'HP',
                'LB' => 'LB',
                'LP' => 'LP',
                'PB' => 'PB',
                'SJB' => 'SJB'
            ],
            'tab' => 'General',
        ]);

        $this->crud->addField([
            'name' => 'placa',
            'label' => 'Placa',
            'type' => 'text',
            'tab' => 'General',
        ]);

        $this->crud->addField([
            'label' => 'Tipo de carroceria',
            'type' => 'select2',
            'name' => 'carroceria_id',
            'entity' => 'carroceria',
            'attribute' => 'name',
            'model' => Carroceria::class,
            'tab' => 'General',
        ]);

        $id = $this->crud->getCurrentEntryId() ?? false;

        if ($id) {
            $bus = Bus::find($id)->routeCount($id);
        }

        $attributes = (isset($id) && !empty($bus) > 0) ? ['class' => 'autobuseros_id form-control', 'disabled' => 'disabled'] : ['class' => 'autobuseros_id form-control'];


        $this->crud->addField([
            'label' => 'Autobusero',
            'type' => 'select2',
            'name' => 'autobusero_id',
            'entity' => 'autobusero',
            'attribute' => 'displayname',
            'model' => Autobusero::class,
            'tab' => 'General',
            'attributes' => $attributes, // change the HTML attributes of your input
        ]);

        $this->crud->addField([
            'name' => 'tipo_contrato',
            'label' => 'Tipo de contrato',
            'type' => 'select2_from_array',
            'options' => [
                '' => 'Sin tipo de contrato',
                'Fijo' => 'Contrato Fijo',
                'Consumo' => 'Contrato Consumo',
            ],
            'tab' => 'General',
        ]);

        $this->crud->addField([
            'name' => 'observaciones',
            'label' => 'Observaciones',
            'type' => 'textarea',
            'tab' => 'General',
        ]);

        $this->crud->addField([
            'name'        => 'activo', // the name of the db column
            'label'       => 'Activo', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label;
                0 => "No",
                1 => "Si"
            ],
            'tab' => 'General',
            'inline'      => true, // show the radios all on the same line?
        ]);


    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        $this->crud->setValidation(UpdateBusRequest::class);

        $this->crud->addField([
            'label' => 'Zona Publicitaria',
            'name' => 'zonaspublicitarias',
            'type' => 'bus_zonas',
            'tab' => 'Zona Publicitaria',
            'columns' => [
                'name' => [
                    'pivot' => true,
                    'label' => trans('student.name'),
                    'name' => 'name',
                ],
            ]
        ]);

        $this->crud->addField([
            'label' => 'Rutas',
            'name' => 'rutas',
            'type' => 'bus_rutas',
            'tab' => 'Rutas',
            'columns' => [
                'name' => [
                    'pivot' => true,
                    'label' => trans('student.name'),
                    'name' => 'name',
                ],
            ]
        ]);

        $this->crud->addField([
            'label' => 'Fotografias',
            'name' => 'artes',
            'type' => 'bus_images',
            'tab' => 'Fotografías',
        ]);
    }

    public function addImagesToBus(Request $request, $bus_id)
    {
        $validador = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required',
        ]);

        if ($validador->passes()) {
            $file = $request->file('image');
            $name = time() . $file->getClientOriginalName();
            $filePath = 'buses/bus_' . $bus_id . '/' . $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file));
            //Format Data
            $art_data = [
                'description' => $request->description,
                'image' => $filePath,
                'bus_id' => $bus_id
            ];
            //Save art
            BusFotos::create($art_data);

            $id_new = DB::getPdo()->lastInsertId();
            $datas = '<tr class="array-row" id="bus_' . $id_new . '"><td><img src="' . \Config::get('filesystems.disks.s3.url') . '/buses/bus_' . $bus_id . '/' . $name . '" style="width: 200px;height: 200px;"></td>
                            <td>' . $request->description . '</td>
                           <td>
                          <a data-id=' . $id_new . '
                            class="btn btn-xs btn-default delete_foto_bus" data-button-type="delete">
                            <i class="fa fa-trash"></i> Eliminar Fotografía
                        </a>
                        <a href="' . route('ajax.download.bus_images', ['bus_id' => $bus_id, 'photo_id' => $id_new]) . '" target="" class="btn btn-primary download_a"><i class="far fa-download"></i> Download Image</a>

                    </td> </tr>';

            return $datas;
        } else {
            return $this->errorResponse($validador->errors()->all(), 404);
        }
    }

    public function deleteBusImages($busImages_id)
    {
        $images = BusFotos::find($busImages_id);
        $images->delete();
        return $this->successResponse('Successfully', 200);
    }

    public function download_photo($bus_id, $busImages_id)
    {
        $bus_photos = BusFotos::find($busImages_id);

        $disk = Storage::disk('s3')->url('buses/bus_' . $bus_id . '/' . $bus_photos->image);

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . basename($disk));
        header("Content-Type: " . $bus_photos->mime);

        return readfile($disk);
    }


}
