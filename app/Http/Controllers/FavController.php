<?php

namespace App\Http\Controllers;

use App\Models\Fav;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\FollowRing;
use Illuminate\Support\Facades\DB;

class FavController extends ApiController
{
    public function getAllFavByUserid(Request $request)
    {
        $fav = DB::table('fav')
        ->leftjoin('prods as p','p.id','fav.prod_id')
        ->leftjoin('countries','countries.id','p.country_id')
        ->leftjoin('user','user.id','fav.uid')
        ->where('fav.uid',$request['uid'])
        ->select('fav.*'
        ,'p.price as prod_price'
        ,'p.img as prod_image'
        ,'p.name as prod_name'
        ,'p.loc as prod_location'
        ,'p.tajeer_or_sell as prod_tajeer_or_sell'
        ,'user.name as name'
        ,'user.last_name as last_name'
        ,'user.verified as user_verified'
        ,'countries.name_ar as countries_name_ar'
        ,'countries.name_ar as countries_name_ar'
        ,'countries.currency_ar as currency_ar'
        )
        ->paginate(10);        
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


    public function activeNotifi(Request $request)
    {
        $user= FollowRing::where('uid',$request['uid'])->where('fid',$request['anther_user_id']);
        if($user){
            $user->delete();
        }else{
            FollowRing::create([
                'uid' => $request['uid'],
                'fid' => $request['anther_user_id'],
            ]);
        }
        return $this->apiResponse($request, trans('language.created'), $user, true);

    }
}