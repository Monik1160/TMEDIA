<?php
$zonas = \App\Models\ZonasBuses::all();
$bus_id = $crud->entry->id;
?>


<div @include('crud::inc.field_wrapper_attributes') class="panel-group" id="accordion">
    <h3 style="margin-top:0" class="panel-titles">{!! $field['label'] !!}

    </h3>

    <div id="colape" style="margin: 30px 0px">
        <div class="form-group">
            <label>Seleccione Zona Publicitaria:</label>
            <select name="contact_id_add" id="contact_id_add" style="width: 100%"
                    class="form-control select2_field">
                @foreach($zonas as $zona)
                    <option value="{{$zona->id}}">{{$zona->name}}</option>
                @endforeach
            </select>
            <input type="hidden" id="bus" value="{{ $bus_id  }}">

        </div>
        <div class="array-controls btn-group m-t-10">
            <a id="contact-modal">
                <button type="button" id="add_contact_btn" class="btn btn-sm btn-default">Agregar Zona Publicitarua
                </button>
            </a>
        </div>
    </div>
    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0" id="contacts">
            <thead>
            <tr>
                <td>
                    Zona Publicitaria
                </td>
                <td>
                    Campaña
                </td>
            </tr>
            </thead>
            <tbody class="table-striped">
            @foreach( $field['value'] as $item)
                <tr class="array-row" id='contact_relation_{{$item->id}}'>

                    <td>
                        {{$item->name}}
                    </td>
                    <td>
                        <?php
                        $hola = \DB::table('buses_zonas')->select('*')->where('buses_id', '=', $bus_id)->where('zonas_buses_id', '=', $item->id)->first();
                        $campaign = ($hola->campaing_id != null) ? \App\Models\Campaing::where('id', '=', $hola->campaing_id)->first() : '';
                        ?>
                        @if($campaign != '')
                            {{$campaign->name}} <br> {{$campaign->cliente->company_name}}
                        @else
                            -
                        @endif
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
            document.getElementById("colape").click();

            $(document).on('click', '#add_contact_btn', function (event) {
                var zona_id_add = $("#contact_id_add").val();
                var bus_id_add = $("#bus").val();
                var url = '/zona/' + bus_id_add;
                var data = {zona_id_add: zona_id_add, bus_id_add: bus_id_add};


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
                            $('#contacts').append(data);
                            document.getElementById("contact_id_add").selectedIndex = "-1";
                        }
                    },
                    error: function () {
                        swal({
                            title: "Zona Publicitaria",
                            text: "Esta zona ya pertenece a este bus",
                            icon: "error",
                            timer: 2000,
                            buttons: false,
                        });
                    }
                });
                return false;
            });

            $(document).on('click', '.delte', function (event) {
                var deleteId = $(this).data('id');
                var bus_id_add = $("#bus").val();
                var url_zona = '/zona/' + deleteId + '/delete';

                $.ajax({
                    url: url_zona,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {_method: 'DELETE', zona_id_add: deleteId, bus_id_add: bus_id_add},
                    success: function (data) {
                        $("#contact_relation_" + deleteId).remove();
                    },
                    error: function (xhr, status, error) {
                        alert(xhr.responseText);
                    }
                });

            });

        </script>
    @endpush
@endif
