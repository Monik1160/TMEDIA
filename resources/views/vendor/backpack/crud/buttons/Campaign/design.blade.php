<a id="design_campaign_button_{{$entry->id}}" onclick="design_process(this, {{$entry->id}})" class="campaign_button" href="#" data-toggle="tooltip" data-placement="top"
   title="Terminar Diseño">
    <i class="far fa-ruler-triangle"></i>
</a>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        var status_campaign = {{$entry->status}};
        var button_buss = $('#design_campaign_button_{{$entry->id}}');

        if (status_campaign < 4) {
            button_buss.addClass('button_disable');
            button_buss.addClass('button_disable_color');
        } else if (status_campaign > 4) {
            button_buss.addClass('button_disable');
            $('#design_campaign_button_{{$entry->id}}  i').addClass('ready_button');
        } else if (status_campaign === 4) {
            $('#design_campaign_button_{{$entry->id}}  i').addClass('progress_button');
        }

    });

    function design_process(input_button,id) {
        $("#design_campaign_button_{{$entry->id}}").addClass("disabled");

        var route_aprove = 'api/internal/campaign/request_campaign/' + id;

        $.ajax({
            url: route_aprove,
            data: {status:5},
            type: 'POST',
            success: function (result) {
                crud.table.ajax.reload();
                button_{{$entry->id}}.addClass('button_disable');
                $('[data-toggle="tooltip"]').tooltip({disable:true});
                new Noty({
                    title: "Diseño Listo",
                    text: "Diseños Listos para la asignación de Tareas",
                    type: "success"
                }).show();
            },
            error: function (result) {
                // Show an alert with the result
                new Noty({
                    title: "No se puedo enviar Solicitud",
                    text: result.responseJSON.error,
                    type: "warning"
                }).show();
            }
        });

    }
</script>
