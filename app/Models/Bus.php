<?php

namespace App\Models;

use App\Models\BusFotos;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bus extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'buses';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function routeCount($bus_id)
    {

        $bus_with_route = DB::table('ruta_bus')
            ->where('buses_id', $bus_id)
            ->count();

        return $bus_with_route;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function carroceria()
    {
        return $this->belongsTo(Carroceria::class);
    }

    public function autobusero()
    {
        return $this->belongsTo('App\Models\Autobusero');
    }


    public function zonaspublicitarias()
    {
        return $this->belongsToMany(ZonasBuses::class, 'buses_zonas', 'buses_id', 'zonas_buses_id');
    }

    public function rutas()
    {
        return $this->belongsToMany('App\Models\Ruta', 'ruta_bus', 'buses_id', 'rutas_id');
    }


    public function fotografias()
    {
        return $this->hasMany(BusFotos::class, 'bus_id', 'id');
    }

    public function designs()
    {
        return $this->hasMany(CampaignDesigns::class,'bus_id','id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeBusesWithZonas($query, $zonapublicitaria_id, $ruta_id, $autobusero_id)
    {
        return $query->whereHas('zonaspublicitarias', function ($q) use ($zonapublicitaria_id) {
            $q->where('zonas_buses_id', '=', $zonapublicitaria_id);
        })->where('autobusero_id', '=', $autobusero_id)
            ->where('activo', '=', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getFullPlateAttribute()
    {
        return $this->tipo_placa . ' ' . $this->placa;
    }

    public function getFullPlate()
    {
        return $this->tipo_placa . ' ' . $this->placa;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
