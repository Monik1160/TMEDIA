<a id="task_campaign_{{$entry->id}}" href="#" onclick="createTaskCampaign()" data-toggle="tooltip" data-placement="top" data-id="{{$entry->id}}" title="Crear Tareas de la Campaña">
    <i class="far fa-tasks" style="padding-right: 7px; color: black;"></i>
</a>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        var status_campaign = {{$entry->status}};
        var button_campaign = $('#task_campaign_{{$entry->id}}');

        console.log(status_campaign)

        if (status_campaign < 5) {
            button_campaign.addClass('button_disable');
            button_campaign.addClass('button_disable_color');
        } else if (status_campaign > 5) {
            button_campaign.addClass('button_disable');
            $('#task_campaign_{{$entry->id}}  i').addClass('ready_button');
        } else if (status_campaign === 5) {
            $('#task_campaign_{{$entry->id}}  i').addClass('progress_button');
        }

    });

    function createTaskCampaign() {
        $("#task_campaign_{{$entry->id}}").addClass("disabled");

        let task = 'api/internal/campaign/createTaskCampaign';
        var button_campaign = $('#task_campaign_{{$entry->id}}');

        $.ajax({
            url: task,
            data: {id: button_campaign.data('id')},
            type: 'POST',
            success: function (result) {
                crud.table.ajax.reload();
                $('[data-toggle="tooltip"]').tooltip({disable:true});
                new Noty({
                    title: "Tareas Creadas con Exito",
                    text: "La tareas se han creado correctamente",
                    type: "success"
                }).show();
            },
            error: function (result) {
                // Show an alert with the result
                new Noty({
                    title: "No se puedo enviar Solicitud",
                    text: "La solicitud no se pudo enviar. Contact support team",
                    type: "warning"
                }).show();
            }
        });

    }
</script>
