<?php

namespace App\Http\Controllers;

use App\Models\Prod;
use App\Models\Prods;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProdsController extends ApiController
{
    public function getAllProds(Request $request)
    {
        $prods = Prod::where('country_id',$request['country_id'])->get();
        return $this->apiResponse($request, trans('language.message'), $prods, true);
    }
    public function getAllProdsByCatid(Request $request)
    {
        $cat_id=$request['cat_id'];
        $country_id=$request['country_id'];
        $prods = Prod::where('country_id',$country_id)->where('cat_id',$cat_id)->get();
        return $this->apiResponse($request, trans('language.message'), $prods, true);
    }
    public function getAllProdsByUserid(Request $request)
    {
        $user_id=$request['uid'];
        $country_id=$request['country_id'];
        $prods = Prod::where('country_id',$country_id)->where('uid',$user_id)->get();
        return $this->apiResponse($request, trans('language.message'), $prods, true);
    }
    public function searchProds(Request $request)
    {
        $keyword=$request['keyword'];
        $country_id=$request['country_id'];
        $prods = Prod::where('country_id',$country_id)->where('name','LIKE', '%'.$keyword.'%')->where('descr','LIKE', '%'.$keyword.'%')->get();
        return $this->apiResponse($request, trans('language.message'), $prods, true);
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
     * @param  \App\Models\Prods  $prods
     * @return \Illuminate\Http\Response
     */
    public function show(Prods $prods)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prods  $prods
     * @return \Illuminate\Http\Response
     */
    public function edit(Prods $prods)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prods  $prods
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prods $prods)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prods  $prods
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prods $prods)
    {
        //
    }
}
