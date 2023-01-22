<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitiesController extends ApiController
{

    public function getAllCities(Request $request)
    {
        $cities=DB::table('cities')
        ->leftjoin('countries','countries.id','cities.country_id')
        ->select('cities.*'
        ,'countries.name_ar as countries_name_ar' 
        ,'countries.name_en as countries_name_en')->get();        
        return $this->apiResponse($request, trans('language.message'), $cities, true);
    }

    public function getAllCitiesByCountrId(Request $request)
    {
        $country_id = $request['country_id'];
        $cities =DB::table('cities')
        ->where('cities.country_id', $country_id)
        ->leftjoin('countries','countries.id','cities.country_id')
        ->select('cities.*','countries.name_ar as countries_name_ar')
        ->get(); 
        return $this->apiResponse($request, trans('language.message'), $cities, true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['city'] =DB::table('city')
        ->leftjoin('country','country.id','city.country_id')->get();
        return view('cities', $data);
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
