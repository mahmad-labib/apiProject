<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Article;

class GetArticle extends Controller
{
    use GeneralTrait;
    public function show($id)
    {
        try {
            $data = Article::find($id);
            $content = html_entity_decode($data->content);
            $data->content = $content;
            if (!empty($data)) {
                return $this->returnData('article', $data);
            }
            return $this->returnError('404', 'not found');
        } catch (\Throwable $ex) {
            return $this->returnError('404', 'not found');
        }
    }
    public function index()
    {
        try {
            $user = auth()->user();
            $articles = $user->articles;
            return $this->returnData('articles', $articles);
        } catch (\Throwable $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
