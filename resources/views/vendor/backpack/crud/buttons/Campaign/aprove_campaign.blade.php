<a id="aprove_campaign_button_{{$entry->id}}" onclick="change_status(this,{{$entry->id}})" class="campaign_button"
   data-toggle="tooltip"
   data-placement="top" title="Finanzas">
    <i class="fas fa-dollar-sign color_ready_{{$entry->id}}"></i>
</a>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        var status_campaign = {{$entry->status}};
        var button_aprove = $('#aprove_campaign_button_{{$entry->id}}');

        if (status_campaign < 2) {
            button_aprove.addClass('button_disable');
            button_aprove.addClass('button_disable_color');
        } else if (status_campaign > 2) {
            button_aprove.addClass('button_disable');
            $('#aprove_campaign_button_{{$entry->id}}  i').addClass('ready_button');
        } else if (status_campaign === 2) {
            $('#aprove_campaign_button_{{$entry->id}}  i').addClass('progress_button');
        }
        @if(!backpack_auth()->user()->hasRole('financiero'))
        button_aprove.addClass('button_disable');
        @endif
    });


    function change_status(input_button, id) {

        $("#request_campaign_button_{{$entry->id}}").addClass("disabled");

        var route = 'api/internal/campaign/request_campaign/' + id;

        $.ajax({
            url: route,
            data: {status: 3},
            type: 'POST',
            success: function (result) {
                crud.table.ajax.reload();
                button_{{$entry->id}}.addClass('button_disable');
                $('[data-toggle="tooltip"]').tooltip({disable:true});
                new Noty({
                    title: "Finanzas",
                    text: "Información Financiera Guardad, Listo para asignar Buses",
                    type: "success"
                }).show();
            },
            error: function (result) {
                // Show an alert with the result
                new Noty({
                    title: "No se puedo enviar Solicitud",
                    text: "No se puede aprobar solicitud, Debes agregar la información Financiera",
                    type: "warning"
                }).show();
            }
        });

    }

</script>

