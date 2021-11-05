<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        if (!backpack_user()->hasRole("developer")){

            static::addGlobalScope('protectDeveloper', function (Builder $builder) {
                $builder->where('name', "!=" ,"developer-tools");
            });
        }

    }

}
