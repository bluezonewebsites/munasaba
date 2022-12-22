<?php

namespace App\Http\Controllers;

use App\Models\UserBlocked;
use Illuminate\Http\Request;

class UserBlockedController extends Controller
{

    public function blocked(Request $request){
        $from_uid=$request['from_uid'];
        $to_uid=$request['to_uid'];
        $blocked_user=UserBlocked::where('to_uid',$to_uid)->where('from_uid',$from_uid)->first();
        if($blocked_user){
            $blocked_user->delete();
            return $this->apiResponse($request,"UnBlocked Sucsess", $blocked_user, true);

        }else{
            $blocked_user=UserBlocked::create([
                'to_uid' => $to_uid,
                'from_uid' => $from_uid,
            ]);
            return $this->apiResponse($request,"Blocked Sucsess", $blocked_user, true);

        }
        
    }
    
}
