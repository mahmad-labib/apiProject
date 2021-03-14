<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Models\PendingArticles;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class PendingArticleController extends Controller
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
            $articles = PendingArticles::all();
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
        try {
            $pendingArticle = PendingArticles::find($id);
            $article = new Article;
            $this->validate($request, [
                'state' => 'required',
            ]);
            if ($request->state === 'approved') {
                $article->title = $pendingArticle->title;
                $article->content = $pendingArticle->content;
                $article->save();
                $article->creator()->attach($pendingArticle->creator_id);
                $article->section()->attach($pendingArticle->section_id);
                $pendingArticle->delete();
                return $this->returnSuccessMessage('article published');
            }
            if ($request->state === 'rejected') {
                $pendingArticle->state = $request->state;
                $pendingArticle->comment = $request->comment;
                $pendingArticle->save();
                return $this->returnSuccessMessage('article rejected');
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
        //
    }
}
