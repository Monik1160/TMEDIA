<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class CampaignDetails extends Model
{
    use CrudTrait;
    /*
   |--------------------------------------------------------------------------
   | GLOBAL VARIABLES
   |--------------------------------------------------------------------------
   */

    protected $table = 'campaign_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['campaign_id', 'zona_id', 'ruta_id', 'zona_publicitaria', 'notes', 'arte_id','bus_id'];
    protected $casts = [
        'zona_publicitaria' => 'array',
    ];
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
    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    public function zona_publicitaria()
    {
        return $this->belongsTo(ZonaPublicitaria::class);
    }

    public function arte()
    {
        return $this->belongsTo('App\Models\Arte');
    }

    public function bus()
    {
        return $this->hasOne(Bus::class,'id','bus_id');
    }
    public function campaign()
    {
        return $this->hasOne(Campaing::class, 'id','campaign_id');
    }
    public function task()
    {
        return $this->hasOne(Tarea::class,'campaing_id');
    }
    public function design(){
        return $this->hasMany(CampaignDesigns::class,'detail_id');
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
