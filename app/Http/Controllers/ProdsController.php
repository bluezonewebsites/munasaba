<?php

namespace App\Http\Controllers;

use App\Models\Prod;
use App\Models\ProdRate;
use App\Models\Prods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Models\CommentOnProd;
use App\Models\FollowRing;
use App\Models\LikeOnCommentOnProd;
use App\Models\LikeOnProd;
use App\Models\ProdImage;
use App\Models\ProdReport;
use App\Models\ReplayOnComment;
use App\Models\UserBlocked;

class ProdsController extends ApiController
{
    public  function  __construct()
    {
        if(\request()->header('Authorization')){
            $this->middleware('auth:sanctum');
        }
    }
    public function getAllProdsByCountry(Request $request)
    {
        $prods = DB::table('prods')
            ->where('prods.country_id', $request['country_id'])
            ->leftjoin('countries', 'countries.id', 'prods.country_id')
            ->leftjoin('regions', 'regions.id', 'prods.region_id')
            ->leftjoin('cities', 'cities.id', 'prods.city_id')
            ->leftjoin('cats as main_cat', 'main_cat.id', 'prods.cat_id')
            ->leftjoin('cats as sub_cat', 'sub_cat.id', 'prods.sub_cat_id')
            ->leftjoin('prod_imgs', 'prod_imgs.prod_id', 'prods.id')
            ->leftjoin('prods_rates', 'prods_rates.prod_id', 'prods.id')
            ->leftjoin('user', 'user.id', 'prods.uid')
            ->select(
                'prods.*',
                'main_cat.name_ar as main_cat_name',
                'sub_cat.name_ar as sub_cat_name',
                'prod_imgs.img as prods_image',
                'prod_imgs.mtype',
                'user.name as user_name',
                'user.last_name as user_last_name',
                'user.verified as user_verified',
                'countries.name_ar as countries_name_ar',
                'countries.name_en as countries_name_en',
                'countries.currency_ar as countries_currency_ar',
                'countries.currency_en as countries_currency_en',
                'cities.name_ar as cities_name_ar',
                'cities.name_en as cities_name_en',
                'regions.name_ar as regions_name_ar',
                'regions.name_en as regions_name_en',
                DB::raw('COUNT(prods_rates.prod_id) as comments')
            )->groupBy('prods.id')->paginate(10);
        return $this->apiResponse($request, trans('language.message'), $prods, true);
    }
    public function getAllProdsById(Request $request)
    {
        $prods = DB::table('prods')
        ->where('prods.id',$request['id'])
            ->leftjoin('countries', 'countries.id', 'prods.country_id')
            ->leftjoin('regions', 'regions.id', 'prods.region_id')
            ->leftjoin('cities', 'cities.id', 'prods.city_id')
            ->leftjoin('cats as main_cat', 'main_cat.id', 'prods.cat_id')
            ->leftjoin('cats as sub_cat', 'sub_cat.id', 'prods.sub_cat_id')
            ->leftjoin('prod_imgs', 'prod_imgs.prod_id', 'prods.id')
            ->leftjoin('prods_rates', 'prods_rates.prod_id', 'prods.id')
            ->leftjoin('user', 'user.id', 'prods.uid')
            ->leftjoin('follow_ring', 'follow_ring.uid', 'prods.uid')

            ->select(
                'prods.*',
                'main_cat.name_ar as main_cat_name',
                'sub_cat.name_ar as sub_cat_name',
                'user.name as name',
                'user.username as user_name',
                'user.last_name as user_last_name',
                'user.verified as user_verified',
                'user.pic as user_pic',
                'countries.name_ar as countries_name_ar',
                'countries.name_en as countries_name_en',
                'countries.currency_ar as countries_currency_ar',
                'countries.currency_en as countries_currency_en',
                'cities.name_ar as cities_name_ar',
                'cities.name_en as cities_name_en',
                'regions.name_ar as regions_name_ar',
                'regions.name_en as regions_name_en',
                'prods_rates.comment as comment_on _prod',
                DB::raw('COUNT(prods_rates.prod_id) as comments_number')

            )->groupBy('prods.id')->first();
            $fav=0;
            if (isset($request['anther_user_id'])) {
                $fav=FollowRing::where('follow_ring.uid', $request['id'])->where('follow_ring.fid', $request['anther_user_id'])->get();
                if($fav){
                    $fav=1;
                }
            }
            $prods->fav=$fav;
            $prods->images=DB::table('prod_imgs')
            ->where('prod_id', $request['id'])
            ->select('prod_imgs.*',)->get();
            $prods->comments=ProdRate::where('prod_id', $request['id'])->get();

//                DB::table('prods_rates')
//            ->where('prod_id', $request['id'])
//            ->leftjoin('user', 'user.id', 'prods_rates.uid')
//            ->select('prods_rates.*',
//            'user.name as comment_user_name',
//            'user.last_name as comment_user_last_name',
//            'user.verified as comment_user_verified',
//            'user.pic as comment_user_pic',)->get();
        return $this->apiResponse($request, trans('language.message'), $prods, true);
    }


