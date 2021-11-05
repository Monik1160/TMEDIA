<?php

namespace App\Http\Controllers\Backpack\PermissionManager;

use Backpack\PermissionManager\app\Http\Controllers\PermissionCrudController as BasePermissionCrudController;

// VALIDATION

class PermissionCrudController extends BasePermissionCrudController
{


    public function setup()
    {
        $this->role_model = $role_model = config('backpack.permissionmanager.models.role');
        $this->permission_model = $permission_model = config('backpack.permissionmanager.models.permission');

        $this->crud->setModel($permission_model);
        $this->crud->setEntityNameStrings(trans('backpack::permissionmanager.permission_singular'), trans('backpack::permissionmanager.permission_plural'));
        $this->crud->setRoute(backpack_url('permission'));

        // Allow access if its developer
        if ( backpack_user()->hasRole('developer') ) {

            $this->crud->allowAccess('create');
            $this->crud->allowAccess('update');
            $this->crud->allowAccess('delete');

        }else {

            $this->crud->denyAccess('create');
            $this->crud->denyAccess('update');
            $this->crud->denyAccess('delete');

        }
    }

}
