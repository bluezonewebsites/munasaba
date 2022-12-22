<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CitiesController extends ApiController
{

    public function getAllCities(Request $request)
    {
        $cities = City::select('id','country_id','name_ar','name_en')->with('country:id,name_ar,name_en')->get();
        return $this->apiResponse($request, trans('language.message'), $cities, true);
    }

    public function getAllCitiesByCountrId(Request $request)
    {
        $country_id = $request['country_id'];
        $cities = City::where('country_id', $country_id)->with('country:id,name_ar,name_en')->get();
        return $this->apiResponse($request, trans('language.message'), $cities, true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::with('country:id,name_ar,name_en')->get();
        return view('cities', compact('cities'));
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
     * @param  \App\Models\Cities  $cities
     * @return \Illuminate\Http\Response
     */
    public function show(City $cities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cities  $cities
     * @return \Illuminate\Http\Response
     */
    public function edit(City $cities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cities  $cities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $cities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cities  $cities
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $cities)
    {
        //
    }
}
