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

    function SendSmsOut ($number,$code,$type='Signup'){

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'User-Agent' => 'Some Agent',
        ])->asForm()->Post('https://monasbh.multi-kw.com/serv.php',[
            'method'=>'send_sms_out',
            'mobile'=>$number,
            'code'=>$code ,
            "type"=>$type ,

        ]);

    }

    function SendTwilio ($number,$code,$type='Signup'){
        $receiverNumber = $number;
        $message=__('word.welcome_app').' \n';
        $message.= __('word.'.$type,['code'=>$code]);

        try {

            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");

            $client = new Twilio\Rest\Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message]);

            dd('SMS Sent Successfully.');

        } catch (Exception $e) {
            dd("Error: ". $e->getMessage());
        }
    }
