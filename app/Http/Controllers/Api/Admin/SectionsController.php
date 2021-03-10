<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Traits\GeneralTrait;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionsController extends Controller
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
            $sections = Section::All();
            return $this->returnData('sections', $sections);
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
            $section = new Section();
            $this->validate($request, [
                'name' => 'required',
                'parent_id' => 'required',
            ]);
            $secNameCheck = Section::where('name', $request->name)->first();
            if ($secNameCheck) {
                return $this->returnError('409', 'section name already exists');
            }
            $section->name = $request->name;
            $section->parent_id = $request->parent_id;
            $section->save();
            return $this->returnSuccessMessage(msg: 'data saved');
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
            $section = Section::find($id);
            if (!$section) {
                return $this->returnError('404', 'data not found');
            }
            return $this->returnData('section', $section);
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
                'name' => 'required',
            ]);
            $sectionRequest = $request->all();
            $sectionModel = Section::find($id);
            if (!$sectionModel) {
                return $this->returnError('404', 'section dont exists!');
            }
            $sectionModel->update($sectionRequest);
            return $this->returnSuccessMessage(msg: 'data updated successfully');
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
            $section = Section::find($id);
            $section->children()->update(['parent_id' => $section->parent_id]);
            $section->delete();
            return $this->returnSuccessMessage(msg: 'data deleted successfully');
        } catch (\Throwable $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
