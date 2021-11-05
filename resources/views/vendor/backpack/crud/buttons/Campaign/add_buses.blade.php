<a id="add_bus_campaign_button_{{$entry->id}}" onclick="asign_buses(this, {{$entry->id}})" class="campaign_button"
   data-toggle="tooltip" data-placement="top"
   title="Buses">
    <i class="fal fa-bus-alt"></i>
</a>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        var status_campaign = {{$entry->status}};
        var button_buss = $('#add_bus_campaign_button_{{$entry->id}}');

        if (status_campaign < 3) {
            button_buss.addClass('button_disable');
            button_buss.addClass('button_disable_color');
        } else if (status_campaign > 3) {
            button_buss.addClass('button_disable');
            $('#add_bus_campaign_button_{{$entry->id}}  i').addClass('ready_button');
        } else if (status_campaign === 3) {
            $('#add_bus_campaign_button_{{$entry->id}}  i').addClass('progress_button');
        }

        @if(!backpack_auth()->user()->hasRole('logistica'))
        button_buss.addClass('button_disable');
        @endif

    });

    function asign_buses(input_button, id) {
        $("#add_bus_campaign_button_{{$entry->id}}").addClass("disabled");

        var route_aprove = 'api/internal/campaign/request_campaign/' + id;

        $.ajax({
            url: route_aprove,
            data: {status: 4},
            type: 'POST',
            success: function (result) {
                crud.table.ajax.reload();
                button_{{$entry->id}}.addClass('button_disable');
                $('[data-toggle="tooltip"]').tooltip({disable: true});
                new Noty({
                    title: "Buses Asignado",
                    text: "Solicitud de buses asignados",
                    type: "success"
                }).show();
            },
            error: function (result) {
                // Show an alert with the result
                console.log(result.responseJSON.error)
                if (result.status == 409) {
                    new Noty({
                        title: "No se puedo enviar Solicitud",
                        text:  result.responseJSON.error,
                        type: "warning"
                    }).show();
                } else {
                    new Noty({
                        title: "No se puedo enviar Solicitud",
                        text: "La solicitud no se pudo enviar. Contact support team",
                        type: "warning"
                    }).show();
                }
            }
        });

    }

</script>
