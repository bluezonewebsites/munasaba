<?php

namespace App\Http\Controllers;

use App\Models\Fav;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class FavController extends ApiController
{
    public function getAllFavByUserid(Request $request)
    {
        $fav = Fav::with('prod')
        ->with('prod.country:id,currency_ar')
        ->with('user')
        ->where('uid',$request['uid'])->get();
        return $this->apiResponse($request, trans('language.message'), $fav, true);
    }
    public function makeFavProd(Request $request)
    {
        $fav_prod = Fav::where('uid', $request['uid'])->where('prod_id', $request['prod_id'])->first();
        if ($fav_prod) {
            $fav_prod->delete();
            return $this->apiResponse($request, trans('language.deleted'), null, true);
        } else {
            $fav_prod = Fav::create([
                'uid' => $request['uid'],
                'prod_id' => $request['prod_id'],
            ]);
            return $this->apiResponse($request, trans('language.created'), $fav_prod, true);
        }
    }
}