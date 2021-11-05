<a id="request_campaign_button_{{$entry->id}}" href="#"
   class="campaign_button" onclick="request_campaign(this, {{$entry->id}})"
   data-route="{{ route('request_campaign_post', ['id' => $entry->getKey()]) }}" data-toggle="tooltip"
   data-placement="top" title="Ejecutivo">
    <i class="far fa-check color_ready_{{$entry->id}}"></i>
</a>

<script>
    var button_{{$entry->id}} = $('#request_campaign_button_{{$entry->id}}');

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if ({{$entry->status}} != 1)
        {
            button_{{$entry->id}}.addClass('button_disable');
            $('.color_ready_{{$entry->id}}').addClass('ready_button');
        }else{
            $('.color_ready_{{$entry->id}}').addClass('color_black');
        }
    });

    function request_campaign(input_button,id) {
        $("#request_campaign_button_{{$entry->id}}").addClass("disabled");

        var route_aprove = 'api/internal/campaign/request_campaign/' + id;

        $.ajax({
            url: route_aprove,

            data: {status:2,
                "_token": "{{ csrf_token() }}",
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (result) {
                crud.table.ajax.reload();
                button_{{$entry->id}}.addClass('button_disable');
                $('[data-toggle="tooltip"]').tooltip({disable:true});
                new Noty({
                    title: "Solicitud de Campaña Enviada",
                    text: "Solicitud de Campaña Enviada",
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



