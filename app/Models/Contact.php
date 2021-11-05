<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Edgcarmu\Crgeodata\app\Models\Barrio;
use Edgcarmu\Crgeodata\app\Models\Canton;
use Edgcarmu\Crgeodata\app\Models\Distrito;
use Edgcarmu\Crgeodata\app\Models\Provincia;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contacts';
     protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public static function boot()
    {
        parent::boot();

        self::updated(function ($model) {
            $user = $model->user;

            if (isset($user)) {
                $model->user->email = $model->email;
                $model->user->first_name = $model->first_name;
                $model->user->last_name = $model->last_name;
                $model->user->save();
            }
        });
    }

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
    public function ruta()
    {
        return $this->belongsToMany('App\Models\Ruta', 'ruta_contacto', 'contact_id', 'rutas_id');
    }

    public function identification_type()
    {
        return $this->belongsTo(IdentificationType::class, 'identification_type_id', 'id');
    }

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

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'contact_tag', 'contact_id');
    }

    public function user()
    {
        return $this->morphOne('App\Models\BackpackUser', 'userable');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeGetContact($query, $id)
    {
        return $query->where('user_id', '=', $id)->first();
    }

    /**
     *
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAgency($query)
    {
        return $query->where('is_agency', '=', 1);
    }

    /**
     *
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeClient($query)
    {
        return $query->where('is_client', '=', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    /**
     * Get the user's show name.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        if ($this->contact_type == 'individual' && $this->parent_id) {
            return "{$this->parent->company_name} , {$this->first_name} {$this->last_name}";
        } elseif ($this->contact_type == 'individual') {
            return "{$this->first_name} {$this->last_name}";
        }

        if ($this->contact_type == 'company') {
            return $this->company_name;
        }
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setIsAgencyAttribute($value)
    {
        if ($this->attributes['is_provider'] == 0) {
            $this->attributes['is_agency'] = 0;
        } else {
            $this->attributes['is_agency'] = $value;
        }
    }

    public function setProviderTypeAttribute($value)
    {
        if ($this->attributes['is_provider'] == 0) {
            $this->attributes['provider_type'] = null;
        } else {
            $this->attributes['provider_type'] = $value;
        }
    }

}
