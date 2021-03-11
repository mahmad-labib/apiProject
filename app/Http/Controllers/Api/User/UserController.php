<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class UserController extends Controller
{
    use GeneralTrait;
    // use HasPermissionsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = auth()->user();
            dd($user->can('publisher'));
            return $this->returnData('user', $user);
        } catch (\Throwable $ex) {
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
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'confirmPassword' => 'required'
            ]);
            $pass = $request->password;
            $confirmPass = $request->confirmPassword;
            $name = $request->name;
            $email = $request->email;
            $user = new User;
            $checkEmail = User::where('email', $email)->pluck('id')->first();
            if ($pass !== $confirmPass) {
                return $this->returnError('409', 'password dont match');
            }
            if ($checkEmail !== null) {
                return $this->returnError('409', 'this user email already exists');
            }
            $user->name = $name;
            $user->email = $email;
            $user->password = bcrypt($pass);
            $user->save();
            return $this->returnSuccessMessage('registered');
        } catch (\Throwable $ex) {
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
