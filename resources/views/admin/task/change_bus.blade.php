@extends(backpack_view('layouts.top_left'))

@section('content')
    <div class="row">
        <div class="contact_create">
            <main class="pt-4">
                <section class="container-fluid">
                    <h2>
                        <span class="text-capitalize">Cambio de Bus</span>
                    </h2>
                </section>
                <div class="container-fluid animated fadeIn">
                    <div class="row">
                        <div id="app">
                            <input type="hidden" id="campaign_id" value="{{$tarea->campaing->id}}">
                            <input type="hidden" id="detail_id" value="{{$tarea->campaing_detail_id}}">
                            <change_bus task_id="{{$tarea->id}}"></change_bus>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
