@extends(backpack_view('layouts.top_left'))

@section('content')
    <div class="row">
        <div class="contact_create">
            <main class="pt-4">
                <section class="container-fluid">
                    <h2>
                        <span class="text-capitalize">Editar Solicitud de Campaña</span>
                    </h2>
                </section>
                <div class="container-fluid animated fadeIn">
                    <div class="row">
                        <div id="app">
                            @if(backpack_user()->hasRole('ejecutivo'))
                                <input type="hidden" id="campaign_id" value="{{$entry->id}}">
                                @if($entry->status == 5)
                                    <designs_approve></designs_approve>
                                @elseif($entry->status <= 4)
                                    <edit-campaign></edit-campaign>
                                @endif
                            @elseif(backpack_user()->hasRole('financiero') || $entry->status == 2)
                                <input type="hidden" id="campaign_id"
                                       value="{{$entry->id ? $entry->id: $campaign_id }}">
                                <finance-campaign></finance-campaign>
                            @elseif(backpack_user()->hasRole('logistica') || $entry->status == 3)
                                <input type="hidden" id="campaign_id"
                                       value="{{$entry->id ? $entry->id: $campaign_id }}">
                                <asign-buses></asign-buses>
                            @elseif(backpack_user()->hasRole('design') || $entry->status == 4)
                                <input type="hidden" id="campaign_id"
                                       value="{{$entry->id ? $entry->id: $campaign_id }}">
                                <design-buses></design-buses>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
