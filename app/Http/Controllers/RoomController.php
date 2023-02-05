<?php

namespace App\Http\Controllers;

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
        $result = Room::where(function ($query) use ($uid) {
            $query->where('user1',  $uid )
            ->orWhere('user2', $uid );
        })->where('user_delete_chat','<>', $uid)
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
        if(!$room){
           $room= Room::create([
                    'user1'=>$sid,
                    'user2'=>$rid,
                ]);
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
