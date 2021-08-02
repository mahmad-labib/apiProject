<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Models\Permission;

class RolesController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $roles = Role::all();
            foreach ($roles as $role) {
                $role->permissions;
            }
            return $this->returnData('roles', $roles);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $perm = $request->permissions;
            $role = new Role;
            $roleCheck = Role::where('name', $request->name)->first();
            if (!empty($roleCheck)) {
                return $this->returnError(errNum: '409', msg: 'role already exists');
            }
            $role->name = $request->name;
            $role->save();
            $role->permissions()->sync($perm);
            return $this->returnSuccessMessage(msg: 'role saved succesfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $role = Role::where('id', $id)->first();
            if (!$role) {
                return $this->returnError('404', 'role not found');
            }
            return $this->returnData('role', $role);
        } catch (\Throwable $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $name = $request->name;
            $perm = $request->permissions;
            $deletedPerm = $request->deletedPerm;
            $role = Role::where('id', $id)->first();
            if (!$role) {
                return $this->returnError('404', 'role dont exist');
            }
            $role->update(['name' => $name]);
            if (!empty($deletedPerm)) {
                $role->permissions()->detach($deletedPerm);
            }
            if (!empty($perm)) {
                $role->permissions()->attach($perm);
            }
            return $this->returnSuccessMessage(msg: 'role updated');
        } catch (\Throwable $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $role =  Role::where('id', $id)->first();
            if (!$role) {
                return $this->returnError(errNum: '404', msg: 'role dont exist');
            }
            $role->permissions()->detach();
            $role->delete();
            return $this->returnSuccessMessage(msg: 'role deleted successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
