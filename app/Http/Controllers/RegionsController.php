<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class RegionsController extends ApiController
{

    public function getAllRegions(Request $request){
        $regions=DB::table('regions')
        ->leftjoin('cities','cities.id','regions.city_id')
        ->select('regions.*','cities.name_ar as city_name_ar')
        ->get(); 
        return $this->apiResponse($request, trans('language.message'), $regions, true);

    }

    public function getAllRegionsByCityId(Request $request){
        $city_id=$request['city_id'];
        $region=DB::table('regions')
        ->where('regions.city_id',$city_id)
        ->leftjoin('cities','cities.id','regions.city_id')
        ->select('regions.*','cities.name_ar as city_name_ar')
        ->get(); 
        return $this->apiResponse($request, trans('language.message'), $region, true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $regions=Region::with('city:id,name_ar,name_en')->get();
        return view('regions', compact('regions'));

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
     * @param  \App\Models\Zones  $zones
     * @return \Illuminate\Http\Response
     */
    public function show(Region $zones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zones  $zones
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $zones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zones  $zones
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $zones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zones  $zones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $zones)
    {
        //
    }
}
