<!DOCTYPE html>
<html>
    <head>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <title>REPORTE FOTOGRÁFICO</title>
        <style>

            @page {
                margin: 0;
            }

            @font-face {
                font-family: 'Montserrat-Regular';
                src: url({{ storage_path('fonts\Montserrat-Regular.otf') }}) format("truetype");
                font-weight: 400; // use the matching font-weight here ( 100, 200, 300, 400, etc).
                font-style: normal; // use the matching font-style here
            }

            body {
                font-family: "Montserrat-Regular";
            }

            .page-break {
                page-break-after: always;
            }

            .title{
                font-size: 48px;
            }

            .first-page-content > .title{
                color : #636569;
            }

            .first-page-content > p{
                font-size: 24px;
                color : #6BB74C;
                margin : 1px;
            }

            .first-page {

                background: url("{{ asset('images/Publimedia1.png') }}");
                /* background: url("{{ public_path('images/Publimedia1.png') }}"); */
                /* background: url("{{ public_path('images/Publimedia1.png') }}"); */
                /* background: {{ public_path('images/Publimedia7.png') }}; */
                /* background-image: url("/images/Publimedia1.png"); */
                background-repeat: no-repeat;
                background-size:cover;
            
                    padding-top: 45%;
                    padding-left : 40px;
            }

            .second-page {
                background-color : #6BB74C;
                padding-top: 45%;
                padding-left : 40px;
                font-size: 24px;
                color : white;
            }

            .second-page > p {
                font-size: 24px;
            }

            .rer {
                background-color : blue;
            }

            .third-page {
                
            }

            .third-page-content > span {
                color :#2D2D2D;
                font-size: 20px;

            }

            .third-page-content > .description {
                color : #2D2D2D;
                font-size: 48px;

            }
            

            .bottomright {
                position: absolute;
                bottom: 8px;
                right: 16px;
            }


            .sixth-page{
                padding-top : 35%;
            }


            .seventh-page-content {

                background-image : url("/images/Publimedia7.png");
                background-repeat : no-repeat;
                background-size : cover;
            }

            .quarter-page {
                background-color : grey;
                padding-top: 45%;
                padding-left : 40px;
                font-size: 24px;
                color : white;
            }

            /* Three image containers (use 25% for four, and 50% for two, etc) */
            .column {
                float: left;
                width: 50%;
                padding: 5px;
            }

            .row::after {
                content: "";
                clear: both;
                display: table;
            }


        </style>
    </head>
    <body >

        <!-- PRIMERA PAGINA-->
        <div class="container-fluid first-page">
            <div class="row h-100">
                <div class="col-sm first-page-content">
                    <h1 class="title"> REPORTE FOTOGRÁFICO</h1>
                    <p> Nombre de la Campaña: {{ $title }} </p>
                    <p> Cliente: {{ $client_name }} </p>
                </div>      
            </div>
        </div>
        


        <div class="page-break"></div>
        <!-- SEGUNDA PAGINA-->


        <div class="container-fluid second-page">
            
            <div class="row h-100">
                <div class="col-sm second-page-content">
                    <h1 class="title"> DESGLOCE DE SERVICIO </h1>

                    <p> {{ $installation_tasks_count }} Instalaciones </p>
                    <p class="bottomright">INSTALACIONES</p>

                </div>   
            </div>
        </div>
        

        <div class="page-break"></div>
        <!-- TERCERA PAGINA-->


        <div class="container-fluid third-page h-100">
        @foreach($installation_tasks as $task)
            <div class="row">
                <div class="col-sm third-page-content">
                    
                    <p class="description"> {{ $task->section_name ? $task->section_name : "N/A" }}</p>

                    <span class="bus-plate"> Número de placa: {{ $task->bus->getFullPlate() }} </span>
                    <br>

                    <span class="rute-name"> Ruta: {{ $task->ruta ? $task->ruta->name : "N/A" }} </span>
                </div>
            </div>

            
            <div class="row">
                    @foreach($task->tareaFotos as $image)

                    <div class="column">
                        <img src="{{ $image_s3_path }}/{{$image->image}}" alt="" style="width:100%">
                    </div>
                    @endforeach
            </div>
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif

            @endforeach
        </div>


        <div class="page-break"></div>
        <!-- CUARTA PAGINA-->


        <div class="container-fluid quarter-page">
            
            <div class="row h-100">
                <div class="col-sm quarter-page-content">
                    <h1 class="title"> DESGLOCE DE SERVICIO </h1>

                    <p> {{ $desinstallation_tasks_count }} Desintalaciones </p>
                    <p class="bottomright">DESINSTALACIONES</p>

                </div>   
            </div>
        </div>

        @if($desinstallation_tasks_count > 0)
        <div class="page-break"></div>
        <!-- QUINTA PAGINA-->


        <div class="container-fluid third-page h-100">
        @foreach($desinstallation_tasks as $task)
            <div class="row">
                <div class="col-sm third-page-content">
                    
                    <p class="description"> {{ $task->section_name ? $task->section_name : "N/A" }} </p>

                    <span class="bus-plate"> Número de placa: {{ $task->bus->getFullPlate() }} </span>
                    <br>
                    
                    <span class="rute-name"> Ruta: {{ $task->ruta ? $task->ruta->name : "N/A" }} </span>
                </div>
            </div>

            
            <div class="row">
                    @foreach($task->tareaFotos as $image)

                    <div class="column">
                        <img src="{{ $image_s3_path }}/{{$image->image}}" alt="" style="width:100%">
                    </div>
                    @endforeach
            </div>
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif

            @endforeach
        </div>
        @endif


        <div class="page-break"></div>
        <!-- SEXTA PAGINA-->


        <div class="container-fluid sixth-page" >
            <div class="row h-100">
                <div class="col-sm-12 h-100 text-center">
                    <span class="title"> ¡Somos expertos en colocar tus campañas a tiempo y en el lugar correcto! </span>
                </div>
            </div>
        </div>


        <div class="page-break"></div>
        <!-- SEPTIMA PAGINA-->


        <div class="container-fluid seventh-page">
            <div class="row h-100">
                <div class="seventh-page-content">
                </div>
            </div>
        </div>

        
    </body>
</html>