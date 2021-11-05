<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Campaing extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'campaings';
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

    public function cliente()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function arte()
    {
        return $this->hasMany(Arte::class);
    }

    public function ejecutivo()
    {
        return $this->belongsTo(Ejecutivo::class, 'ejecutivo_id');
    }

    public function campaign_details()
    {
        return $this->hasMany(CampaignDetails::class, 'campaign_id');
    }

    public function finance_campaign()
    {
        return $this->hasMany(FinanceCampaign::class, 'campaign_id');
    }

    public function task()
    {
        return $this->hasMany(Tarea::class, 'campaing_id');
    }

    public function design()
    {
        return $this->hasMany(CampaignDesigns::class,'campaign_id');
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
