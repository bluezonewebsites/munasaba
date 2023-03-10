<?php

namespace App\Http\Controllers;

use App\Models\ProdRate;
use App\Models\ReplyReport;
use App\Models\Question;
use App\Models\QuestionReport;
use App\Models\ReportOnComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\ApiController;
use App\Models\CommentOnProd;
use App\Models\CommentOnQuestion;
use App\Models\LikeOnQuest;
use App\Models\ReplayOnComment;
use App\Models\UserBlocked;

class QuestionController extends ApiController
{
    public  function  __construct()
    {
        if(\request()->header('Authorization')){
            $this->middleware('auth:sanctum');
        }
    }
    public function getAllQuestionByUserid(Request $request)
    {
        $uid = $request['uid'];
        $question = DB::table('questions')
            ->leftjoin('user','user.id','questions.uid')
            ->leftjoin('comment_on_questions','comment_on_questions.quest_id','questions.id')
            ->whereNull('questions.deleted_at')
            ->where('questions.uid',$uid)
            ->select('questions.*'
                ,'user.name as name'
                ,'user.pic as user_pic'
                ,'user.last_name as last_name'
                ,'user.verified as user_verified',
                DB::raw('COUNT(comment_on_questions.quest_id) as comments')
            )->groupBy('questions.id')
            ->latest('id')->paginate(10);
        return $this->apiResponse($request, trans('language.message'), $question, true);
    }
    public function getAllQuestionByCityid(Request $request)
    {
        $city_id = $request['city_id'];
        $question = DB::table('questions')
            ->leftjoin('user','user.id','questions.uid')
            ->leftjoin('comment_on_questions','comment_on_questions.quest_id','questions.id')
            ->where('questions.city_id', $city_id)
            ->whereNull('questions.deleted_at')
            ->select('questions.*'
                ,'user.name as name'
                ,'user.pic as user_pic'
                ,'user.last_name as last_name'
                ,'user.verified as user_verified',
                DB::raw('COUNT(comment_on_questions.quest_id) as comments')
            )->groupBy('questions.id');
        if (isset($request['uid'])) {
            $question->where('questions.uid', $request['uid']);
        }
        $question=$question->latest('id')->paginate(10);
        return $this->apiResponse($request, trans('language.message'), $question, true);
    }


    public function getAllQuestion(Request $request)
    {
        $uid = $request['uid'];
        $country_id = $request['country_id'];
        $question = DB::table('questions')
            ->leftjoin('user','user.id','questions.uid')
            ->leftjoin('comment_on_questions','comment_on_questions.quest_id','questions.id')
            ->where('questions.country_id', $country_id)
            ->whereNull('questions.deleted_at')
            ->select('questions.*'
                ,'user.name as name'
                ,'user.pic as user_pic'
                ,'user.last_name as last_name'
                ,'user.verified as user_verified',
                DB::raw('COUNT(comment_on_questions.quest_id) as comments')
            );
        $blocked_user = UserBlocked::where('from_uid', $uid)->first();
        if($blocked_user){
            $question->where('questions.uid', '!=', $blocked_user);
        }
        $question=$question->latest('id')->get();
        return $this->apiResponse($request, trans('language.message'), $question, true);
    }

    public function searchQuestion(Request $request)
    {
        $keyword = $request['keyword'];
//        $country_id = $request['country_id'];
        $uid = $request['uid'];
        $question = DB::table('questions')
            ->leftjoin('user','user.id','questions.uid')
            ->leftjoin('comment_on_questions','comment_on_questions.quest_id','questions.id');
        $blocked_user = UserBlocked::where('from_uid', $uid)->first();
        if ($blocked_user) {
            $question=$question->where('prods.uid', '!=', $blocked_user->to_uid);
        }
        $question=$question->where('questions.quest', 'LIKE', '%' . $keyword . '%')
//        ->where('questions.country_id', $country_id)
            ->whereNull('questions.deleted_at')
            ->select('questions.*'
                ,'user.name as name'
                ,'user.pic as user_pic'
                ,'user.last_name as last_name'
                ,'user.verified as user_verified',
                DB::raw('COUNT(comment_on_questions.quest_id) as comments')
            )->groupBy('questions.id');
        $question=$question->paginate(10);
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
        $question=Question::where('id',$request['quest_id'])->first();
        if($comment_on_question->mention != "-"){
            $this->save_notf('ASK_REPLY',$request['quest_id']
                ,'?????? ?????????? ?????? ????????????',$request['uid'],$question->uid);
        }
        else{
            $this->save_notf('ASK_REPLY',$request['quest_id']
                ,'?????? ?????????? ?????? ??????????',$request['uid'],$question->uid);
        }
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

            if($request['like_type'] == 0)
            {
                $created_by= CommentOnQuestion::where('id',$request['comment_id'])->first();
                if($created_by){

                    $this->save_notf('LIKE_REPLY_Questions',$created_by->quest_id
                        , '???????? ??????????????',$request['uid'], $created_by->uid);
                }
            }
//            else{
//                $created_by= CommentOnQuestion::where('id',$request['comment_id'])->first();
//                if($created_by){
//                    $created_by= $created_by->uid;
//                    $this->save_notf('LIKE_REPLY_Questions',$request['comment_id']
//                        , '???????? ?????? ??????',$request['uid'],$created_by);
//                }
//            }


        }


