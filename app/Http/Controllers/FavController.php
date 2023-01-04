<?php

namespace App\Http\Controllers;

use App\Models\Fav;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class FavController extends ApiController
{
    public function getAllFavByUserid(Request $request)
    {
        $fav = Fav::where('uid',$request['uid'])->get();
        return $this->apiResponse($request, trans('language.message'), $fav, true);
    }
    public function makeFavProd(Request $request){
        $fav_prod = Fav::create([
            'uid' => $request['uid'],
            'prod_id' => $request['prod_id'],
        ]);
        return $this->apiResponse($request, trans('language.created'), $fav_prod, true);

    }
}