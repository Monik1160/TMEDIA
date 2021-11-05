<?php
$installers = \App\Models\Installer::all();
$optionSelected = \App\Models\Tarea::find($entry->getKey());

if ($entry->status == 3 || $entry->status == 5) {
    return '';
}
?>
<a href="javascript:void(0)" class="btn btn-sm btn-link"
   data-id="{{$entry->id}}"
   data-button-type="installer" data-toggle="modal"
   data-target="#modalTarea{{$entry->id}}"><i
            class="far fa-thumbtack"
            id="assignInstaller"></i> {{$entry->status == 1 ? 'Asignar Instalador': 'Cambiar Instalador' }}</a>

<div class="modal fade modalTarea" id="modalTarea{{$entry->id}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Campaña {{$entry->campaing->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <label>Seleccione el instalador para la tarea:</label>
                    @include('crud::inc.field_translatable_icon')

                    <input name="idTarea" type="hidden" id="tarea" value="{{$entry->getKey()}}">
                    <select id="installer_select" name="installer" style="width: 100%"
                            data-init-function="bpFieldInitSelect2Element"
                            @include('crud::inc.field_attributes', ['default_class' =>  'form-control select2_field'])>
                        @foreach($installers as $installer)
                            <option value="{{ $installer->getKey() }}">{{ $installer->first_name .' '. $installer->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input id="add_installer" class="holza btn btn-sm btn-link" type="submit"/>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#modalTarea<?php echo e($entry->id); ?>').appendTo(document.body);
    $("#modalTarea<?php echo e($entry->id); ?> form").submit(function (event) {
        event.preventDefault(); //prevent default action
        var form_data = $(this).serialize(); //Encode form elements for submission
        var route = '/tarea/instalador';
        $.ajax({
            url: route,
            type: 'POST',
            data: form_data,
            success: function (result) {
                // Show an alert with the result
                new Noty({
                    type: "success",
                    text: "<strong>Tarea Asignada con Exito</strong><br>La tarea has sido asignado a su instalador con exito."
                }).show();

                // Hide the modal, if any
                $('.modal').modal('hide');

                if (typeof crud !== 'undefined') {
                    crud.table.ajax.reload();
                }
            },
            error: function (result) {
                // Show an alert with the result
                new Noty({
                    type: "warning",
                    text: "<strong>Error</strong><br>No se ha asignado este instalador a esta tarea"
                }).show();
            }
        });

    });

</script>

