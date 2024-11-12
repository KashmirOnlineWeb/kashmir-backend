<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $serviceURL = 'http://cloud.smsindiahub.in/vendorsms/pushsms.aspx';
    protected $sid    = 'AREPLY';
    protected $APIKey = '7OaWXe6a9E6VYi9HWQ66KA';
    protected $fl     = '0';
    protected $gwid   = '2';

    public function __construct()
    {
        
    }

    /**
     * Send text SMS
     * **/
    public function sendText($mobile = NULL, $text = NULL)
    {
        try {
            if(!is_null($mobile) AND !is_null($text)){
                $url = $this->serviceURL."?APIKey=".$this->APIKey."&msisdn=".$mobile."&sid=".$this->sid."&msg=".$text."&fl=".$this->fl."&gwid=".$this->gwid;

                $cSession = curl_init(); 
                curl_setopt($cSession,CURLOPT_URL,$url);
                curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($cSession,CURLOPT_HEADER, false); 
                $result = curl_exec($cSession);
                curl_close($cSession);
                $responseData = json_decode($result);

                $response = false;
                if(isset($responseData->JobId) AND !empty($responseData->JobId)){
                    $response = true;
                }
                return $response;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in sendText.');
        }
    }
    
}
