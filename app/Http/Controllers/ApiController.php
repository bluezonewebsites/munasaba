<?php

namespace App\Http\Controllers;

use App\Models\FcmTokenModel;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Room;
use App\Models\User;
use App\Traits\apiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests , apiResponse;//,apiNotification;
    static function send_notf($fcm_token , $data,$app_name,$not = null ,$type_mob=false){
        try {
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);
            $notificationBuilder = new PayloadNotificationBuilder($app_name);
            $notificationBuilder->setBody($data)
                ->setSound('default');
            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(['a_data' => $not]);

            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();
            // dd($data);
            $token = $fcm_token;
            if($type_mob){
                $downstreamResponse = FCM::sendTo($token, $option,$notification , $data);
            }else{
                $downstreamResponse = FCM::sendTo($token, $option,null , $data);

            }


            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            $downstreamResponse->numberModification();
            // return Array - you must remove all this tokens in your database
            $downstreamResponse->tokensToDelete();
            // return Array (key : oldToken, value : new token - you must change the token in your database)
            $downstreamResponse->tokensToModify();
            // return Array - you should try to resend the message to the tokens in the array
            $downstreamResponse->tokensToRetry();
            // return Array (key:token, value:error) - in production you should remove from your database the tokens
            $downstreamResponse->tokensWithError();
        }catch (\Exception $e){

        }

    }
    static function send_notf_array($fcm_tokens , $data,$app_name,$not = null,$type_mob=false){
        try {

            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);

            $notificationBuilder = new PayloadNotificationBuilder($app_name);
            $notificationBuilder->setBody($data)
                ->setSound('default');

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData($not->toarray());
            // dd($dataBuilder);
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();
            // dd($data);

            // You must change it to get your tokens
            if($type_mob){
                $downstreamResponse = FCM::sendTo($fcm_tokens, $option,$notification , $data);
            }else{
                $downstreamResponse = FCM::sendTo($fcm_tokens, $option,null , $data);

            }

            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            $downstreamResponse->numberModification();

            // return Array - you must remove all this tokens in your database
            $downstreamResponse->tokensToDelete();

            // return Array (key : oldToken, value : new token - you must change the token in your database)
            $downstreamResponse->tokensToModify();

            // return Array - you should try to resend the message to the tokens in the array
            $downstreamResponse->tokensToRetry();

            // return Array (key:token, value:error) - in production you should remove from your database the tokens present in this array
            $downstreamResponse->tokensWithError();
        }catch (\Exception $e){

        }

    }

    static function save_notf( $type ,$type_id,$body,$user_send = null  , $user_r=null,$nto='user',$nfrom='user',$is_all=false ){
        $app=__('site.app_name');
        if(!$is_all){
            $user=User::where('id',$user_r)->where('notification',1)->first();
            if($user){
                $not= Notification::create([
                    'oid'=>$type_id,
                    'uid'=>$user_r,
                    'fid'=>$user_send,
                    'ntype'=>$type,
                    'ncontent'=>$body,
                    'nfrom'=>$nfrom,
                    'nto'=>$nto,
                ]);
                if($type=='CHAT'){
                    $not->unseen_count = Message::where('room_id',$type_id)
                        ->where('seen',0)->where('rid',$user->id)->count();
                }


                if($user->regid){
                    if($user->type_mob){
                        self::send_notf($user->regid, $body, $app,$not, true);
                    }else {
                        self::send_notf($user->regid, $body, $app, $not);
                    }
                }
            }
        }else{
            $not=null;
            foreach ($user_r as $one_user_r){
                $user=User::where('id',$one_user_r)->where('notification',1)->first();
                if($user) {
                    $not = Notification::create([
                        'oid' => $type_id,
                        'uid' => $one_user_r,
                        'fid' => $user_send,
                        'ntype' => $type,
                        'ncontent' => $body,
                        'nfrom' => $nfrom,
                        'nto' => $nto,
                    ]);
                }
            }
            $regids=User::wherein('id',$user_r)->whereNotNull('regid')->where('type_mob',0)->where('notification',1)->pluck('regid');
            $regids_ios=User::wherein('id',$user_r)->whereNotNull('regid')->where('type_mob',1)->where('notification',1)->pluck('regid');
            if($regids){
                self::send_notf_array($regids,$body,$app,$not,false);
            }
            if($regids_ios) {
                self::send_notf_array($regids_ios, $body, $app,$not, true);
            }
        }



    }
}
