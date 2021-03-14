<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Models\Article;
use App\Models\Section;
use Tymon\JWTAuth\Contracts\Providers\Auth;

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
        try {
            $this->validate($request, [
                'title' => 'required',
                'content' => 'required',
                'section_id' => 'required',
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
                $article->title = $request->title;
                $article->content = $request->content;
                $article->save();
                $article->creator()->sync($user->id);
                $article->section()->sync($request->section_id);
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
