<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class GetArticle extends Controller
{
    use GeneralTrait;
    public function show($id)
    {
        try {
            $data = Article::find($id);
            $data->creator;
            $data->section;
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
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $articles =  User::where('id', $user->id)
                ->with(array('articles' => function ($query) {
                    $query->select('id', 'title')->with(array('images' => function ($query) {
                        $query->select('id', 'path');
                    }));
                }))->paginate(5, ['*'], 'page', $request->paginate);

            foreach ($articles as $article) {
                $content = html_entity_decode($article->content);
                $article->content = $content;
            };
            return $this->returnData('articles', $articles);
        } catch (\Throwable $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function news(Request $request)
    {
        try {
            $articles = Article::query()
                ->with(array('creator' => function ($query) {
                    $query->select('id', 'name', 'speciality');
                }))
                ->with(array('images' => function ($query) {
                    $query->select('path');
                }))
                ->paginate(10, ['*'], 'page', $request->header('paginate'));
            return $this->returnData('articles', $articles);
        } catch (\Throwable $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
