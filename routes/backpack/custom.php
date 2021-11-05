<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('carroceria', 'CarroceriaCrudController');
    Route::crud('bus', 'Bus\BusCrudController');
    Route::any('bus/{bus_id}/save_image', 'Bus\BusCrudController@addImagesToBus');
    Route::put('bus/{bus_id}/delete', 'Bus\BusCrudController@deleteBusImages');
    Route::get('bus/{bus_id}/photo/{photo_id}/download', 'Bus\BusCrudController@download_photo')->name('ajax.download.bus_images');


    Route::crud('zona', 'ZonaCrudController');
    Route::any('zona/{zona_id}', 'ZonasBusesCrudController@addZonaToBus');
    Route::any('zona/{zona_id}/delete', 'ZonasBusesCrudController@removeZonaFromBus');
    Route::get('rutas_autobusero/{ruta_id}', 'RutaCrudController@getRutas');

    Route::post('ruta/{ruta_id}/autobusero/{autobusero}/bus/{bus_id}', 'RutaCrudController@addRutaToBus');
    Route::any('ruta/{ruta_id}/delete', 'RutaCrudController@removeRutaFromBus');

    Route::crud('contact', 'ContactCrudController');
    Route::crud('autobusero', 'AutobuseroCrudController');
    Route::crud('zonapublicitaria', 'ZonaPublicitariaCrudController');
    Route::crud('installer', 'InstallerCrudController');
    Route::crud('tag', 'TagCrudController');
    Route::crud('tipo-de-identificacion', 'IdentificationTypeCrudController');
    Route::crud('ruta', 'RutaCrudController');
    Route::crud('autobuseros_rutas', 'AutobuserosRutasCrudController');
    Route::crud('installationtype', 'InstallationTypeCrudController');

    //Campañas
    Route::crud('campañas', 'Campaign\CampaingCrudController');
    Route::get('campañas/{id}/add-financial-info', 'Campaign\CampaingCrudController@addFinancialInfo')->name('finance_info');
    Route::any('campanas/{campaing_id}/save-arts/', 'CampaingCrudController@saveArtsCampaings');
    Route::put('campanas/{arte_id}/delete', 'CampaingCrudController@deleteArt');
    Route::get('campanas/{id}/report', 'Campaign\CampaingCrudController@generateCampaignReport')->name('campaign.create.report');


    //CRUD by Process
    Route::get('campañas/{id}/{logistica}', 'Campaign\CampaingCrudController@status');
    Route::get('campañas/{id}/{diseno}', 'Campaign\CampaingCrudController@status');
    Route::get('campañas/{id}/{finanzas}', 'Campaign\CampaingCrudController@status');
    Route::get('campañas/{id}/{ejecutivo}', 'Campaign\CampaingCrudController@status');

    //end campaings

    //CRUD Task
    Route::crud('tarea', 'TareaCrudController');
    Route::post('tarea/instalador', 'TareaCrudController@addInstallerToTarea')->name('addInstaller');
    Route::get('tarea/{id}/progress', 'TareaCrudController@progressTask');
    Route::get('tarea/change-bus/{id}/{status}', 'TareaCrudController@changeBus');


    Route::crud('clientes', 'ClientCrudController');
    Route::crud('materiales', 'MaterialCrudController');
    Route::crud('notificaciones', 'NotificationsCrudController');
    Route::crud('ejecutivo', 'EjecutivoCrudController');
    Route::crud('tipos-tareas', 'TareatypeCrudController');
    Route::crud('zonasbuses', 'ZonasBusesCrudController');

    //Rutas Autobuses y Autobuseros
    Route::crud('busesrutas', 'BusesRutasCrudController');


    // if not otherwise configured, setup the dashboard routes
    if (config('backpack.base.setup_dashboard_routes')) {
        Route::get('dashboard', 'AdminController@dashboard')->name('backpack.dashboard');
        Route::get('/', 'AdminController@redirect')->name('backpack');
    }


    Route::get('testing-buses-etl', 'TestingController@busesZonas');


}); // this should be the absolute last line of this file


Route::group([
    'prefix' => 'api/internal',
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace' => 'App\Http\Controllers',
], function () { // custom admin routes
    Route::get('campana', 'Admin\CampaingCrudController@campana_list');
    Route::get('arte', 'Admin\CampaingCrudController@arte_list');

    Route::get('bus', 'Admin\TareaCrudController@busesWithZonas');
    Route::get('zonas', 'Admin\TareaCrudController@zonas_list');

    // Campaigns
    Route::get('campaign/clients', 'Api\internals\CampaignController@getClients');
    Route::get('campaign/bus', 'Api\internals\CampaignController@getBus');
    Route::get('campaign/bus-informacion', 'Api\internals\CampaignController@getBusInformation');

    

    Route::post('campañas/{id}/active', 'Api\internals\CampaignController@change_status');

    Route::post('campaign/designs', 'Api\internals\CampaignController@addDesignsToBusCampaign');
    Route::any('campaign/remove-designs', 'Api\internals\CampaignController@removeDesignsToBusCampaign');
    Route::post('campaign/designs/status', 'Api\internals\CampaignController@changeDesignStatus');

    Route::get('campaign/autobuseros', 'Api\internals\CampaignController@getAutobuseros');
    Route::post('campaign/bus/add', 'Api\internals\CampaignController@addBusToCampaign');
    Route::get('campaign/zonas-publicitarias', 'Api\internals\CampaignController@getZonasPublicitarias');
    Route::get('campaign/zonas', 'Api\internals\CampaignController@getZonas');
    Route::get('campaign/rutas', 'Api\internals\CampaignController@getRutas');
    Route::post('campaign/remove_details', 'Api\internals\CampaignController@removeDetailsCampaign');
    Route::post('campaign/remove_finance', 'Api\internals\CampaignController@removeFinanceCampaign');
    Route::post('campaign/create', 'Admin\Campaign\CampaingCrudController@store');
    Route::post('campaign/update', 'Admin\Campaign\CampaingCrudController@update');

    Route::post('campaign/request_campaign/{id}', 'Api\internals\CampaignController@change_status')->name('request_campaign_post');
    Route::post('campaign/{id}/task/create', 'Api\internals\CampaignController@createCampaignTasks')->name('create_task_campaign');

    Route::get('campaigns', 'Api\internals\CampaignController@getCampaigns');

    Route::get('campaigns/details', 'Api\internals\CampaignController@getCampaignsDetails');

    //Rutas Autobuseros
    Route::get('campaign/autobuseros_all', 'Api\internals\CampaignController@getAutobuserosAll');
    Route::get('campaign/buses_autobuseros/{id}', 'Api\internals\CampaignController@getBusesAutobuseros');
    Route::get('campaign/ruta_all/{id}', 'Api\internals\CampaignController@getRoutes');
    Route::post('campaign/save_rutas_autobuseros', 'Api\internals\CampaignController@saveAutobuserosRutas');

    //Rutas Buses
    Route::post('campaign/save_rutas_buses', 'Api\internals\CampaignController@saveBusesRutas');

    //Task
    Route::post('task/details', 'Api\internals\TaskController@getTaskDetails');
    Route::post('task/decline-aprroved', 'Api\internals\TaskController@declineOrAcceptTask');
    Route::post('task/approved-photos-to-reports', 'Api\internals\TaskController@approvedPhotoToReports');

}); // this should be the absolute last line of this file
