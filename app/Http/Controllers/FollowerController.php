<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class FollowerController extends ApiController
{

    public function getAllFollowerByUserid(Request $request)
    {
        $follower = DB::table('followers')
        ->where('followers.uid',$request['uid'])
        ->leftjoin('user as user_to','user_to.id','followers.uid')
        ->leftjoin('user as user_from','user_from.id','followers.fid')
        ->select('followers.*','user_from.name as user_from_name'
        ,'user_to.name as user_to_name')
        ->get();        
        return $this->apiResponse($request, trans('language.message'), $follower, true);
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
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function show(Follower $follower)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function edit(Follower $follower)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Follower $follower)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function destroy(Follower $follower)
    {
        //
    }
}