        return $this->apiResponse($request, trans('language.created'), $like_on_quest, true);
    }

    public function getQuestionsComments(Request $request){
        $uid = $request['uid'];
        $id = $request['id'];
        $like_type = $request['like_type'];

        $question= DB::table('questions')
            ->leftjoin('user','user.id','questions.uid')
            ->where('questions.id',$id)->select(
                'questions.*'
                ,'user.name as name'
                ,'user.pic as user_pic'
                ,'user.last_name as last_name',
                'user.verified as user_verified'
            )->first();
        $comment= CommentOnQuestion::where('quest_id',$id);

        $blocked_user = UserBlocked::where('from_uid', $uid)->first();
        if ($blocked_user) {
            $comment=$comment->where('comment_on_questions.uid', '!=', $blocked_user->to_uid);
        }
        $data['question']=$question;
        $data['comment']=$comment->paginate(10);;
        return $this->apiResponse($request, trans('language.message'), $data, true);

    }

    public function getCommentsReplayQuest(Request $request){
        $uid = $request['uid'];
        $comment= DB::table('like_on_replay')
            ->leftjoin('user','user.id','like_on_replay.uid')
            ->leftjoin('comment_on_questions','comment_on_questions.id','like_on_replay.comment_id');        $blocked_user = UserBlocked::where('from_uid', $uid)->first();
        if ($blocked_user) {
            $comment=$comment->where('like_on_replay.uid', '!=', $blocked_user->to_uid);
        }
        $comment=$comment->select('like_on_replay.*'
            ,'user.name as user_name'
            ,'user.pic as user_pic'
            ,'user.last_name as user_last_name'
            ,'user.verified as user_verified'
            ,'comment_on_questions.comment as replay_comment',
        )->groupBy('like_on_replay.id');
        $comment=$comment->latest('id')->paginate(10);
        return $this->apiResponse($request, trans('language.message'), $comment, true);

    }

    public function editQuestion(Request $request)
    {
        $question = Question::find($request['id']);
        if(!$question){
            return $this->apiResponse($request, __('language.question_not_found'), null, false, 500);
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
        $question=Question::firstwhere('id',$request->id);
        if(!$question){
            return $this->apiResponse($request, __('language.question_not_found'), null, false, 500);
        }
        $question->delete();
        return $this->apiResponse($request, trans('language.deleted'), null, true);
    }

    public function deleteCommentOnQuestions(Request $request)
    {

        $question=CommentOnQuestion::firstwhere('id',$request->id);
        if(!$question){
            return $this->apiResponse($request, __('not found'), null, false, 500);
        }
        $question->delete();
        return $this->apiResponse($request, trans('language.deleted'), null, true);

    }
    public function questionReports(Request $request)
    {

        //questions_reports //uid	q_id	reson	rdate
        $report=QuestionReport::where('uid',Auth::id())->where('q_id',$request->q_id)->first();
        if($report){
            return $this->apiResponse($request, __('language.y_have_lready_reported'), $report, false, 500);
        }
        $report=QuestionReport::create([
            'uid'=>Auth::id(),
            'q_id'=>$request->q_id,
            'reson'=>$request->reason,
        ]);
        return $this->apiResponse($request, trans('language.y_reported'), $report, true);
    }
    public function commentReports(Request $request)
    {

        //comment_reports //uid	comment_id	reason	rdate
        $report=ReportOnComment::where('uid',Auth::id())->
        where('comment_id',$request->comment_id)->first();

        if($report){
            return $this->apiResponse($request, __('language.y_have_lready_reported'), $report, false, 500);
        }
        $report=ReportOnComment::create([
            'uid'=>Auth::id(),
            'comment_id'=>$request->comment_id,
            'reason'=>$request->reason,
        ]);

        return $this->apiResponse($request, trans('language.y_reported'), $report, true);
    }

    public function replyReports(Request $request)
    {

        //comment_reports //uid	comment_id	reason	rdate
        $report=ReplyReport::where('uid',Auth::id())->
        where('comment_id',$request->reply_id)->first();

        if($report){
            return $this->apiResponse($request, __('language.y_have_lready_reported'), $report, false, 500);
        }
        $report=ReplyReport::create([
            'uid'=>Auth::id(),
            'comment_id'=>$request->reply_id,
            'reason'=>$request->reason,
        ]);

        return $this->apiResponse($request, trans('language.y_reported'), $report, true);
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
