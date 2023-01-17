<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Followimg;
use App\Models\User;
use App\Models\UserBlocked;
use App\Models\UserRate;
use App\Models\UserReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

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
                'pic' => isset($request['image']) ? $image_name : null,
                'activation_code' => $phone_code,
            ]);
            Auth::login($user);
            $item = auth()->user();
            $token = Auth::user()->createToken('Monasbah');
            $accessToken = $token->plainTextToken;
            // dd($accessToken);

            DB::commit();
            return $this->apiResponse($request, trans('language.login'), $user, true);
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
            //return $this->apiResponse($request, trans('language.same_error'), null, false,500);

        }
    }

    public function profile(Request $request)
    {
        $user=User::withCount('prods')
        ->withCount('following')
        ->withCount('followers')
        ->withCount('userRate')
        ->with('country')
        ->with('city')
        ->with('region')
        ->findOrFail($request['id']);
        $flag=0;
        if (isset($request['anther_user_id'])) {
            $follow = Follower::where('uid', $request['id'])->where('fid', $request['anther_user_id'])->get();
            if($follow){
                $flag=1;
            }
        }
        $data=[
            'user'=>$user,
            'follow'=>$flag,
        ];
        return $this->apiResponse($request, trans('language.message'), $data, true);
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
        // $follow=Followimg::where();
        $blocked_user = UserBlocked::where('from_uid', $uid)->first();
        $users = User::where('country_id', $country_id)
        ->where('name', 'LIKE', '%' . $keyword . '%')
        ->with('city')
        ->where('last_name', 'LIKE', '%' . $keyword . '%');
        if($blocked_user){
            $users->where('id', '!=', $blocked_user);
        }
        $users=$users->get();
        $data=[
            'user'=>$users,
            'follow'=>0
        ];
        return $this->apiResponse($request, trans('language.message'), $data, true);
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
                //$token = $request->token;
                //$accessToken = PersonalAccessToken::findToken($token);
                return $this->sendResponse($request, trans('language.login'), $user, true, 200);
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

    public function reportUser(Request $request)
    {
        $report_user = UserReport::create([
            'uid' => $request['uid'],
            'from_uid' => $request['from_uid'],
            'reson' => isset($request['reson']) ? $request['reson'] : null,
        ]);
        return $this->apiResponse($request, trans('language.created'), $report_user, true);
    }
    
}
