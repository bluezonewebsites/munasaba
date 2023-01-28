<?php

   function SendNotf($number,$code,$type='Signup'){
            //ResetPassword
            $text=__('word.welcome_app').' \n';
            $text.= __('word.'.$type,['code'=>$code]);
            $endpoint = "https://rest.nexmo.com/sms/json";
            $client = new \GuzzleHttp\Client();
           $VONAGE_BRAND_NAME= env('BRAND_NAME','Monasbh');
           $VONAGE_API_KEY= env('API_KEY');
           $VONAGE_API_SECRET= env('API_SECRET');
           $response = \Illuminate\Support\Facades\Http::Post($endpoint,[
               'from'=>$VONAGE_BRAND_NAME,
               'text'=>$text,
               'to'=>"$number" ,
               "api_key"=>"$VONAGE_API_KEY" ,
               "api_secret"=>"$VONAGE_API_SECRET"

           ]);
            return true;
    }
