@extends(backpack_view('layouts.top_left'))

@section('content')
    <div class="row">
        <div class="contact_create">
            <main class="pt-4">
                <section class="container-fluid">
                    <h2>
                        <span class="text-capitalize">Crear Solicitud de Campaña</span>
                    </h2>
                </section>
                <div class="container-fluid animated fadeIn">
                    <div class="row">
                        <div id="app">
                            <create-campaign-requets class="tab-pane fade show active" id="home" role="tabpanel"
                                                     aria-labelledby="home-tab"></create-campaign-requets>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

@endsection
