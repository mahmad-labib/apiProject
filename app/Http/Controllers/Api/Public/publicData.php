<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\User;
use Illuminate\Http\Request;

class publicData extends Controller
{
    use GeneralTrait;

    public function creator($id)
    {
        try {
            $user = User::query()
                ->with(array('articles' => function ($query) {
                    $query->select('id', 'title')->with(array('images' => function ($query) {
                        $query->select('id', 'path');
                    }));
                }))->where('id', $id)->first();
            $user->sections;
            return $this->returnData('creator', $user);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
