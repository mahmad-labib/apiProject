<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\PendingArticles;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Models\Article;
use App\Models\Section;
use Illuminate\Support\Facades\Storage;

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
                'images' => 'required'
            ]);
            $user = auth()->user(); 
            $requestSection = Section::find($request->section_id);
            $userSections = $user->sections;
            $article = new PendingArticles;
            if (empty($requestSection) || empty($userSections)) {
                return $this->returnError('404', 'somthing went wrong');
            }
            $checkName = Article::where('title', $request->title);
            if (!$checkName) {
                return $this->returnError('409', 'this title does exist');
            }
            $checkSection = $this->checkChildren($userSections, $requestSection);
            if ($checkSection) {
                $content = $request->content;
                $image = $request->file('images');
                foreach ($image as $image) {
                    $name = $image->getClientOriginalName();
                    $imageSaveName = time() . '.' . bcrypt($name) . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('uploads/avatar/' . Auth()->id(), $imageSaveName, 'public');
                    $url = Storage::url($path);
                    $content = str_replace($name, $_SERVER['SERVER_NAME'] . $url, $content);
                }
                $article->content = base64_encode($content);;
                $article->title = $request->title;
                $article->section_id = $request->section_id;
                $article->creator_id = $user->id;
                $article->save();
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
