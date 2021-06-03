<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\PendingArticles;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Models\Article;
use App\Models\Section;

class SubmitToPendingArticleController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = auth()->user();
            $articles = PendingArticles::where('creator_id', $user->id)->get();
            foreach ($articles as $article) {
                $content = html_entity_decode($article->content);
                $article->content = $content;
            }
            return $this->returnData('articles', $articles);
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
                'title' => 'required',
                'content' => 'mimes:txt|required',
                'section_id' => 'required',
                'images' => 'required'
            ]);
            $user = auth()->user();
            $requestSection = Section::find($request->section_id);
            $userSections = $user->sections;
            $article = new PendingArticles;
            $checkSection = $this->checkChildren($userSections, $requestSection);
            // $checkName = Article::where('title', $request->title);
            // if (!$checkName) {
            //     return $this->returnError('409', 'this title does exist');
            // }
            if ($checkSection) {
                $content = file_get_contents($request->content);
                $images = $request->file('images');
                $data = $this->createArticleWithImages($content, $images);
                $article->content = htmlentities($data->content);
                $article->title = $request->title;
                $article->section_id = $request->section_id;
                $article->creator_id = $user->id;
                $article->save();
                foreach ($data->imagesPath as $image) {
                    $image->save();
                    $image->pending_article()->attach($article->id);
                }
                return $this->returnSuccessMessage('article posted and waiting for approve');
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
            $article = PendingArticles::find($id);
            $content = html_entity_decode($article->content);
            $article->content = $content;
            return $this->returnData('article', $content);
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
                'images' => 'required'
            ]);
            $user = auth()->user();
            $requestSection = Section::find($request->section_id);
            $checkSection = $this->checkChildren($user->sections, $requestSection);
            if ($checkSection) {
                $article = PendingArticles::find($id);
                $data = $this->replaceArticleImages($article, $request);
                $article->content = htmlentities($data->content);
                $article->title = $request->title;
                $article->section_id = $request->section_id;
                $article->save();

                foreach ($data->imagesPath as $image) {
                    $image->save();
                    $image->pending_article()->attach($article->id);
                }

                return $this->returnSuccessMessage('article posted and waiting for approve');
            }
            return $this->returnError('404', 'somthing went wrong');
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
            $pendingArticle = PendingArticles::find($id);
            $this->deleteImages($pendingArticle->images);
            $pendingArticle->images()->detach();
            $pendingArticle->delete();
        } catch (\Throwable $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
