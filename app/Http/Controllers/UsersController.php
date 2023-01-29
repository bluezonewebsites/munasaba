<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Followimg;
use App\Models\FollowRing;
use App\Models\User;
use App\Models\UserBlocked;
use App\Models\UserRate;
use App\Models\UserReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Vonage\Laravel\Facade\Vonage;

class UsersController extends Controller
{

    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'name' => 'required',
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = is_array($validator->errors()->all()) ? $validator->errors()->all() : [$validator->errors()->all()];
            return $this->apiResponse($request, $errors, null, false, 500);
        }
        $user = User::where('mobile', $data['mobile'])->get();
        if (count($user) > 0) {
            return $this->apiResponse($request, trans('language.Existmobile'), null, false, 500);
        }
        try {
            DB::beginTransaction();
            $phone_code = rand(10000, 99999);
            $image_name=null;
            $folder = 'image/users/';
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $ext = $request->file('image')->extension();
                $name = time() . '.' . $ext;
                $image_name = 'users/' . $name;
                $name = public_path($folder) . '/' . $name;
                move_uploaded_file($image, $name);
            }
            $user = User::create([
                'name' => $data['name'],
                'username' => isset($data['username']) ? $data['username'] : null,
                'last_name' => isset($data['last_name']) ? $data['last_name'] : null,
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'country_id' => isset($data['country_id']) ? $data['country_id'] : 6,
                'city_id' => isset($data['city_id']) ? $data['city_id'] : null,
                'region_id' => isset($data['region_id']) ? $data['region_id'] : 6,
                'note' => isset($data['note']) ? $data['note'] : null,
                'regid' => isset($data['regid']) ? $data['regid'] : null,
                'remember_token' => Str::random(10),
                'pass' => Hash::make($request->password),
                'pass_v' => $request->password,
                'pic' => $image_name,
                'activation_code' => $phone_code,
            ]);