    public function getAllProdsByCatid(Request $request)
    {
        $cat_id = $request['cat_id'];
        $country_id = $request['country_id'];
        $prods = DB::table('prods')
        ->leftjoin('countries', 'countries.id', 'prods.country_id')
        ->leftjoin('regions', 'regions.id', 'prods.region_id')
        ->leftjoin('cities', 'cities.id', 'prods.city_id')
        ->leftjoin('cats as main_cat', 'main_cat.id', 'prods.cat_id')
        ->leftjoin('cats as sub_cat', 'sub_cat.id', 'prods.sub_cat_id')
        ->leftjoin('prods_rates', 'prods_rates.prod_id', 'prods.id')
        ->leftjoin('user', 'user.id', 'prods.uid')
        ->where('prods.country_id', $country_id)
        ->where('prods.cat_id', $cat_id)
            ->select(
                'prods.*',
                'main_cat.name_ar as main_cat_name',
                'sub_cat.name_ar as sub_cat_name',
                'user.name as user_name',
                'user.last_name as user_last_name',
                'user.verified as user_verified',
                'countries.name_ar as countries_name_ar',
                'countries.name_en as countries_name_en',
                'countries.currency_ar as countries_currency_ar',
                'countries.currency_en as countries_currency_en',
                'cities.name_ar as cities_name_ar',
                'cities.name_en as cities_name_en',
                'regions.name_ar as regions_name_ar',
                'regions.name_en as regions_name_en',
                DB::raw('COUNT(prods_rates.prod_id) as comments')
            )->groupBy('prods.id')->orderBy('prods.created_at', 'asc')
            ->paginate(10);
            $prods->images=DB::table('prod_imgs')
            ->leftjoin('prods', 'prods.id', 'prod_imgs.prod_id')
            ->select('prod_imgs.*',)->get();

        return $this->apiResponse($request, trans('language.message'), $prods, true);
    }



