<?php

namespace App\Http\Controllers;

use App\Models\Followimg;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class FollowimgController extends ApiController
{
    public function getAllFollowingByUserid(Request $request)
    {
        $followimg = Followimg::where('uid',$request['uid'])->get();
        return $this->apiResponse($request, trans('language.message'), $followimg, true);
    }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Followimg  $followimg
     * @return \Illuminate\Http\Response
     */
    public function show(Followimg $followimg)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Followimg  $followimg
     * @return \Illuminate\Http\Response
     */
    public function edit(Followimg $followimg)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Followimg  $followimg
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Followimg $followimg)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Followimg  $followimg
     * @return \Illuminate\Http\Response
     */
    public function destroy(Followimg $followimg)
    {
        //
    }
}
