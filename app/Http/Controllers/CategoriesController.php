<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Http\Controllers\ApiController;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends  ApiController
{

    public function getAllCategories(Request $request)
    {
        $categories = DB::table('cats')
        ->where('cats.cat_id',0)
        ->select('cats.*')
        ->get();
        $categories->ask= 1;
        return $this->apiResponse($request, trans('language.message'), $categories, true);
    }
    public function getAllSubCategories(Request $request)
    {
        $sub_categories =DB::table('cats')
        ->where('cats.cat_id',1)
        ->select('cats.*')
        ->get();
        return $this->apiResponse($request, trans('language.message'), $sub_categories, true);
    }
    public function getAllSubCategoriesbyId(Request $request)
    {
        $cat_id=$request['cat_id'];
        $sub_categories =DB::table('cats')
        ->where('cats.id',$cat_id)
        ->select('cats.*')
        ->get();
        return $this->apiResponse($request, trans('language.message'), $sub_categories, true);
    }
    public function getCategoriesById(Request $request)
    {
        $cat_id=$request['cat_id'];
        $category = DB::table('cats')
        ->where('cat_id',$cat_id)
        ->select('cats.*')
        ->find($cat_id);
        if (!$category) {
            return $this->apiResponse($request, trans('language.message_error'), null, false,500);
        }
        return $this->apiResponse($request, trans('language.message'), $category, true);

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
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function show(Category $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $categories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $categories)
    {
        //
    }
}
