<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Tarea extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'tareas';
//     protected $primaryKey = 'id';
//    public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['monto','montaje', 'notes', 'cancel_message', 'tarea_type_id', 'campaing_id','campaing_detail_id', 'arte_id', 'zonapublicitaria_id', 'installer_id', 'bus_id', 'status', 'ruta_id' , 'desinstallion_data'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'desinstallion_data' => 'object',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function campaing()
    {
        return $this->belongsTo('App\Models\Campaing');
    }

    public function arte()
    {
        return $this->belongsTo('App\Models\Arte');
    }

    public function zonapublicitaria()
    {
        return $this->belongsTo(ZonaPublicitaria::class , "zona_publicitaria_id");
    }

    public function installer()
    {
        return $this->belongsTo(Installer::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function tareaFotos()
    {
        return $this->hasMany(TareaFotos::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class,'task_id');
    }

    public function tareatype()
    {
        return $this->belongsTo(Tareatype::class, 'tarea_type_id');
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeGetTareasInstaller($query, $id)
    {
        return $query->where('installer_id', '=', $id)->whereIn('status', [2,3])->orWhere('status', '=',5)->where('approved','=',0)->get();
    }

    public function scopeGetActiveTasksInstaller($query, $id)
    {
        return $query->where('installer_id', '=', $id)->where('status', '=', 4)->first();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
