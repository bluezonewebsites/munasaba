<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Models\User;
use App\Models\Room;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends ApiController
{
    public  function  __construct()
    {
        if(\request()->header('Authorization')){
            $this->middleware('auth:sanctum');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function chatByRoom(Request $request)
    {
        $id = $request->room_id;
        $uid = Auth::id();
         Message::where('room_id',$id)->where('rid',$uid)->update([
             'seen'=>1
             ]);
        $date['room'] = Room::where('id',$id)->first();
         if(! $date['room']){
            return $this->apiResponse($request, __('language.not_found'), null, false, 500);
        }
        $receiver_id = $date['room']->user1;
        if ($receiver_id == $uid) $receiver_id = $date['room']->user2;
        $date['receiver'] =User::whereId($receiver_id)
            ->select('name','last_name','pass_v','bio','cover','mobile','pic',
                'email','id','country_id','city_id','region_id','verified')
            ->first();
        $date['result'] =Message::where('room_id',$id)->latest('id')->paginate(10);
        return $this->apiResponse($request, trans('language.message'), $date, true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $sid = $_POST['sid'];
        $rid = $_POST['rid'];
        $mtype=$_POST['mtype'];
         if ($mtype == 'IMAGE') {
            $type=$_POST['mtype'];
            $imgs = explode("##", $_POST['msg']);
             //print_r($imgs);
            $room_id=$_POST['room_id'];
            for ($i = 0; $i < count($imgs); $i++) {
                insertData("msgs", "rid,sid,room_id,msg,mtype", "$rid,$sid,$room_id,'$imgs[$i]','$mtype'");
            }
        } else {
            insertData("msgs", getCols(), getValues());
        }
       // insertData("msgs", getCols(), getValues());
        //firebase($_POST['msg'], "chat", "user", "user", $rid, $sid);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sid = Auth::id();
        $rid = $request->reciver_id;
        $room_id=$request->room_id;
        $msg=$request->msg;
        $type=$request->mtype;
        $message=null;


            $room=Room::where('id',$room_id)->where(function ($query) use ($sid,$rid) {
              $query-> Where(function ($query) use ($sid,$rid) {
                    $query->where('user1',  $sid )
                    ->Where('user2', $rid );
                })->orWhere(function ($query) use ($sid,$rid) {
                    $query->where('user1',  $rid )
                    ->Where('user2', $sid );
                });

            })->first();

            if(!$room){
                return $this->apiResponse($request, __('language.not_found'), null, false, 500);
            }
        $folder = 'image/messages/';
         if ($type == 'IMAGE' || $type == 'LOCATION' ) {


             if (isset($request['images'])) {
                foreach ($request->file('images') as $k => $sub_image) {
                    $Slug_image = $k + 1 . '_' . $sid;
                    $image = $sub_image;
                    $ext = $sub_image->extension();
                    $name = 'sub_' . $Slug_image . time() . '.' . $ext;
                    $sub_image_name = 'messages/' . $name;
                    $name = public_path($folder) . '/' . $name;
                    move_uploaded_file($image, $name);
                     $message=Message::create([
                        'rid'=>$rid,
                        'sid'=>$sid,
                        'room_id'=>$room_id,
                        'msg'=>$msg ? '' : $msg,
                        'mtype'=>$type,
                        'image'=>$sub_image_name,

                    ]);
                }
             }
        }elseif($type == 'AUDIO' || $type == 'VIDEO'){
             foreach ($request->file('images') as $k => $sub_image) {
                 $Slug_image = $k + 1 . '_' . $sid;
                 $image = $sub_image;
                 $ext = $sub_image->extension();
                 $name = 'AUDIO_' . $Slug_image . time() . '.' . $ext;
                 $sub_image_name = 'messages/' . $name;
                 $name = public_path($folder) . '/' . $name;
                 move_uploaded_file($image, $name);
                 $message=Message::create([
                     'rid'=>$rid,
                     'sid'=>$sid,
                     'room_id'=>$room_id,
                     'msg'=>$msg ? '' : $msg,
                     'mtype'=>$type,
                     'image'=>$sub_image_name,

                 ]);
             }

         } else {
            $message=Message::create([
                        'rid'=>$rid,
                        'sid'=>$sid,
                        'room_id'=>$room_id,
                        'msg'=>$type == 'IMAGE' ? '' :$msg,
                        'mtype'=>$type,
                        'image'=>null,

                    ]);
        }
//
//
//             save_notf($rid,false, 'CHAT',$_POST['room_id'], $rid, $sid);
//
//

        return $this->apiResponse($request, trans('language.message'), $message, true);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        //
    }
}
