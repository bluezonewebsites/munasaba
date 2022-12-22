<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\UserBlocked;

class QuestionController extends ApiController
{
    public function getAllQuestionByUserid(Request $request)
    {
        $uid = $request['uid'];
        //Get Blocked User 
        $question = Question::where('uid', $uid)->get();
        return $this->apiResponse($request, trans('language.message'), $question, true);
    }
    public function getAllQuestion(Request $request)
    {
        $uid = $request['uid'];
        $country_id = $request['country_id'];
        $blocked_user=UserBlocked::where('from_uid',$uid)->first();
        $question = Question::where('country_id', $country_id)->where('uid','!=',$blocked_user)->get();
        return $this->apiResponse($request, trans('language.message'), $question, true);
    }

    public function searchQuestion(Request $request)
    {
        $keyword = $request['keyword'];
        $country_id = $request['country_id'];
        $question = Question::where('country_id', $country_id)->where('quest', 'LIKE', '%' . $keyword . '%')->get();
        return $this->apiResponse($request, trans('language.message'), $question, true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $question = Question::all();
        return view('question', compact('question'));
    }
}
