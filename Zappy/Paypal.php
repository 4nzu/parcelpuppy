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

    function StartPayment($payeeEmail, $amount, $commission_percent=5){


        $commissionAmount = $amount * $commission_percent/100;
        $receiver = array();
        $receiver[0] = new Receiver();
        $receiver[0]->amount = $amount;
        $receiver[0]->email = PP_RECEIVER_ACC;
        $receiver[0]->primary = "true";

        $receiver[1] = new Receiver();
        $receiver[1]->amount = $amount-$commissionAmount;
        $receiver[1]->email = $payeeEmail;
        $receiver[1]->primary = "false";
        $receiverList = new ReceiverList($receiver);

        $payRequest = new PayRequest(new RequestEnvelope("en_US"), 'PAY_PRIMARY', PP_CANCEL_URL, 'USD', $receiverList, PP_RETURN_URL);

        $payRequest->feesPayer = 'PRIMARYRECEIVER';
        $this->pay($payRequest);

    }

    function CompletePayment($pay_key){

        $service = new AdaptivePaymentsService($this->sdkConfig);
        $request = new ExecutePaymentRequest();
        $request->payKey=$pay_key;
        $envelope = new RequestEnvelope();
        $envelope->errorLanguage = "en_US";
        $request->requestEnvelope = $envelope;
        $response = $service->ExecutePayment($request);
        var_dump($response);
        if(strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
            $this->save_data($response);
        }
        else{
            //error
        }
        //$this->get_payment_details($pay_key);
    }

    function IPNHandler($data){
        header('HTTP/1.1 200 OK');
        $item_name        = $data['item_name'];
        $item_number      = $data['item_number'];
        $payment_status   = $data['payment_status'];
        $payment_amount   = $data['mc_gross'];
        $payment_currency = $data['mc_currency'];
        $txn_id           = $data['txn_id'];
        $receiver_email   = $data['receiver_email'];
        $payer_email      = $data['payer_email'];
        // Build the required acknowledgement message out of the notification just received
        $req = 'cmd=_notify-validate';               // Add 'cmd=_notify-validate' to beginning of the acknowledgement

        foreach ($data as $key => $value) {         // Loop through the notification NV pairs
            $value = urlencode(stripslashes($value));  // Encode these values
            $req  .= "&$key=$value";                   // Add the NV pairs to the acknowledgement
        }

        // Set up the acknowledgement request headers
        $header  = "POST /cgi-bin/webscr HTTP/1.1\r\n";                    // HTTP POST request
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

        // Open a socket for the acknowledgement request
        if(PP_MODE=='sandbox'){
            $fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
        }
        else{
            $fp = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30);
        }
        // Send the HTTP POST request back to PayPal for validation
        fputs($fp, $header . $req);
        while (!feof($fp)) {                     // While not EOF
            $res = fgets($fp, 1024);               // Get the acknowledgement response
            if (strcmp ($res, "VERIFIED") == 0) {  // Response contains VERIFIED - process notification

                $sql = "INSERT INTO paypal_messages SET message = ?";
                $this->db->query($sql, array(json_encode($data)));
            }
            else if (strcmp ($res, "INVALID") == 0) {
                //Notify

            }
        }
        fclose($fp);  // Close the file
    }

    private function pay($request)
    {
        $service = new AdaptivePaymentsService($this->sdkConfig);
        $response = $service->Pay($request);
        if(strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
            $this->save_data($response);
            if(PP_MODE=='sandbox'){
                //var_dump($response);
                header("Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=".$response->payKey);

            }
            else{
                //change to live paypal URL
                header("Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=".$response->payKey);

            }
            exit;
        }else{
//            var_dump($response);
        }
    }

    private function save_data($response)
    {
        $sql = 'REPLACE INTO payments set correlation_id = ? , pay_key = ?, timestamp = ?, status = ? ';
        $this->db->execute($sql,
            array($response->responseEnvelope->correlationId,
                $response->payKey,
                $response->responseEnvelope->timestamp,
                $response->paymentExecStatus));
    }

    private function get_payment_details($pay_key)
    {
        $service = new AdaptivePaymentsService($this->sdkConfig);
        $request = new PaymentDetailsRequest();
        $request->payKey=$pay_key;
        $envelope = new RequestEnvelope();
        $envelope->errorLanguage = "en_US";
        $request->requestEnvelope = $envelope;
        $this->save_data($service->PaymentDetails($request));

    }
}