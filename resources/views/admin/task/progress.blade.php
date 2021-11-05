@extends(backpack_view('layouts.top_left'))

@section('content')
    <div class="row">
        <div class="contact_create col-md-12">
            <main class="pt-4">
                <section class="container-fluid">
                    <h2>
                        <span class="text-capitalize">Progreso de la Tarea</span>
                    </h2>
                </section>
                <div class="container-fluid animated fadeIn">
                    <div class="row col-md-12">
                        <div id="app" class="col-md-12">
                            <input type="hidden" id="campaign_id" value="{{$tarea->campaing->id}}">
                            <input type="hidden" id="tarea_id" value="{{$tarea->id}}">
                            <progress_task task_id="{{$tarea->id}}"></progress_task>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
