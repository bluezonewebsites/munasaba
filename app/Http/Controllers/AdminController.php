<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AppHelp;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function about(){
        $about=AppHelp::all();
        return $this->apiResponse(null, trans('language.created'), $about, true);
    }
    public function contactUs(Request $request){
        $contact=ContactUs::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'descr' => $request['descr'] ,
        ]);
        return $this->apiResponse($request, trans('language.created'), $contact, true);
    }
}
