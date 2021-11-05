<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Edgcarmu\Crgeodata\app\Models\Barrio;
use Edgcarmu\Crgeodata\app\Models\Canton;
use Edgcarmu\Crgeodata\app\Models\Distrito;
use Edgcarmu\Crgeodata\app\Models\Provincia;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'zonas';
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'provincia_id');
    }

    function canton()
    {
        return $this->belongsTo(Canton::class, 'canton_id', 'canton_id');
    }

    function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'distrito_id');
    }

    function barrio()
    {
        return $this->belongsTo(Barrio::class, 'barrio_id', 'barrio_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getNameAttribute()
    {
        return "{$this->provincia->name}";
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
