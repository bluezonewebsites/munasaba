<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\ApiController;
use App\Models\CommentOnProd;
use App\Models\CommentOnQuestion;
use App\Models\LikeOnQuest;
use App\Models\ReplayOnComment;
use App\Models\UserBlocked;

class QuestionController extends ApiController
{
    public function getAllQuestionByUserid(Request $request)
    {
        $uid = $request['uid'];
        //Get Blocked User 
        $question = Question::where('uid', $uid)->with('user')->withCount('comments')->get();
        return $this->apiResponse($request, trans('language.message'), $question, true);
    }
    public function getAllQuestionByCityid(Request $request)
    {
        $city_id = $request['city_id'];
        //Get Blocked User 
        $question = Question::where('city_id', $city_id)->withCount('comments')->with('user')->get();
        return $this->apiResponse($request, trans('language.message'), $question, true);
    }

    
    public function getAllQuestion(Request $request)
    {
        $uid = $request['uid'];
        $country_id = $request['country_id'];
        $blocked_user = UserBlocked::where('from_uid', $uid)->first();        
        $question = Question::with('user')->withCount('comments')->where('country_id', $country_id);
        
        if($blocked_user){
            $question->where('uid', '!=', $blocked_user);
        }
        $question=$question->get();
        // $data=[
        //      'questions'=>$question,
        // ];
         return $this->apiResponse($request, trans('language.message'), $question, true);
    }

    public function searchQuestion(Request $request)
    {
        $keyword = $request['keyword'];
        $country_id = $request['country_id'];
        $uid = $request['uid'];
        $blocked_user = UserBlocked::where('from_uid', $uid)->first();
        
        $question = Question::with('user')->withCount('comments')->where('country_id', $country_id)->where('quest', 'LIKE', '%' . $keyword . '%');
        if($blocked_user){
            $question->where('uid', '!=', $blocked_user);
        }
        $question=$question->get();
        return $this->apiResponse($request, trans('language.message'), $question, true);
    }
    public function storeQuestion(Request $request)
    {
        $data = $request->all();
        try {
            DB::beginTransaction();
            $folder = 'image/questions/';
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $ext = $request->file('image')->extension();
                $name = time() . '.' . $ext;
                $image_name = 'questions/' . $name;
                $name = public_path($folder) . '/' . $name;
                move_uploaded_file($image, $name);
            }
            $question = Question::create([
                'uid' => $data['uid'],
                'quest' => $data['quest'],
                'country_id' => isset($data['country_id']) ? $data['country_id'] : 6,
                'city_id' => isset($data['city_id']) ? $data['city_id'] : null,
                'pic' => isset($request['image']) ? $image_name : null,
            ]);

            DB::commit();
            return $this->apiResponse($request, trans('language.quis_created'), $question, true);
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
            //return $this->apiResponse($request, trans('language.same_error'), null, false,500);

        }
    }
    public function makeCommentOnQuestion(Request $request)
    {
        $comment_on_question = CommentOnQuestion::create([
            'uid' => $request['uid'],
            'quest_id' => $request['quest_id'],
            'mention' => isset($request['mention']) ? $request['mention'] : '-',
            'comment' => isset($request['comment']) ? $request['comment'] : null,
        ]);
        return $this->apiResponse($request, trans('language.created'), $comment_on_question, true);
    }

    

    public function makeLikeOnCommentOrReplayOnQuestion(Request $request)
    {
        //type == 1 -> like on comment 
        //type ==0 ->  like on replay
        $like=LikeOnQuest::where('uid',$request['uid'])
        ->where('comment_id',$request['comment_id'])
        ->where('like_type',$request['like_type'])
        ->first();
        if($like){
            $like->delete();
            return $this->apiResponse($request,trans('language.deleted'), null, true);

        }else{
        $like_on_quest= LikeOnQuest::create([
            'uid' => $request['uid'],
            'comment_id' => $request['comment_id'],
            'like_type' => isset($request['like_type']) ? $request['like_type'] : 1,
        ]);
    }
        return $this->apiResponse($request, trans('language.created'), $like_on_quest, true);
    }

    public function editQuestion(Request $request)
    {
        $question = Question::findOrFail($request['id']);
        if (!$question) {
            return $this->apiResponse($request, __('language.unauthenticated'), null, false, 500);
        }
        $folder = 'image/questions';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ext = $request->file('image')->extension();
            $name = time() . '.' . $ext;
            $img = 'questions/' . $name;
            $name = public_path($folder) . '/' . $name;
            move_uploaded_file($image, $name);
            $question->pic = $img;
        }
        $question->quest = isset($request->quest) ? $request->quest : $question->quest;

        $question->country_id = isset($request->country_id) ? $request->country_id : $question->country_id;
        $question->city_id = isset($request->city_id) ? $request->city_id : $question->city_id;

        $question->save();
        return $this->apiResponse($request, trans('language.update_profile'), $question, true);
    }

    public function deleteQuestion(Request $request)
    {
        Question::findOrFail($request['id'])->delete();
        return $this->apiResponse($request, trans('language.deleted'), null, true);
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
