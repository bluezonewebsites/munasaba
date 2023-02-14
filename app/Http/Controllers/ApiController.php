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
    static function send_notf($fcm_token , $data,$app_name,$not = null ){
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

            $downstreamResponse = FCM::sendTo($token, $option,$notification , $data);

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
    static function send_notf_array($fcm_tokens , $data,$app_name,$not = null){
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

            $downstreamResponse = FCM::sendTo($fcm_tokens, $option, null, $data);

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
                    self::send_notf($user->regid,$body,$app,$not);
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
            $regids=User::wherein('id',$user_r)->whereNotNull('regid')->where('notification',1)->pluck('regid');
            self::send_notf_array($regids,$body,$app,$not);
        }



    }


    // function send_not($oid, $not_type, $rid, $sid, $content)
    // {
    //     insertData('nots', 'oid,uid,fid,ntype,ncontent,nfrom,nto', "$oid,$rid,$sid,'$not_type','$content','user','user'");
    //     firebase($oid, $not_type, "user", "user", $rid, $sid);
    // }
    // function firebase($orderid, $title, $totype, $fromtype, $toid, $fromid)
    // {

    //      global $conn;
    //     error_reporting(-1);
    //     ini_set('display_errors', 'On');
    //     $firebase = new Firebase();
    //     $push = new Push();

    //     $payload = array();
    //     $payload['article_data'] = isset($_POST['article_data']) ? $_POST['article_data'] : '';

    //     $result = $conn->query("SELECT * FROM $fromtype where id=$fromid");
    //     if ($result->num_rows > 0) {
    //         $row = $result->fetch_assoc();
    //         $name = $row['name'];
    //         $pic = $row['pic'];
    //         $mobile = $row['mobile'];
    //     }
    //     $result = $conn->query("SELECT * FROM $totype where id=$toid");
    //     if ($result->num_rows > 0) {
    //         $row = $result->fetch_assoc();
    //         $regId = $row['regid'];
    //     }

    //     $push->setTitle($orderid . "##" . $title);
    //     $push->setMessage($name . "##" . $pic . "##" . $mobile . "##" . $fromid);
    //     $push->setImage("");
    //     $push->setIsBackground(TRUE);
    //     $push->setPayload($payload);

    //     $push_type = 'individual';
    //     $json = '';
    //     $response = '';

    //     if ($push_type == 'topic') {
    //         $json = $push->getPush();
    //         $response = $firebase->sendToTopic('global', $json);
    //     } else if ($push_type == 'individual') {
    //         $json = $push->getPush();
    //         $firebase->send($regId, $json);
    //         if ($title == "chat") {
    //             $push->setTitle($name);
    //             $push->setMessage($orderid);
    //             $json = $push->getPush2();
    //             $firebase->sendBG($regId, $json);

    //         } else if ($title == "ADD_ADV") {
    //             $push->setTitle($name);
    //             $push->setMessage("اضاف اعلان جديد");
    //         } else if ($title == "ASK_REPLY") {
    //             $push->setTitle($name);
    //             $push->setMessage("رد علي سؤالك");
    //         } else if ($title == "COMMENT_ADV") {
    //             $push->setTitle($name);
    //             $push->setMessage("قام بالتعليق علي اعلانك");
    //         } else if ($title == "FOLLOW") {
    //             $push->setTitle($name);
    //             $push->setMessage("قام بمتابعتك");
    //         } else if ($title == "LIKE_COMMENT") {
    //             $push->setTitle($name);
    //             $push->setMessage("اعجب بتعليقك");
    //         }else if ($title == "LIKE_REPLY") {
    //             $push->setTitle($name);
    //             $push->setMessage("اعجب علي ردك");
    //         }else if ($title == "REPLY_COMMENT") {
    //             $push->setTitle($name);
    //             $push->setMessage("رد علي تعليقك");
    //         }
    //         else if ($title == "LIKE_REPLY_Questions") {
    //             $push->setTitle($name);
    //              $push->setMessage("اعجب علي ردك");
    //         }
    //         else if ($title == "OFFER_PRICE") {
    //             $push->setTitle($name);
    //             $push->setMessage("ارسل اليك عرض");
    //         }
    //         $json = $push->getPush2();
    //         //$firebase->sendBG($regId, $json);
    //     }
    // }
}
