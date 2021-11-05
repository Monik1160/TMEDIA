@extends(backpack_view('layouts.top_left'))

@section('content')
    <div class="row">
        <div class="contact_create">
            <main class="pt-4">
                <section class="container-fluid">
                    <h2>
                        <span class="text-capitalize">Logistica de la Campaña</span>
                    </h2>
                </section>
                <div class="container-fluid animated fadeIn">
                    <div class="row">
                        <div id="app">
                            <input type="hidden" id="campaign_id"
                                   value="{{$campaign->id}}">
                                <asign-buses></asign-buses>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
