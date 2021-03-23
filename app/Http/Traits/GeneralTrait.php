<?php

namespace App\Http\Traits;

use App\Models\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait GeneralTrait
{

    public function checkChildren($userSections, $requestData)
    {
        if (empty($userSections) || empty($requestData)) {
            return $this->returnError('404', 'somthing went wrong');
        }
        $children = [];
        foreach ($userSections as $section) {
            $sectionChildren = $section->children;
            foreach ($sectionChildren as $child) {
                array_push($children, $child->name);
            }
        }
        return  in_array($requestData->name, $children);
    }

    public function getCurrentLang()
    {
        return app()->getlocale();
    }

    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

    public function returnSuccessMessage($msg = "", $successNum = "200")
    {
        return [
            'status' => true,
            'successNum' => $successNum,
            'msg' => $msg
        ];
    }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'successNum' => "200",
            'msg' => $msg,
            $key => $value
        ]);
    }

    public function deleteImages($images)
    {
        foreach ($images as $image) {
            $image_path =  public_path() . '/storage/' . $image->path;
            if (File::exists($image_path))
                unlink($image_path);
                $image->delete();
        }
    }

    public function replaceArticleImages($oldContent, $request)
    {
        $content = file_get_contents($request->content);
        $data = $this->createArticleWithImages($content, $request->images);
        $oldImages = $oldContent->images;
        foreach ($oldImages as $image) {
            $checkReplacedImg = strpos($data->content, $image->path);
            
            if (!$checkReplacedImg) {
                $image_path =  public_path() . '/storage/' . $image->path;
                if (File::exists($image_path)){
                    unlink($image_path);
                    $oldContent->images()->detach($image->id);
                    $image->delete();
                } 
            }
        }
        return $data;
    }

    public function createArticleWithImages($content, $images)
    {
        $data =  new class{};
        $imagesPath = [];
        foreach ($images as $image) {
            $name = $image->getClientOriginalName();
            $imageSaveName = time() . '.' . bcrypt($name) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('uploads/avatar/' . Auth()->id(), $imageSaveName, 'public');
            $image = new Image;
            $image->path = $path;
            array_push($imagesPath, $image);
            $url = Storage::url($path);
            $content = str_replace($name, 'http://' . $_SERVER['SERVER_NAME'] . $url, $content);
        }
        $data->imagesPath = $imagesPath;
        $data->content = $content;
        return $data;
    }

    public function returnValidationError($validator, $code = "E001")
    {
        return $this->returnError($code, $validator->errors()->first());
    }

    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0011';

        else if ($input == "password")
            return 'E002';

        else if ($input == "mobile")
            return 'E003';

        else if ($input == "id_number")
            return 'E004';

        else if ($input == "birth_date")
            return 'E005';

        else if ($input == "agreement")
            return 'E006';

        else if ($input == "email")
            return 'E007';

        else if ($input == "city_id")
            return 'E008';

        else if ($input == "insurance_company_id")
            return 'E009';

        else if ($input == "activation_code")
            return 'E010';

        else if ($input == "longitude")
            return 'E011';

        else if ($input == "latitude")
            return 'E012';

        else if ($input == "id")
            return 'E013';

        else if ($input == "promocode")
            return 'E014';

        else if ($input == "doctor_id")
            return 'E015';

        else if ($input == "payment_method" || $input == "payment_method_id")
            return 'E016';

        else if ($input == "day_date")
            return 'E017';

        else if ($input == "specification_id")
            return 'E018';

        else if ($input == "importance")
            return 'E019';

        else if ($input == "type")
            return 'E020';

        else if ($input == "message")
            return 'E021';

        else if ($input == "reservation_no")
            return 'E022';

        else if ($input == "reason")
            return 'E023';

        else if ($input == "branch_no")
            return 'E024';

        else if ($input == "name_en")
            return 'E025';

        else if ($input == "name_ar")
            return 'E026';

        else if ($input == "gender")
            return 'E027';

        else if ($input == "nickname_en")
            return 'E028';

        else if ($input == "nickname_ar")
            return 'E029';

        else if ($input == "rate")
            return 'E030';

        else if ($input == "price")
            return 'E031';

        else if ($input == "information_en")
            return 'E032';

        else if ($input == "information_ar")
            return 'E033';

        else if ($input == "street")
            return 'E034';

        else if ($input == "branch_id")
            return 'E035';

        else if ($input == "insurance_companies")
            return 'E036';

        else if ($input == "photo")
            return 'E037';

        else if ($input == "logo")
            return 'E038';

        else if ($input == "working_days")
            return 'E039';

        else if ($input == "insurance_companies")
            return 'E040';

        else if ($input == "reservation_period")
            return 'E041';

        else if ($input == "nationality_id")
            return 'E042';

        else if ($input == "commercial_no")
            return 'E043';

        else if ($input == "nickname_id")
            return 'E044';

        else if ($input == "reservation_id")
            return 'E045';

        else if ($input == "attachments")
            return 'E046';

        else if ($input == "summary")
            return 'E047';

        else if ($input == "user_id")
            return 'E048';

        else if ($input == "mobile_id")
            return 'E049';

        else if ($input == "paid")
            return 'E050';

        else if ($input == "use_insurance")
            return 'E051';

        else if ($input == "doctor_rate")
            return 'E052';

        else if ($input == "provider_rate")
            return 'E053';

        else if ($input == "message_id")
            return 'E054';

        else if ($input == "hide")
            return 'E055';

        else if ($input == "checkoutId")
            return 'E056';

        else
            return "";
    }
}
