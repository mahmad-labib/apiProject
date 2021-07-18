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
            $users = User::paginate(10, ['*'], 'page', $request->header('paginate'));
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
        // $users = User::where('name', 'LIKE', "%{$request->name}%")->paginate(15, ['*'], 'page', $request->paginate)->except(auth()->user()->id);

        $users = User::where('name', 'LIKE', "%{$request->name}%")->paginate(15, ['*'], 'page', $request->paginate);

        if (!empty($request->role)) {
            foreach ($users as $k => $user) {
                $checkRole =  $user->roles()->where('name', $request->role)->exists();
                if ($checkRole == false) {
                    unset($users[$k]);
                }
            }
        };
        if (!empty($request->section)) {
            foreach ($users as $k => $user) {
                $checkSection =  $user->sections()->where('name', $request->section)->exists();
                if ($checkSection == false) {
                    unset($users[$k]);
                }
            };
        };

        foreach ($users as $k => $user) {
            $user->roles;
            $user->sections;
        };

        // $users->pages = $pages;
        return $this->returnData('users', $users);
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
        // return $this->returnSuccessMessage(msg: $request->sections);
        try {
            $input = $request->all();
            $user = User::find($id);
            if (!$user) {
                return $this->returnError(errNum: '404', msg: 'user dont exist');
            };
            $user->update($input);
            if ($request->deletedRoles) {
                foreach ($request->deletedRoles as $roleId) {
                    $role =  Role::find($roleId);
                    if ($role) {
                        $user->roles()->detach($role);
                    }
                }
            }
            if ($request->deletedSections) {
                foreach ($request->deletedSections as $sectionId) {
                    $section =  Section::find($sectionId);
                    if ($section) {
                        $user->sections()->detach($section);
                    }
                }
            }
            if ($request->roles) {
                foreach ($request->roles as $roleId) {
                    $role =  Role::find($roleId);
                    if ($role) {
                        $user->roles()->attach($role);
                    }
                }
            }
            if ($request->sections) {
                foreach ($request->sections as $sectionId) {
                    $section =  Section::find($sectionId);
                    if ($section) {
                        $user->sections()->attach($section);
                    }
                }
            }

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
