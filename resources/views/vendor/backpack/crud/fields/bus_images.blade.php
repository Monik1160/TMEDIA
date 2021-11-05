<?php
$bus_id = $crud->entry->id;
$images_buses = App\Models\BusFotos::all()->where('bus_id',$bus_id);
?>


<div @include('crud::inc.field_wrapper_attributes') class="panel-group" id="accordion">
    <h3 style="margin-top:0" class="panel-titles">{!! $field['label'] !!}</h3>
    <div id="colape" style="margin: 30px 0px">
        <div class="form-group">
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>
            <label for="" class="control-label">Adjunte fotografía de Bus</label>
            <div>
                <img id="previewHolder" alt="" width="250px" height="250px"/>
                <input type="file" name="filePhoto" value="" id="filePhoto" class="required borrowerImageFile"
                       data-errormsg="PhotoUploadErrorMsg" accept="image/x-png,image/gif,image/jpeg">
            </div>
            <input type="hidden" id="bus" value="{{$bus_id}}">
        </div>
        <div class="form-group">
            <div>
                <div>
                    <label for="" class="control-label">Descripción:</label>
                </div>
                <div>
                    <textarea class="form-control" id="description" rows="4" cols="50"></textarea>
                </div>
            </div>
        </div>
        <div class="array-controls btn-group m-t-10">
            <a id="contact-modal">
                <button type="button" class="btn btn-primary btn-lg " id="add_bus_btn"
                        data-loading="<i class='icon-fa-spinner icon-fa-spin'></i>  Procesando foto...">Agregar Fotografía
                </button>
            </a>
        </div>
    </div>
    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0" id="artes">
            <thead>
            <tr>
                <td>
                    Fotografía
                </td>
                <td>
                    Descripción
                </td>
                <th data-orderable="false">{{ trans('backpack::crud.actions') }}</th>
            </tr>
            </thead>
            <tbody class="table-striped">
            @foreach($images_buses as $images)
                <tr class="array-row" id='bus_{{$images->id}}'>
                    <td>
                        <img src="{{Config::get('filesystems.disks.s3.url')}}/{{$images->image}}"
                             style="width: 200px;height: 200px;">
                    </td>
                    <td>
                        {{$images->description}}
                    </td>
                    <td>
                        <a data-id="{{$images->id}}"
                           class="btn btn-xs btn-default delete_foto_bus" data-button-type="delete">
                            <i class="fa fa-trash"></i> Eliminar Fotografía
                        </a>
                        <a href="{{route('ajax.download.bus_images',['bus_id' => $bus_id,'photo_id'=> $images->id ])}}" target="" class="btn btn-xs btn-default download_a"><i class="far fa-download"></i> Download Image</a>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="{{ asset('packages/cropperjs/dist/cropper.min.js') }}"></script>
        <script src="{{ asset('packages/jquery-cropper/dist/jquery-cropper.min.js') }}"></script>
        <script>
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#previewHolder').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#filePhoto").change(function () {
                readURL(this);
            });
        </script>

        <script>
            document.getElementById("colape").click();
            var bus_id_add = $("#bus").val();
            $(document).on('click', '#add_bus_btn', function (event) {
                $("#add_bus_btn").html($("#add_bus_btn").data('loading')).attr("disabled", "disabled");

                var description_add = $("#description").val();
                var image = $('input[type=file]')[0].files[0];
                var formdata = new FormData();
                formdata.append('description', description_add);
                if (image === undefined) {
                    formdata.append('image', '');
                } else {
                    formdata.append('image', $('input[type=file]')[0].files[0]);
                }
                var url = '/bus/' + bus_id_add + '/save_image';


                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formdata,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        console.log(data)
                        $("#previewHolder").attr('src', '');
                        $("#add_bus_btn").html('Agregar Arte').removeAttr("disabled", "disabled");
                        $('#description').val('');
                        $('input[type=file]').val('');
                        $('#filePhoto').attr('src', '');
                        $('#artes').append('{{Config::get('filesystems.disks.s3.url')}}'+data);
                    },
                    error: function (data) {
                        $("#previewHolder").attr('src', '');
                        $("#add_bus_btn").html('Agregar Arte').removeAttr("disabled", "disabled");
                        $('input[type=file]').val('');
                        $('#filePhoto').attr('src', '');
                        var arte = JSON.parse(data.responseText);
                        printErrorMsg(arte.error);
                    }
                });

            });

            function printErrorMsg(msg) {
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display', 'block');
                $.each(msg, function (key, value) {
                    $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
                });
            }


            $(document).on('click', '.delete_foto_bus', function (event) {
                var deleteId = $(this).data('id');
                console.log(deleteId);
                var url_foto = '/bus/' + deleteId + '/delete';

                $.ajax({
                    url: url_foto,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {_method: 'DELETE', arte_id_add: deleteId},
                    success: function (data) {
                        $("#bus_" + deleteId).remove();
                    },
                    error: function (xhr, status, error) {
                        alert(xhr.responseText);
                    }
                });

            });
        </script>
    @endpush
@endif
