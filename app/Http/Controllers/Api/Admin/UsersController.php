<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Models\Role;
use App\Models\Section;

use function PHPUnit\Framework\isNull;

class UsersController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $users = User::paginate(10, ['*'], 'page', $request->header('paginate'))->except(auth()->user()->id);
            // $users = User::paginate(15)->lastPage()->except(auth()->user()->id);
            // $pages = $users->lastPage();
            foreach ($users as $user) {
                $user->roles;
                $user->sections;
            }
            // $users->pages = $pages;
            return $this->returnData('users', $users);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function usersPages()
    {
        try {
            $pages = User::paginate(10)->lastPage();
            return $this->returnData('pages', $pages);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $list = [];
            if (!empty($request->name)) {
                $users = User::where('name', 'LIKE', "%{$request->name}%")->get();
                foreach ($users as $user) {
                    // $request->role ? $role = $user->roles->where('name', $request->role)->count() : $role = 0;
                    // $request->section ? $section = $user->sections->where('name', $request->section)->count() : $section = 0;
                    // if (
                    //     $role > 0 &&
                    //     $section > 0
                    // ) {
                    //     $user->roles;
                    //     $user->sections;
                    //     array_push($list, $user);
                    // }
                    $user->whereHas('roles', function ($query) use ($request) {
                        return $request->role ?
                            $query->where('name', 'LIKE', "%{$request->role}%") : '';
                    })->whereHas('sections', function ($query) use ($request) {
                        return $request->section ?
                            $query->where('name', 'LIKE', "%{$request->section}%") : '';
                    })->get();
                    $user->roles;
                    $user->sections;
                    array_push($list, $user);
                }
                return $this->returnData('users', $list);
            } else {
                $users = User::whereHas('roles', function ($query) use ($request) {
                    return $request->role ?
                        $query->where('name', 'LIKE', "%{$request->role}%") : '';
                })->whereHas('sections', function ($query) use ($request) {
                    return $request->section ?
                        $query->where('name', 'LIKE', "%{$request->section}%") : '';
                })->get();
                foreach ($users as $user) {
                    $user->roles;
                    $user->sections;
                    array_push($list, $user);
                }
                return $this->returnData('users', $users);
            }
            // $users = User::where('name',$request->name)
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
        //
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
            $user = User::where('id', $id)->first();
            if (!$user) {
                return $this->returnError('404', 'user not found');
            }
            $user->roles;
            $user->sections;
            return $this->returnData('user', $user);
        } catch (\Exception $ex) {
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
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'role' => 'required'
            ]);

            $input = $request->all();
            $role = Role::find($request->role);
            $user = User::find($id);
            if (!$user) {
                return $this->returnError(errNum: '404', msg: 'user dont exist');
            };
            $user->update($input);
            $user->roles()->sync($role);
            return $this->returnSuccessMessage(msg: 'user updated successfully');
        } catch (\Exception $ex) {
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
            $user =  User::where('id', $id)->first();

            if (!$user) {
                return $this->returnError(errNum: '404', msg: 'user dont exist');
            }
            $user->roles()->detach();
            $user->delete();
            return $this->returnSuccessMessage(msg: 'user deleted successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
