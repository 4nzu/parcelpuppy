<?php
/**
 * Created by PhpStorm.
 * User: a.khursanov
 * Date: 03/06/14
 * Time: 00:26
 */
use PayPal\Service\AdaptivePaymentsService;
use PayPal\Types\AP\ExecutePaymentRequest;
use PayPal\Types\Common\RequestEnvelope;
use PayPal\Types\AP\Receiver;
use PayPal\Types\AP\ReceiverList;
use PayPal\Types\AP\PayRequest;
use PayPal\Types\AP\PaymentDetailsRequest;
use PayPal\IPN\PPIPNMessage;
require('vendor/autoload.php');


class Paypal {

    private $sdkConfig;
    private $db;

    function __construct()
    {
        $this->sdkConfig = array(
            "mode" => PP_MODE,
            "acct1.UserName" => PP_USERNAME,
            "acct1.Password" => PP_PASSWORD,
            "acct1.Signature" => PP_SIGNATURE,
            "acct1.AppId" => PP_APPID
        );
        $this->db = DB::instance();

    }

    function StartPayment($embedded,$receiverEmail, $amount, $commission_percent, $description){


        $commissionAmount = $amount * $commission_percent/100;
        $receiver = array();
        $receiver[0] = new Receiver();
        $receiver[0]->amount = $amount;
        $receiver[0]->email = PP_RECEIVER_ACC;
        $receiver[0]->primary = "true";

        $receiver[1] = new Receiver();
        $receiver[1]->amount = $amount-$commissionAmount;
        $receiver[1]->email = $receiverEmail;
        $receiver[1]->primary = "false";
        $receiverList = new ReceiverList($receiver);

        $envelope = new RequestEnvelope("en_US");
        $payRequest = new PayRequest($envelope, 'PAY_PRIMARY', PP_CANCEL_URL, 'USD', $receiverList, PP_RETURN_URL);
        $payRequest->memo = $description;
        $payRequest->feesPayer = 'PRIMARYRECEIVER';
        $payRequest->trackingId = $this->generateTrackingId();
        $payRequest->ipnNotificationUrl = PP_IPN_URL;
        $payKey = $this->pay($payRequest);
        if(!$embedded){
            if(PP_MODE=='sandbox'){
                //var_dump($response);
                header("Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=".$payKey);

            }
            else{
                //change to live paypal URL
                header("Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=".$payKey);
            }
            exit;
        }
        else{
            return $payKey;
        }

    }

    function CompletePayment($pay_key){

        $service = new AdaptivePaymentsService($this->sdkConfig);
        $request = new ExecutePaymentRequest();
        $request->payKey=$pay_key;
        $envelope = new RequestEnvelope();
        $envelope->errorLanguage = "en_US";

        $request->requestEnvelope = $envelope;

        $response = $service->ExecutePayment($request);
        $response->payKey = $pay_key;
        //var_dump($response);
        if(strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
            return true;
        }
        else{
            return false;
        }

    }

    function IPNHandler($data){

        // first param takes ipn data to be validated. if null, raw POST data is read from input stream
        $ipnMessage = new PPIPNMessage(null, Configuration::getConfig());
        $data = $ipnMessage->getRawData();
        foreach($ipnMessage->getRawData() as $key => $value) {
            error_log("IPN: $key => $value");
        }

        if($ipnMessage->validate()) {
            error_log("Success: Got valid IPN data");
            $sql = "INSERT INTO paypal_messages SET message = ?";
            $this->db->query($sql, array(json_encode($data)));
            $this->update_payment_details($data['trackingId']);

        } else {
            error_log("Error: Got invalid IPN data");
        }


    }

    private function pay($request)
    {
        $service = new AdaptivePaymentsService($this->sdkConfig);
        $this->save_data($request);
        $response = $service->Pay($request);
        if(strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
            $response->trackingId = $request->trackingId;
            $this->save_data($response);
            return $response->payKey;
        }else{
            return false;
        }
    }

    private function generateTrackingId() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    private function save_data($data)
    {
       // var_dump($data);
        $sql = 'REPLACE INTO payments set pay_key = ?, tracking_id = ?, timestamp = ?, status = ? ';
        if($data->responseEnvelope->timestamp)
            $timestamp = $data->responseEnvelope->timestamp;
        else
            $timestamp = date("Y-m-d H:i:s");
        $this->db->execute($sql,
            array(
                $data->payKey,
                $data->trackingId,
                $timestamp,
                $data->paymentExecStatus));
    }

    private function update_payment_details($tracking_id)
    {
        $service = new AdaptivePaymentsService($this->sdkConfig);
        $request = new PaymentDetailsRequest();
        //$request->payKey=$pay_key;
        $request->trackingId = $tracking_id;
        $envelope = new RequestEnvelope();
        $envelope->errorLanguage = "en_US";
        $request->requestEnvelope = $envelope;
        $this->save_data($service->PaymentDetails($request));

    }
}