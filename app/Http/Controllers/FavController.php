<?php

namespace App\Http\Controllers;

use App\Models\Fav;
use App\Models\Prod;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\FollowRing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class FavController extends ApiController
{
    public  function  __construct()
    {
        if(\request()->header('Authorization')){
            $this->middleware('auth:sanctum');
        }
    }
    public function getAllFavByUserid(Request $request)
    {
        $fav = Prod::wherehas('fav',function ($q) use ($request){
            $q->where('fav.uid',$request['uid']);
            })

        ->paginate(10);
        return $this->apiResponse($request, trans('language.message'), $fav, true);
    }
    public function makeFavProd(Request $request)
    {
        if(!Auth::user()){
            return $this->apiResponse($request, __('language.unauthenticated'), null, false, 500);
        }
        $fav_prod = Fav::where('uid', Auth::id())
            ->where('prod_id', $request['prod_id'])->first();
        if ($fav_prod) {
            $fav_prod->delete();
            return $this->apiResponse($request, trans('language.deleted'), null, true);
        } else {
            $fav_prod = Fav::create([
                'uid' =>  Auth::id(),
                'prod_id' => $request['prod_id'],
            ]);
            return $this->apiResponse($request, trans('language.created'), $fav_prod, true);
        }
    }


    public function activeNotifi(Request $request)
    {

        $user= FollowRing::where('uid',$request['uid'])
            ->where('fid',$request['anther_user_id'])
            ->first();
        if($user){
            $user->delete();
            return $this->apiResponse($request, trans('language.deleted'), null, true);

        }else{
            FollowRing::create([
                'uid' => $request['uid'],
                'fid' => $request['anther_user_id'],
            ]);
        }
        return $this->apiResponse($request, trans('language.created'), $user, true);

    }
}
