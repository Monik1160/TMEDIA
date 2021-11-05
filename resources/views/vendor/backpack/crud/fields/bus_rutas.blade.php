<?php
$autobuseros = \App\Models\Autobusero::all();
$bus_id = $crud->entry->id;

?>


<div @include('crud::inc.field_wrapper_attributes') class="panel-group" id="accordion">
    <h3 style="margin-top:0" class="panel-titles">{!! $field['label'] !!}

    </h3>

    <div id="colape" style="margin: 30px 0px">
        <div class="form-group">
            <label for="" class="control-label">Seleccione Ruta</label>
            <select name="productos" id="productos" class="form-control">
                <option value="">-Selecciones Ruta-</option>
            </select>

            <input type="hidden" id="bus" value="{{ $bus_id  }}">

        </div>
        <div class="array-controls btn-group m-t-10">
            <a id="contact-modal">
                <button type="button" id="add_ruta_btn" class="btn btn-sm btn-default">Agregar Ruta
                </button>
            </a>
        </div>
    </div>
    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0" id="rutas">
            <thead>
            <tr>
                <td>
                    Rutas Asignadas
                </td>
                <th data-orderable="false">{{ trans('backpack::crud.actions') }}</th>
            </tr>
            </thead>
            <tbody class="table-striped">
            @foreach( $field['value'] as $item)
                <tr class="array-row" id='ruta_{{$item->id}}'>

                    <td>
                        {{$item->name}}
                    </td>
                    <td>
                        <a data-id="{{$item->id}}"
                           class="btn btn-xs btn-default delete_ruta" data-button-type="delete">
                            <i class="fa fa-trash"></i> Eliminar Ruta
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>


<style>
    .panel-titles > a:before {
        font-family: FontAwesome;
        content: "\f068";
        padding-right: 5px;
        background-color: red;
        font-size: 16px;
        padding: 6px 9px 6px 9px;
        color: white;
    }

    .panel-titles > a.collapsed:before {

        content: "\f067";
        background-color: green;
        font-size: 16px;
        padding: 6px 9px 6px 9px;
        color: white;

    }

</style>


@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))
    @push('crud_fields_scripts')
        <script>
            $(document).ready(function () {
                $(".autobuseros_id").on('change', function () {
                    var categoria = $(this).val();
                    if (categoria === "") {
                        var producto = '<option value="">-Seleccione Ruta-</option>'
                        $("#productos").html(producto);
                    } else {
                        $.get('/rutas_autobusero/' + categoria, function (data) {
                            var producto_select = '<option value="">-Seleccione Ruta-</option>'
                            for (var i = 0; i < data.length; i++)
                                producto_select += '<option value="' + data[i].id + '">' + data[i].name + '</option>';

                            $("#productos").html(producto_select);

                        });
                    }
                }).trigger('change');
            });
        </script>
        <script>
            document.getElementById("colape").click();

            $(document).on('click', '#add_ruta_btn', function (event) {
                $('.loading').show();
                var autobusero_id_add = $("#autobuseros").val();
                var ruta_id_add = $("#productos").val();
                var bus_id_add = $("#bus").val();

                var url = '/ruta/' + ruta_id_add + '/autobusero/' + autobusero_id_add + '/bus/' + bus_id_add;

                var data = {autobusero_id_add: autobusero_id_add, ruta_id_add: ruta_id_add, bus_id_add: bus_id_add};


                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.is-invalid').removeClass('is-invalid');
                        if (data.fail) {
                            for (control in data.errors) {
                                $('input[name=' + control + ']').addClass('is-invalid');
                                $('#error-' + control).html(data.errors[control]);
                            }
                        } else {
                            $('#rutas').append(data);
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        swal({
                            title: "Ruta",
                            text: "Esta ruta ya pertenece a este bus",
                            icon: "error",
                            timer: 2000,
                            buttons: false,
                        });
                    }
                });
                return false;
            });

            $(document).on('click', '.delete_ruta', function (event) {
                var bus_id_add = $("#bus").val();
                var deleteId = $(this).data('id');
                var url = '/ruta/' + deleteId + '/delete';
                $.ajax({
                    url: url,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {_method: 'DELETE', zona_id_add: deleteId, bus_id_add: bus_id_add},
                    success: function (data) {
                        $("#ruta_" + deleteId).remove();
                    },
                    error: function (xhr, status, error) {
                        alert(xhr.responseText);
                    }
                });
            });

        </script>
    @endpush
@endif