    public function getAllProdsByFilter(Request $request)
    {
        $country_id = $request['country_id'];
        $prods = DB::table('prods')
        ->leftjoin('countries', 'countries.id', 'prods.country_id')
        ->leftjoin('regions', 'regions.id', 'prods.region_id')
            ->leftjoin('cities', 'cities.id', 'prods.city_id')
            ->leftjoin('cats as main_cat', 'main_cat.id', 'prods.cat_id')
            ->leftjoin('cats as sub_cat', 'sub_cat.id', 'prods.sub_cat_id')
            ->leftjoin('prod_imgs', 'prod_imgs.prod_id', 'prods.id')
            ->leftjoin('prods_rates', 'prods_rates.prod_id', 'prods.id')
            ->leftjoin('user', 'user.id', 'prods.uid')
            ->where('prods.country_id', $country_id);
           $prods->select(
                'prods.*',
                'main_cat.name_ar as main_cat_name',
                'sub_cat.name_ar as sub_cat_name',
                'prod_imgs.img as prods_image',
                'prod_imgs.mtype',
                'user.name as user_name',
                'user.last_name as user_last_name',
                'user.verified as user_verified',
                'countries.name_ar as countries_name_ar',
                'countries.name_en as countries_name_en',
                'countries.currency_ar as countries_currency_ar',
                'countries.currency_en as countries_currency_en',
                'cities.name_ar as cities_name_ar',
                'cities.name_en as cities_name_en',
                'regions.name_ar as regions_name_ar',
                'regions.name_en as regions_name_en',
                DB::raw('COUNT(prods_rates.prod_id) as comments')
            )->groupBy('prods.id');
            if (isset($request['city_id'])) {
                $prods->where('prods.city_id', $request['city_id']);
            }
            if (isset($request['cat_id'])) {
                $prods->where('prods.cat_id', $request['cat_id']);
            }
            if (isset($request['sub_cat_id'])) {
                $prods->where('prods.sub_cat_id', $request['sub_cat_id']);
            }
            if (isset($request['tajeer_or_sell'])) {
                $prods->where('prods.tajeer_or_sell', $request['tajeer_or_sell']);
            }
            if (isset($request['high_price'])) {
                $prods->OrderBy('prods.price', 'DESC');
            }
            if (isset($request['low_price'])) {
                $prods->OrderBy('prods.price', 'ASC');
            }
            if (isset($request['newest'])) {
                $prods->OrderBy('prods.created_at', 'DESC');
            }
            $prods=$prods->paginate(10);
            // $prods->images=DB::table('prod_imgs')
            // ->leftjoin('prods', 'prods.id', 'prod_imgs.prod_id')
            // ->select('prod_imgs.*',)->get();
        return $this->apiResponse($request, trans('language.message'), $prods, true);
    }



    public function getAllProdsByUserid(Request $request)
    {
        $user_id = $request['uid'];
        $country_id = $request['country_id'];
        $prods = DB::table('prods')
        ->leftjoin('countries', 'countries.id', 'prods.country_id')
        ->leftjoin('regions', 'regions.id', 'prods.region_id')
        ->leftjoin('cities', 'cities.id', 'prods.city_id')
        ->leftjoin('cats as main_cat', 'main_cat.id', 'prods.cat_id')
        ->leftjoin('cats as sub_cat', 'sub_cat.id', 'prods.sub_cat_id')
        ->leftjoin('prod_imgs', 'prod_imgs.prod_id', 'prods.id')
        ->leftjoin('prods_rates', 'prods_rates.prod_id', 'prods.id')
        ->leftjoin('user', 'user.id', 'prods.uid')
        ->where('prods.country_id', $country_id)
        ->where('prods.uid', $user_id)
        ->select(
            'prods.*',
            'main_cat.name_ar as main_cat_name',
            'sub_cat.name_ar as sub_cat_name',
            'prod_imgs.img as prods_image',
            'prod_imgs.mtype',
            'user.name as user_name',
            'user.last_name as user_last_name',
            'user.verified as user_verified',
            'countries.name_ar as countries_name_ar',
            'countries.name_en as countries_name_en',
            'countries.currency_ar as countries_currency_ar',
            'countries.currency_en as countries_currency_en',
            'cities.name_ar as cities_name_ar',
            'cities.name_en as cities_name_en',
            'regions.name_ar as regions_name_ar',
            'regions.name_en as regions_name_en',
            DB::raw('COUNT(prods_rates.prod_id) as comments')
        )->groupBy('prods.id')->paginate(10);
        return $this->apiResponse($request, trans('language.message'), $prods, true);
    }



