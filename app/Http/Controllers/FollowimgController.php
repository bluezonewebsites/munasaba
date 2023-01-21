<?php

namespace App\Http\Controllers;

use App\Models\Followimg;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class FollowimgController extends ApiController
{
    public function getAllFollowingByUserid(Request $request)
    {
        $followimg = DB::table('followings')
        ->where('followings.uid',$request['uid'])
        ->leftjoin('user as user_to','user_to.id','followings.uid')
        ->leftjoin('user as user_from','user_from.id','followings.fid')
        ->select('followings.*','user_from.name as user_from_name'
        ,'user_to.name as user_to_name')
        ->get();        
       
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
