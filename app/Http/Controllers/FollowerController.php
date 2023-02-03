<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class FollowerController extends ApiController
{

    public function getAllFollowerByUserid(Request $request)
    {
        $follower = DB::table('followers')
        ->where('followers.uid',$request['uid'])
        ->leftjoin('user as user_to','user_to.id','followers.uid')
        ->leftjoin('user as user_from','user_from.id','followers.fid')
        ->select(
            'user_from.id as user_id',
            'user_from.name as user_name',
            'user_from.last_name as last_name',
            'user_from.verified as user_verified',
            'user_from.pic as user_pic',
        )
        ->get();
        return $this->apiResponse($request, trans('language.message'), $follower, true);
    }
    public function makeFollow(Request $request)
    {
        $user= Follower::where('uid',$request['uid'])->where('fid',$request['anther_user_id'])->first();
        if($user){
            $user->delete();
            return $this->apiResponse($request, trans('language.deleted'), null, true);

        }else{
            Follower::create([
                'uid' => $request['uid'],
                'fid' => $request['anther_user_id'],
            ]);
        }
        return $this->apiResponse($request, trans('language.created'), $user, true);

    }
}
