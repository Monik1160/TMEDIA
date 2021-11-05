<?php

namespace App\Http\Controllers\Backpack;

use Backpack\PermissionManager\app\Http\Controllers\RoleCrudController as BaseRoleCrudController;

// VALIDATION

class RoleCrudController extends BaseRoleCrudController
//    extends BasePermissionCrudController
{
    public function setup()
    {

        $this->role_model = $role_model = config('backpack.permissionmanager.models.role');
        $this->permission_model = $permission_model = config('backpack.permissionmanager.models.permission');

        $this->crud->setModel($role_model);
        $this->crud->setEntityNameStrings(trans('backpack::permissionmanager.role'), trans('backpack::permissionmanager.roles'));
        $this->crud->setRoute(backpack_url('role'));


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
