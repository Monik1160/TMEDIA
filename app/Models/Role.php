<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
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
                $builder->where('name', "!=" ,"developer");
            });
        }

    }

}
