<?php 

namespace App;

use DB;
use App\Libraries\AfricasTalkingGateway as Gateway;

class SmsHandler{

    /*
    * @var api key
    */
    protected $apiKey;

    /*
    * @var username
    */
    protected $username;

    /*
    * The SENDER_ID
    * @var code
    */
    protected $code;

    /**
     * Constructor for smshandler
     *
     * @param api key     $apiKey
     * @param username      $username
     * @return $gateway object for sending sms
     */
    public function __construct($username = null, $apiKey = null, $code = null)
    {
        if($username == null && $apiKey == null) {
            $settings = DB::table('bulk_sms_settings')->first();
            if($username == null ) {
                $this->username = $settings->username;
            }
            if($apiKey == null ) {
                $this->apiKey = $settings->api_key;
            }
            if($code == null ) {
                $this->code = $settings->code;
            }
        //sms settings
        }
        else {
            $this->username = $username;
            $this->apiKey = $api_key;
            $this->code = $code;
        }
    }

    public function sendMessage($phone, $message, $logMessage = false)
    { 
        $result = false;
    	$from = $this->code;
        if($phone != null && strlen($phone) >= 9){
            $phone = ltrim($phone, '0');
            if(strpos($phone, "+") !== false){
                $recepient = $phone;
            }else{
                $recepient = "+254".$phone; //Default is Kenyan phone prefix
            }

            try {
                $gateway = new Gateway($this->username, $this->apiKey);
                if(env('ALLOW_SENDING_SMS', true)){
                    $result = $gateway->sendMessage($recepient, $message, $from);
                }
            } catch (AfricasTalkingGatewayException $e) {
                \Log::error("Error sending message: Recepient - $recepient, Message: $message");
                \Log::error("Reason: ".$e->getMessage());
            }
            if($logMessage)\Log::info("SRC: $from DST: $recepient MSG: $message");
        }
        return $result;
    }
}
