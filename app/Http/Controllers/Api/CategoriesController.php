<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        $lang = app()->getLocale();
        $categories = Category::selectION()->get();

        return $this->returnData(key: 'categories', value: $categories);
    }

    public function getCategoryById(Request $request)
    {

        $category = Category::selection()->find($request->id);
        if (!$category) {
            return $this->returnError(errNum: '001', msg: 'هذا القسم غير موجود');
        };
        return  $this->returnData(key: 'category', value: $category);
    }

    public function changeStatus(Request $request)
    {
        //validation
        Category::where('id', $request->id)->update(['active' => $request->active]);
        return $this->returnSuccessMessage(msg: 'تم تغيير الحاله بنجاح');
    }
}