    public function searchProds(Request $request)
    {
        $keyword = $request['keyword'];
        $country_id = $request['country_id'];
        $uid = $request['uid'];
        $prods = DB::table('prods')
        ->leftjoin('countries', 'countries.id', 'prods.country_id')
        ->leftjoin('regions', 'regions.id', 'prods.region_id')
        ->leftjoin('cities', 'cities.id', 'prods.city_id')
        ->leftjoin('cats as main_cat', 'main_cat.id', 'prods.cat_id')
        ->leftjoin('cats as sub_cat', 'sub_cat.id', 'prods.sub_cat_id')
        ->leftjoin('prod_imgs', 'prod_imgs.prod_id', 'prods.id')
        ->leftjoin('prods_rates', 'prods_rates.prod_id', 'prods.id')
        ->leftjoin('user', 'user.id', 'prods.uid')
        ->where('prods.country_id', $country_id);
        $blocked_user = UserBlocked::where('from_uid', $uid)->first();
        if ($blocked_user) {
            $prods=$prods->where('prods.uid', '!=', $blocked_user->to_uid);
        }
        $prods = $prods->where(function ($query) use ($keyword) {
            $query->where('prods.name', 'LIKE', '%' . $keyword . '%')
            ->orWhere('prods.descr', 'LIKE', '%' . $keyword . '%');
        })->select(
            'prods.*',
            'main_cat.name_ar as main_cat_name',
            'sub_cat.name_ar as sub_cat_name',
            'prod_imgs.img as prods_images',
            'prod_imgs.mtype',
            'user.name as user_name',
            'user.last_name as user_last_name',
            'user.verified as user_verified',
            'countries.name_ar as countries_name_ar',
            'countries.name_en as countries_name_en',
            'countries.currency_ar as countries_currency_ar',
            'countries.currency_en as countries_currency_en',
            'cities.name_ar as cities_name_ar',
            'cities.name_en as cities_name_en',
            'regions.name_ar as regions_name_ar',
            'regions.name_en as regions_name_en',
            DB::raw('COUNT(prods_rates.prod_id) as comments')
        )->groupBy('prods.id')->paginate(10);

        return $this->apiResponse($request, trans('language.message'), $prods, true);
    }


    public function makeCommentOnProd(Request $request)
    {
        $comment_on_prod = CommentOnProd::create([
            'uid' => $request['uid'],
            'prod_id' => $request['prod_id'],
            'rating' => isset($request['rating']) ? $request['rating'] : 0,
            'comment' => isset($request['comment']) ? $request['comment'] : null,
        ]);
        return $this->apiResponse($request, trans('language.created'), $comment_on_prod, true);
    }

    public function makeReplayOnComment(Request $request)
    {
        $replay_on_comment = ReplayOnComment::create([
            'uid' => $request['uid'],
            'comment_id' => $request['comment_id'],
            'mention' => isset($request['mention']) ? $request['mention'] : '-',
            'comment' => isset($request['comment']) ? $request['comment'] : null,
        ]);
        return $this->apiResponse($request, trans('language.created'), $replay_on_comment, true);
    }

    public function getCommentsReplayProd(Request $request){
        $uid = $request['uid'];
        $comment= DB::table('likes_on_rates')
        ->leftjoin('user','user.id','likes_on_rates.uid')
        ->leftjoin('prods_rates','prods_rates.id','likes_on_rates.comment_id');        $blocked_user = UserBlocked::where('from_uid', $uid)->first();
        if ($blocked_user) {
            $comment=$comment->where('like_on_replay.uid', '!=', $blocked_user->to_uid);
        }
        $comment=$comment->select('likes_on_rates.*'
        ,'user.name as user_name'
        ,'user.pic as user_pic'
        ,'user.last_name as user_last_name'
        ,'user.verified as user_verified'
        ,'prods_rates.comment as replay_comment',
    )->groupBy('likes_on_rates.id');
        $comment=$comment->paginate(10);
        return $this->apiResponse($request, trans('language.message'), $comment, true);

    }

