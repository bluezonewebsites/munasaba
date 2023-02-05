<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Models\FcmTokenModel;
use App\Models\ShowNotification;
use App\Models\User;
use App\Models\Notification;
use App\Models\UserBlocked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends ApiController
{
    public  function  __construct()
    {
        $this->middleware('auth:sanctum')->only('delete');
        if(\request()->header('Authorization')){
            $this->middleware('auth:sanctum');
        }
    }
    public function index(Request $request)
    {
        $value=Auth::id();
        $report_user1= UserBlocked::where('from_uid',$value)->pluck('to_uid')->toarray();
        $report_user2= UserBlocked::where('to_uid',$value)->pluck('from_uid')->toarray();
        $report_user=[];
        if($report_user1 != null){
            $report_user=array_merge($report_user1,$report_user);
        }
        if($report_user2 != null){
            $report_user=array_merge($report_user2,$report_user);
        }
        $notifications= Notification::where('uid',$value)
                                        ->where(function ($query) use ($report_user) {
                                            $query->whereNotIn('uid',  $report_user )
                                                ->WhereNotIn('fid', $report_user );
                                        })->latest()->get();

        return $this->apiResponse($request, trans('language.message'), $notifications, true);
    }

    public function save_token(Request $request)
    {

        $user=User::where('id',Auth::id())->update([
                    'regid'=>$request->fcm_token
                ]);
        return $this->apiResponse($request, trans('language.message'), false, true);

    }
    public function delete(Request $request)
    {

        Notification::where('id',$request->notification_id)->delete();
        return $this->apiResponse($request, trans('language.message'), [], true);

    }

    public function active(Request $request)
    {
        $notification = (Auth::user()->notification * -1)+1;
        $user=User::where('id',Auth::id())->update([
            'notification'=>$notification
        ]);
        return $this->apiResponse($request, trans('language.message'), [], true);

    }
}
