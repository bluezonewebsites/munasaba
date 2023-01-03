<?php

namespace App\Http\Controllers;

use App\Models\PendingUser;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PendingUserController extends ApiController
{
    public function pendingUser(Request $request)
    { {
            $data = $request->all();
            $validator = Validator::make($request->all(), [
                'uid' => 'required',
                'account_type' => 'required',
                'country_id' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = is_array($validator->errors()->all()) ? $validator->errors()->all() : [$validator->errors()->all()];
                return $this->apiResponse($request, $errors, null, false, 500);
            }
            try {
                DB::beginTransaction();

                $folder = 'image/users/pending';
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $ext = $request->file('image')->extension();
                    $name = time() . '.' . $ext;
                    $image_name = 'users/pending/' . $name;
                    $name = public_path($folder) . '/' . $name;
                    move_uploaded_file($image, $name);
                }
                $pending_user = PendingUser::create([
                    'uid' => $data['uid'],
                    'mobile' => isset($data['mobile']) ? $data['mobile'] : null,
                    'account_type' => $data['account_type'],
                    'category' => $data['category'],
                    'document_type' => $data['document_type'],
                    'country_id' => isset($data['country_id']) ? $data['country_id'] : null,
                    'note' => isset($data['note']) ? $data['note'] : null,
                    'pic' => ($image_name) ? $image_name : null,
                ]);
                DB::commit();
                return $this->apiResponse($request, trans('language.created'), $pending_user, true);
            } catch (\Exception $e) {
                DB::rollback();
                return $e->getMessage();
            }
        }
    }
}