//            SendNotf($data['mobile'], $phone_code,'Signup');
            Auth::login($user);
            $item = auth()->user();
            $token = Auth::user()->createToken('Monasbah');
            $accessToken = $token->plainTextToken;
            $accessToken = $token->plainTextToken;

            $data=[
                'user'=>$user,
                'token'=>$accessToken
            ];

            DB::commit();
            return $this->apiResponse($request, trans('language.login'),$data, true);
        } catch (\Exception $e) {
            DB::rollback();
                //            return $e->getMessage();
            return $this->apiResponse($request, trans('language.same_error'), null, false,500);

        }
    }

    public function profile(Request $request)
    {
        $data['user'] = DB::table('user')
            ->leftjoin('countries', 'countries.id', 'user.country_id')
            ->leftjoin('regions', 'regions.id', 'user.region_id')
            ->leftjoin('cities', 'cities.id', 'user.city_id')
            ->leftjoin('prods', 'prods.uid', 'user.id')
            ->leftjoin('followings', 'followings.fid', 'user.id')
            ->leftjoin('followers', 'followers.uid', 'user.id')
            ->leftjoin('follow_ring', 'follow_ring.uid', 'user.id')
            ->leftjoin('user_rates', 'user_rates.uid', 'user.id')
            ->where('user.id', $request['id'])
            ->select(
                'user.*',
                DB::raw('COUNT(prods.uid) as numberOfProds'),
                DB::raw('COUNT(followings.uid) as Following'),
                DB::raw('COUNT(followers.uid) as Followers'),
                DB::raw('COUNT(user_rates.uid) as UserRate'),
                'countries.name_ar as countries_name_ar',
                'countries.name_en as countries_name_en',
                'cities.name_ar as cities_name_ar',
                'cities.name_en as cities_name_en',
                'regions.name_ar as regions_name_ar',
                'regions.name_en as regions_name_en',
            )->groupBy('user.id')
            ->first();
        $flag = 0;
        $fav = 0;
        if (isset($request['anther_user_id'])) {
            $follow = Follower::where('followers.uid', $request['id'])->where('followers.fid', $request['anther_user_id'])->get();
            $fav = FollowRing::where('follow_ring.uid', $request['id'])->where('follow_ring.fid', $request['anther_user_id'])->get();
            if ($follow) {
                $flag = 1;
            }
            if ($fav) {
                $fav = 1;
            }
        }
        $data['user']->is_follow = $flag;
        $data['user']->fav = $fav;
        return $this->apiResponse($request, trans('language.message'), $data['user'], true);
    }

    public function editProfile(Request $request)
    {
        $user = User::findOrFail($request['id']);
        if (!$user) {
            return $this->apiResponse($request, __('language.unauthenticated'), null, false, 500);
        }
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'country_id' => 'required|exists:countries,id',
        ]);
        if ($validator->fails()) {
            $errors = is_array($validator->errors()->all()) ? $validator->errors()->all() : [$validator->errors()->all()];
            return $this->apiResponse($request, $errors, null, false, 500);
        }
        $folder = 'image/users';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ext = $request->file('image')->extension();
            $name = time() . '.' . $ext;
            $img = 'users/' . $name;
            $name = public_path($folder) . '/' . $name;
            move_uploaded_file($image, $name);
            $user->pic = $img;
        }
        if ($request->hasFile('cover')) {
            $image = $request->file('cover');
            $ext = $request->file('cover')->extension();
            $name = time() . '.' . $ext;
            $cover_img = 'users/' . $name;
            $name = public_path($folder) . '/' . $name;
            move_uploaded_file($image, $name);
            $user->cover = $cover_img;
        }
        $user->name = isset($request->name) ? $request->name : $user->name;
        $user->last_name = isset($request->last_name) ? $request->last_name : $user->last_name;
        $user->username = isset($request->username) ? $request->username : $user->username;
        $user->email = isset($request->email) ? $request->email : $user->email;
        $user->mobile = isset($request->mobile) ? $request->mobile : $user->mobile;

        $user->country_id = isset($request->country_id) ? $request->country_id : $user->country_id;
        $user->city_id = isset($request->city_id) ? $request->city_id : $user->city_id;
        $user->region_id = isset($request->region_id) ? $request->region_id : $user->region_id;


        $user->bio = isset($request->bio) ? $request->bio : $user->bio;
        $user->note = isset($request->note) ? $request->note : $user->note;

        $user->save();
        return $this->apiResponse($request, trans('language.update_profile'), $user, true);
    }

    public function searchUsers(Request $request)
    {
        $keyword = $request['keyword'];
        $country_id = $request['country_id'];
        $uid = $request['uid'];
        $users = DB::table('user')
            ->leftjoin('regions', 'regions.id', 'user.region_id')
            ->leftjoin('cities', 'cities.id', 'user.city_id')
            ->leftjoin('followers', 'followers.uid', 'user.id');
        $users = $users->where(function ($query) use ($keyword) {
            $query->where('user.name', 'LIKE', '%' . $keyword . '%')
                ->OrWhere('user.last_name', 'LIKE', '%' . $keyword . '%');
        })->where('user.country_id', $country_id);
        $blocked_user = UserBlocked::where('from_uid', $uid)->first();
        if ($blocked_user) {
            $users = $users->where('user.uid', '!=', $blocked_user->to_uid);
        };
        $users = $users->select(
            'user.*',
            'cities.name_ar as cities_name_ar',
            'cities.name_ar as cities_name_ar',
            'regions.name_en as regions_name_en',
            'regions.name_en as regions_name_en'
        );
        $users = $users->paginate(10);
        $flag = 0;
        $fav = 0;
        if (isset($request['anther_user_id'])) {
            $follow = Follower::where('followers.uid', $request['id'])->where('followers.fid', $request['anther_user_id'])->get();
            $fav = FollowRing::where('follow_ring.uid', $request['id'])->where('follow_ring.fid', $request['anther_user_id'])->get();

            if ($follow) {
                $flag = 1;
            }
            if ($fav) {
                $fav = 1;
            }
        }
        $users->follow = $flag;
        $users->fav = $fav;

        return $this->apiResponse($request, trans('language.message'), $users, true);
        // ->get();
        // $flag=0;
        // if (isset($request['anther_user_id'])) {
        //     $follow = Follower::where('uid', $request['id'])->where('fid', $request['anther_user_id'])->get();
        //     if($follow){
        //         $flag=1;
        //     }
        // }
        // $users->is_follow=$flag;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = is_array($validator->errors()->all()) ? $validator->errors()->all() : [$validator->errors()->all()];
            return $this->apiResponse($request, $errors, null, false, 500);
        }

        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            return $this->apiResponse($request, __('language.not_ExistemailPhone'), null, false, 500);
        } else {
            $password = $request->password;
            if (Hash::check($password, $user->pass)) {
                Auth::login($user);
                $token = Auth::user()->createToken('Monasbah');
                $accessToken = $token->plainTextToken;
                return $this->sendResponse($request, trans('language.login'), $user, true, $accessToken, 200);
            } else {
                return $this->apiResponse($request, __('language.unauthenticated'), null, false, 500);
            }
        }
    }

    public function destroy(UserRate $userRate)
    {
        //
    }

    public function rateUser(Request $request)
    {
        $rate_user = UserRate::create([
            'uid' => $request['uid'],
            'user_rated_id' => $request['user_rated_id'],
            'rate' => isset($request['rate']) ? $request['rate'] : 0,
            'comment' => isset($request['comment']) ? $request['comment'] : null,
        ]);
        return $this->apiResponse($request, trans('language.created'), $rate_user, true);
    }

    public function getRateUser(Request $request)
    {
        $rate_user = DB::table('user_rates')
        ->leftjoin('user as rated_user', 'rated_user.id', 'user_rates.user_rated_id')
        ->leftjoin('user as from_user', 'from_user.id', 'user_rates.uid')
        ->where('user_rates.uid',$request['uid'])
        ->select(
        'user_rates.*',
        'rated_user.name as rated_user_name',
        'rated_user.last_name as rated_last_name',
        'rated_user.pic as rated_user_pic',
        'from_user.name as from_user_name',
        'from_user.last_name as from_last_name',
        'from_user.pic as from_user_pic'
    )->paginate(10);
    // $blocked_user = UserBlocked::where('from_uid', $uid)->first();
    // if ($blocked_user) {
    //     $users = $users->where('user.uid', '!=', $blocked_user->to_uid);
    // };
        return $this->apiResponse($request, trans('language.created'), $rate_user, true);
    }
    public function reportUser(Request $request)
    {
        $report_user = UserReport::create([
            'uid' => $request['uid'],
            'from_uid' => $request['from_uid'],
            'reson' => isset($request['reson']) ? $request['reson'] : null,
        ]);
        return $this->apiResponse($request, trans('language.created'), $report_user, true);
    }


    public function checkUser(Request $request) {

        $user = User::where('email' , $request->email)->orwhere('mobile' , $request->email)->first();
        if(!$user){
            return $this->apiResponse($request, __('language.not_ExistemailPhone'), null, false,500);
        }
        $user->activation_code= rand ( 1000 , 9999 );
        $user->save();
        $from=env('MAIL_FROM_ADDRESS');
        $data=[];
        $data["subject"] = __('language.Reset Password');
        $data["code"] = $user->activation_code;
        $data["name"] = $user->name;
        $data["email"] = $request->email;
//        $vonage = app('Vonage\Client');
//        dd($vonage);
//        $text = new \Vonage\SMS\Message\SMS("201009156765", 'Monasbh', 'Test SMS using Laravel');
//        $response = $vonage->sms()->send($text);

//        $message = $response->current();
//        dd($response);
//        if ($message->getStatus() == 0) {
//            echo "The message was sent successfully\n";
//        } else {
//            echo "The message failed with status: " . $message->getStatus() . "\n";
//        }
//
//
//        SendNotf($user->mobile, $data["code"],'ResetPassword');
        Mail::send('emails.resetPassword', $data, function ($message) use ($data, $from) {
            $message->from($from)->to($data["email"], $data["email"] )
                ->subject($data["subject"]);
        });
        return $this->apiResponse($request, trans('language.sendresetPassword'), $user->id, true);
    }
    public function checkCode(Request $request)
    {
        $validator = validator($request->all(), [
            'user_id' => 'required|integer',
            'code' => 'required|max:4',
        ]);

        if ($validator->fails()) {
            $errors = is_array($validator->errors()->all())?$validator->errors()->all():[$validator->errors()->all()];
            return $this->apiResponse($request, $errors, null, false,500);
        }

        $user = User::where('id', $request->user_id)->first();
        if(!$user){
            return $this->apiResponse($request, __('language.user_not_exist'), null, false,500);
        }

        if($user->activation_code ==  $request->code){
            return $this->apiResponse($request, trans('language.message'), $user, true);
        }
        return $this->apiResponse($request, __('language.activation code is incorrect'), null, false,500);

    }
    public function forgotPassword(Request $request) {

        $validator = \Validator::make($request->all(), [
            'user_id'  => 'required|integer|exists:user,id',
            'password' => 'required|string|min:3|max:255',
        ]);

        if ($validator->fails()) {
            $errors = is_array($validator->errors()->all())?$validator->errors()->all():[$validator->errors()->all()];
            return $this->apiResponse($request, $errors, null, false,500);
        }

        User::where('id',$request->user_id)->update([
            'pass' => bcrypt($request->password),
            'activation_code' => null,
            'pass_v' =>$request->password
        ]);
        return $this->apiResponse($request, trans('language.Password has been restored'), null, true);



    }

}
