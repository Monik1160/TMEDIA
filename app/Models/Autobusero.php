<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Autobusero extends Contact
{
    /*
   |--------------------------------------------------------------------------
   | GLOBAL VARIABLES
   |--------------------------------------------------------------------------
   */

    protected $table = 'contacts';
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
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('Autobusero', function (Builder $builder) {
            $builder->where('is_provider', '=', 1);
            $builder->where('provider_type', '=', 'autobusero');
        });

    }

    public function ruta()
    {
        return $this->belongsToMany('App\Models\Ruta', 'rutas', 'contact_id', 'rutas_id');
    }

    public function buses()
    {
        return $this->hasMany('App\Models\Bus');
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */


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
    public function getDisplayNameAttribute()
    {
        if ($this->first_name != '') {
            return "{$this->first_name} {$this->last_name}";
        } else {
            return $this->company_name;
        }
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
