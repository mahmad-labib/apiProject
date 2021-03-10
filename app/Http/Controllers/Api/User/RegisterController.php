<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;

class RegisterController extends Controller
{
    use GeneralTrait;

    public function register(Request $request)
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
            $user = new User();
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
}
