@extends(backpack_view('layouts.top_left'))

@section('content')
    <div class="row">
        <div class="contact_create">
            <main class="pt-4">
                <section class="container-fluid">
                    <h2>
                        <span class="text-capitalize">Ejecutivo | Campaña</span>
                    </h2>
                </section>
                <div class="container-fluid animated fadeIn">
                    <div class="row">
                        <div id="app">
                            <input type="hidden" id="campaign_id" value="{{$campaign->id}}">
                            @if($campaign->status < 4)
                                @if($campaign->status > 1)
                                    <edit-campaign disable="true"></edit-campaign>
                                @else
                                    <edit-campaign></edit-campaign>
                                @endif
                            @else
                                <designs_approve></designs_approve>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
