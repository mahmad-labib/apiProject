<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Models\Article;
use App\Models\Section;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // $imgarr = [];
        // foreach ($request->images as $image) {
        //     array_push($imgarr, $image->getClientOriginalExtension());
        // };
        // return response($imgarr);
        // return $this->returnData('data', $request->all());
        try {
            $this->validate($request, [
                'title' => 'required',
                'content' => 'required',
                'section_id' => 'required',
                'images' => 'required',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            ]);
            $user = auth()->user();
            $article = new Article;
            $requestSection = Section::find($request->section_id);
            $userSections = $user->sections;
            if (empty($requestSection) || empty($userSections)) {
                return $this->returnError('404', 'somthing went wrong');
            }
            $checkSection = $this->checkChildren($userSections, $requestSection);
            if ($checkSection) {
                $content = $request->content;
                $images = $request->file('images');
                $data = $this->createArticleWithImages($content, $images);
                $article->title = $request->title;
                $article->content = htmlentities($data->content);
                $article->save();
                $article->creator()->sync($user->id);
                $article->section()->sync($request->section_id);
                foreach ($data->imagesPath as $image) {
                    $image->save();
                    $image->article()->attach($article->id);
                }
                return $this->returnSuccessMessage('articel posted');
            }
            return $this->returnError('403', 'forbidden');
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
        try {
            $data = Article::find($id);
            $content = html_entity_decode($data->content);
            $data->content = $content;
            if (!empty($data)) {
                return $this->returnData('article', $data);
            }
            return $this->returnError('404', 'not found');
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
            $this->validate($request, [
                'title' => 'required',
                'content' => 'mimes:txt|required',
                'section_id' => 'required',
            ]);
            $article = Article::find($id);
            $user = auth()->user();
            $requestSection = Section::find($request->section_id);
            $checkSection = $this->checkChildren($user->sections, $requestSection);
            if ($checkSection) {
                $data = $this->replaceArticleImages($article, $request);
                $article->content = htmlentities($data->content);
                $article->title = $request->content;
                $article->section()->sync($request->section_id);
                $article->save();
                foreach ($data->imagesPath as $image) {
                    $image->save();
                    $image->article()->attach($article->id);
                }
                return $this->returnSuccessMessage('article updated');
            }
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
            $user = auth()->user();
            $article = Article::find($id);
            $articleCreator = $article->creator;
            foreach ($articleCreator as $creator) {
                if ($user->id === $creator->id) {
                    $this->deleteImages($article->images);
                    $article->images()->detach();
                    $article->delete();
                    return $this->returnSuccessMessage('deleted');
                }
            }
            return $this->returnError('403', 'forbidden');
        } catch (\Throwable $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
