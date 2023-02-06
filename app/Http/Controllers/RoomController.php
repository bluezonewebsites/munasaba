<?php

namespace App\Http\Controllers;

use App\Models\ChatReport;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class RoomController extends ApiController
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
    public function getRooms(Request $request)
    {
        $uid = Auth::id();
        $result = Room::wherehas('messages')->where(function ($query) use ($uid) {
            $query->where('user1',  $uid )
            ->orWhere('user2', $uid );
        })->where('user_delete_chat','!=', $uid)
        ->where('deleted', 0)->get();
        return $this->apiResponse($request, trans('language.message'), $result, true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $rid = $request->rid;
       $room= Room::Where(function ($query) use ($sid,$rid) {
            $query->where('user1',  $sid )
            ->Where('user2', $rid );
        })->orWhere(function ($query) use ($sid,$rid) {
            $query->where('user1',  $rid )
            ->Where('user2', $sid );
        })->first();

           //->where('deleted',0)->where('user_delete_chat','!=',$sid)->first();
        if(!$room){
           $room= Room::create([
                    'user1'=>$sid,
                    'user2'=>$rid,
                ]);
        }else{
            $room->user_delete_chat= $room->user_delete_chat== $sid? 0 : $room->user_delete_chat;
            $room->deleted= $room->user_delete_chat== $sid? 0 : $room->deleted;
        }

       return $this->apiResponse($request, trans('language.message'), $room, true);

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


    public function blockRoom(Request $request)
    {
        $id = $request->room_id;
        $uid = $request->uid;
        $room=Room::where('id',$id)->first();

        if(!$room){
            return $this->apiResponse($request, trans('not found'), [], true);

        }
        if ($room->user_make_block == 0) {
            $room->user_make_block=$uid;
            $room->save();
        } else if ($room->user_make_block == $uid) {
            $room->user_make_block=0;
            $room->save();
            return $this->apiResponse($request, trans('language.unblocked'), [], true);

        } else {
            return $this->apiResponse($request, trans('language.already_blocked'), null, false,500);

        }
        return $this->apiResponse($request, trans('language.blocked_'), [], true);
    }


    public function destroy(Request $request)
    {
        $id = $request->room_id;
        $uid = $request->uid;
        $room=Room::where('id',$id)->where('user_delete_chat','!=',0)->first();
        if ($room) {
            Room::where('id',$id)->update([
                'deleted'=>1
            ]);
            Room::where('id',$id)->delete();
            return $this->apiResponse($request, trans('language.deleted'), [], true);
        } else {
            Room::where('id',$id)->update([
                'user_delete_chat'=>$uid,
                'deleted'=>1
            ]);
            return $this->apiResponse($request, trans('language.deleted'), [], true);

        }
    }
     public function destroyAll(Request $request)
    {
        $id = $request->room_id;
        Room::wherein('id',$id)->delete();
        return $this->apiResponse($request, trans('language.deleted'), [], true);

    }
    public function reportRoom(Request $request)
    {
        $id = $request->room_id;
        $uid = Auth::id();
        $room=Room::where('id',$id)->first();

        if(!$room){
            return $this->apiResponse($request, trans('not found'), [], true);

        }

        $report=ChatReport::where('uid',$uid)->where('room_id',$id)->first();
         if($report){
             return $this->apiResponse($request, __('language.y_have_lready_reported'), $report, false, 500);
         }
        $report=ChatReport::create([
            'uid'=>$uid,
            'room_id'=>$id,
            'reason'=>$request->reason,
        ]);

        return $this->apiResponse($request, trans('language.y_reported'), $report, true);

    }


}
