<?php

namespace App\Http\Controllers;

use App\Models\Followimg;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class FollowimgController extends ApiController
{
    public function getAllFollowingByUserid(Request $request)
    {
        $followimg = DB::table('followings')
        ->where('followings.uid',$request['uid'])
        ->leftjoin('user as user_to','user_to.id','followings.uid')
        ->leftjoin('user as user_from','user_from.id','followings.fid')
        ->select('followings.*','user_from.name as user_from_name'
        ,'user_to.name as user_to_name')
        ->get();        
       
        return $this->apiResponse($request, trans('language.message'), $followimg, true);
    }
    public function makeFollowing(Request $request)
    {
        $user= Followimg::where('uid',$request['uid'])->where('fid',$request['anther_user_id'])->first();
        if($user){
            $user->delete();
            return $this->apiResponse($request, trans('language.deleted'), null, true);

        }else{
            Followimg::create([
                'uid' => $request['uid'],
                'fid' => $request['anther_user_id'],
            ]);
        }
        return $this->apiResponse($request, trans('language.created'), $user, true);

    }
}