    public function makeLikeOnCommentOrReplayOnProd(Request $request)
    {
        //type == 1 -> like on comment
        //type ==0 ->  like on replay
        $like = LikeOnProd::where('uid', $request['uid'])
            ->where('comment_id', $request['comment_id'])
            ->where('like_type', $request['like_type'])
            ->first();
        if ($like) {
            $like->delete();
            return $this->apiResponse($request, trans('language.deleted'), null, true);
        } else {
            $like_on_prod = LikeOnProd::create([
                'uid' => $request['uid'],
                'comment_id' => $request['comment_id'],
                'like_type' => isset($request['like_type']) ? $request['like_type'] : 1,
            ]);
        }
        return $this->apiResponse($request, trans('language.created'), $like_on_prod, true);
    }


    public function makeReportOnProd(Request $request)
    {
        $reprt_prod = ProdReport::create([
            'uid' => $request['uid'],
            'prod_id' => $request['prod_id'],
            'reason' => isset($request['reason']) ? $request['reason'] : null,
        ]);
        return $this->apiResponse($request, trans('language.created'), $reprt_prod, true);
    }


    public function storeProd(Request $request)
    {
        $data = $request->all();
        try {

            DB::beginTransaction();
            $folder = 'image/prods/';
            if ($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $ext = $request->file('main_image')->extension();
                $name = time() . '.' . $ext;
                $image_name = 'prods/' . $name;
                $name = public_path($folder) . '/' . $name;
                move_uploaded_file($image, $name);
            }
            $prod = Prod::create([
                'cat_id' => $data['cat_id'],
                'sub_cat_id' => $data['sub_cat_id'],
                'uid' => $data['uid'],
                'name' => $data['name'],
                'price' => $data['price'],
                'loc' => isset($data['loc']) ? $data['loc'] : "",
                'country_id' => isset($data['country_id']) ? $data['country_id'] : 6,
                'city_id' => isset($data['city_id']) ? $data['city_id'] : "",
                'region_id' => isset($data['region_id']) ? $data['region_id'] : 6,
                'lat' => isset($data['lat']) ? $data['lat'] : 0,
                'lng' => isset($data['lng']) ? $data['lng'] : 0,
                'descr' => isset($data['descr']) ? $data['descr'] : "",
                'phone' => $data['phone'],
                'wts' => isset($data['wts']) ? $data['wts'] : "",
                'has_chat' => isset($data['has_chat']) ? $data['has_chat'] : 0,
                'has_wts' => isset($data['has_wts']) ? $data['has_wts'] : 0,
                'has_phone' => isset($data['has_phone']) ? $data['has_phone'] : 0,
                'amount' => $data['amount'],
                'color_name' => $data['color_name'],
                'tajeer_or_sell' => $data['tajeer_or_sell'],
                'duration_use' => isset($data['duration_use']) ? $data['duration_use'] : "",
                'prod_size' => isset($data['prod_size']) ? $data['prod_size'] : "",
                'sell_cost' => isset($data['sell_cost']) ? $data['sell_cost'] : "",
                'img' => isset($request['main_image']) ? $image_name : "",

            ]);
            if (isset($request['sub_image'])) {
                //$sub_images = explode("##", $request['sub_image']);
                foreach ($request->file('sub_image') as $k => $sub_image) {
                    $Slug_image = $k + 1 . '_' . $prod->id;
                    $image = $sub_image;
                    $type = $request['mtype'];
                    $ext = $sub_image->extension();
                    $name = 'sub_' . $Slug_image . time() . '.' . $ext;
                    $sub_image_name = 'prods/' . $name;
                    $name = public_path($folder) . '/' . $name;
                    move_uploaded_file($image, $name);
                    ProdImage::create([
                        'prod_id' => $prod->id,
                        'img' => $sub_image_name,
                        'mtype' => $type[$k],
                    ]);
                }
                // $name = str_replace("/home/bluezonekw/public_html/hunter/public/assets/adv/", "", $name);
                // $name = str_replace("https://", "", $name);
            }
            DB::commit();
            return $this->apiResponse($request, trans('language.prods_created'), $prod, true);
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
    public function updateProd(Request $request){
        $prod = Prod::find($request['id']);
        if(!$prod){
            return $this->apiResponse($request, __('language.ads_not_found'), null, false, 500);
        }
        $folder = 'image/prods/';
        if ($request->hasFile('main_image')) {
            $image = $request->file('main_image');
            $ext = $request->file('main_image')->extension();
            $name = time() . '.' . $ext;
            $image_name = 'prods/' . $name;
            $name = public_path($folder) . '/' . $name;
            move_uploaded_file($image, $name);
            $prod->pic = $image_name;
        }
        if (isset($request['sub_image'])) {
            foreach ($request->file('sub_image') as $k => $sub_image) {
                $Slug_image = $k + 1 . '_' . $prod->id;
                $image = $sub_image;
                $type = $request['mtype'];
                $ext = $sub_image->extension();
                $name = 'sub_' . $Slug_image . time() . '.' . $ext;
                $sub_image_name = 'prods/' . $name;
                $name = public_path($folder) . '/' . $name;
                move_uploaded_file($image, $name);
                $prod_imgs=ProdImage::where('img',$sub_image)->where('prod_id',$prod->id)->first();
                $prod_imgs->img=$sub_image_name;
                $prod_imgs->mtype= $type[$k];
            }
        }
        $prod->cat_id = isset($request->cat_id) ? $request->cat_id : $prod->cat_id;
        $prod->sub_cat_id = isset($request->sub_cat_id) ? $request->sub_cat_id : $prod->sub_cat_id;
        $prod->name = isset($request->name) ? $request->name : $prod->name;
        $prod->price = isset($request->price) ? $request->price : $prod->price;
        $prod->loc = isset($request->loc) ? $request->loc : $prod->loc;
        $prod->country_id = isset($request->country_id) ? $request->country_id : $prod->catcountry_id_id;
        $prod->city_id = isset($request->city_id) ? $request->city_id : $prod->city_id;
        $prod->region_id = isset($request->region_id) ? $request->region_id : $prod->region_id;
        $prod->lat = isset($request->lat) ? $request->lat : $prod->lat;
        $prod->lng = isset($request->lng) ? $request->lng : $prod->lng;
        $prod->descr = isset($request->descr) ? $request->descr : $prod->descr;
        $prod->phone = isset($request->phone) ? $request->phone : $prod->phone;
        $prod->wts = isset($request->wts) ? $request->wts : $prod->wts;
        $prod->has_chat = isset($request->has_chat) ? $request->has_chat : $prod->has_chat;
        $prod->has_wts = isset($request->has_wts) ? $request->has_wts : $prod->has_wts;
        $prod->has_phone = isset($request->has_phone) ? $request->has_phone : $prod->has_phone;
        $prod->amount = isset($request->amount) ? $request->amount : $prod->amount;
        $prod->color_name = isset($request->color_name) ? $request->color_name : $prod->color_name;
        $prod->duration_use = isset($request->duration_use) ? $request->duration_use : $prod->duration_use;
        $prod->tajeer_or_sell = isset($request->tajeer_or_sell) ? $request->tajeer_or_sell : $prod->tajeer_or_sell;
        $prod->prod_size = isset($request->prod_size) ? $request->prod_size : $prod->prod_size;
        $prod->sell_cost = isset($request->sell_cost) ? $request->sell_cost : $prod->sell_cost;
        $prod->save();
        $prods = DB::table('prods')
        ->where('prods.id',$request['id'])
        ->select('prods.*',)
        ->get();
        return $this->apiResponse($request, trans('language.update_profile'), $prods, true);

    }

    public function deleteProds(Request $request)
    {
        $prod = Prod::firstwhere('id',$request->id);
        if(!$prod){
            return $this->apiResponse($request, __('language.ads_not_found'), null, false, 500);
        }
        $prod_image=ProdImage::where('prod_id',$prod->id)->delete();
        $prod->delete();
        return $this->apiResponse($request, trans('language.deleted'), null, true);
    }
}
