<?php
use Vonage\SMS\Message\SMS;
   function SendNotf($number,$code,$type='Signup'){
            //ResetPassword
            $text=__('word.welcome_app').' \n';
            $text.= __('word.'.$type,['code'=>$code]);
            $client = new Vonage\Client(new Vonage\Client\Credentials\Basic(env('API_KEY'), env('API_SECRET')));
            $text = new SMS($number,  env('BRAND_NAME','Monasbh'),  $text);
//            $text->setClientRef('test-message');
            $response = $client->sms()->send($text);
            $message = $response->current();
            return true;
    }
