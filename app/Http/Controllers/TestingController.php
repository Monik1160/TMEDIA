<?php

namespace App\Http\Controllers;

use App\Jobs\CampaignProcess;
use App\Models\BackpackUser;
use App\Models\Bus;
use App\Models\Campaing;
use App\Models\Installation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function index()
    {

        $hola = [
            'hola1' => [
                'hola' => [
                    'hola1' => 'dcd',
                    'hola' => 'holaxx'
                ],
            ],
            'hola2' => [
                'hola' => 'holaxx'
            ],
            'hola3' => [
                'hola' => 'holaxx'
            ]
        ];

        dd($hola['hola1'] ['hola']);


//        busesWithZona get data from buses and buses_zonas tables

//        $buses = Bus::busesWithZonas(2)->where('activo',true)->get();
//
//        dd($buses);

        //Caso #1 Fecha Excelentes
//        Installation::truncate();
//
//        $data_help = [
//            [
//                'id' => 1,
//                'campaign_detail_id' => 3,
//                'campaign_id' => 2,
//                'bus_id' => 1711,
//                'zona_id' => 1,
//                'start_date' => '2020-04-05',
//                'end_date' => '2020-06-30',
//                'installed_by' => 1,
//                'installed_at' => '2020-03-04',
//                'is_active' => 0
//            ],
//            [
//                'id' => 2,
//                'campaign_detail_id' => 3,
//                'campaign_id' => 2,
//                'bus_id' => 1711,
//                'zona_id' => 2,
//                'start_date' => '2020-04-05',
//                'end_date' => '2020-06-30',
//                'installed_by' => 1,
//                'installed_at' => '2020-03-04',
//                'is_active' => 0
//            ],
//            [
//                'id' => 3,
//                'campaign_detail_id' => 3,
//                'campaign_id' => 2,
//                'bus_id' => 1711,
//                'zona_id' => 3,
//                'start_date' => '2020-04-05',
//                'end_date' => '2020-06-30',
//                'installed_by' => 1,
//                'installed_at' => '2020-03-04',
//                'is_active' => 0
//            ],
//        ];
//
//        foreach ($data_help as $instalaciones) {
//            Installation::create($instalaciones);
//        }
//
//
//        $starf_date = Carbon::parse('2020-07-01T00:00:00.000Z');
//        $end_date = Carbon::parse('2020-08-31T06:00:00.000Z');
//
//        $zonas_publicitaria = [
//            [
//                'id' => 5,
//                'name' => 'Lateral grande peatonal',
//                'zonasbuses_id' => 2,
//            ],
//            [
//                'id' => 4,
//                'name' => 'Trasera baja',
//                'zonasbuses_id' => 3,
//            ],
////            [
////                'id' => 3,
////                'name' => 'Lateral grande chofer',
////                'zonasbuses_id' => 1,
////            ]
//        ];
//
//        foreach ($zonas_publicitaria as $zona) {
//            $data = Bus::busesWithZonas($zona['zonasbuses_id'], 3, 550)->get();
//        }
//
//        foreach ($data as $hola) {
//            $instalaciones_existen = Installation::where('bus_id', '=', $hola->buses_id)->where('end_date', '>', $starf_date)->get();
//        }
//////
//        dd($instalaciones_existen);
//
//        dd($data);


        //Caso #2

//        Installation::truncate();
//
//        $data_help = [
//            [
//                'id' => 1,
//                'campaign_detail_id' => 3,
//                'campaign_id' => 2,
//                'bus_id' => 1711,
//                'zona_id' => 1,
//                'start_date' => '2020-04-05',
//                'end_date' => '2020-06-30',
//                'installed_by' => 1,
//                'installed_at' => '2020-03-04',
//                'is_active' => 0
//            ],
//            [
//                'id' => 2,
//                'campaign_detail_id' => 3,
//                'campaign_id' => 2,
//                'bus_id' => 1711,
//                'zona_id' => 2,
//                'start_date' => '2020-04-05',
//                'end_date' => '2020-06-30',
//                'installed_by' => 1,
//                'installed_at' => '2020-03-04',
//                'is_active' => 0
//            ],
//            [
//                'id' => 3,
//                'campaign_detail_id' => 3,
//                'campaign_id' => 2,
//                'bus_id' => 1711,
//                'zona_id' => 3,
//                'start_date' => '2020-04-05',
//                'end_date' => '2020-06-30',
//                'installed_by' => 1,
//                'installed_at' => '2020-03-04',
//                'is_active' => 0
//            ],
//        ];
//
//        foreach ($data_help as $instalaciones) {
//            Installation::create($instalaciones);
//        }
//
//
//        $starf_date = Carbon::parse('2020-07-01T00:00:00.000Z');
//        $end_date = Carbon::parse('2020-08-31T06:00:00.000Z');
//
//        $zonas_publicitaria = [
//            [
//                'id' => 5,
//                'name' => 'Lateral grande peatonal',
//                'zonasbuses_id' => 2,
//            ],
//            [
//                'id' => 4,
//                'name' => 'Trasera baja',
//                'zonasbuses_id' => 3,
//            ],
//            [
//                'id' => 3,
//                'name' => 'Lateral grande chofer',
//                'zonasbuses_id' => 1,
//            ]
//        ];
//
//        foreach ($zonas_publicitaria as $zona) {
//            $data = Bus::busesWithZonas($zona['zonasbuses_id'], 3, 550)->get();
//        }
//
//        foreach ($data as $hola) {
//            $instalaciones_existen = Installation::where('bus_id', '=', $hola->buses_id)->where('end_date', '<', $starf_date)->get();
//        }
////
//        dd($instalaciones_existen);

//        dd($data);
    }

    public function test()
    {
        $campaign = Campaing::first();
        $asunto = '';
        $description = '';
        $details = $campaign->campaign_details()->get();

        switch ($campaign->status) {
            case 2:
                $asunto = $campaign->name . ' esta lista para agregar información Financiera';
                $description = 'La campaña esta lista para agregarle la información financiera';
                break;
            case 3:
                $asunto = $campaign->name . ' esta lista para asignación de Buses';
                $description = 'La campaña esta lista para proceder con la asignación de buses';
                break;
            case 4:
                $asunto = $campaign->name . ' esta lista para agregar agregar diseños';
                $description = 'La campaña esta lista para subir los diseños de los buses';
                break;
            case 5:
                $asunto = $campaign->name . ' esta lista para activar y creación de tarea';
                $description = 'La campaña esta lista para que las tareas sean creadas y activadas';
                break;
            case 6:
                $asunto = $campaign->name . ' en instalaciones';
                $description = 'La campaña esta en instalaciones';
                break;
        }

        return view('email.campaign_email', compact("campaign", 'asunto', 'details', 'description'));
    }

    public function testJob()
    {
        CampaignProcess::dispatch();
    }
}
