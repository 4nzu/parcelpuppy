<?php
/**
 * Created by PhpStorm.
 * User: a.khursanov
 * Date: 03/06/14
 * Time: 00:26
 */
include('../../../Frameworks/PaypalAdaptivePayments/vendor/autoload.php');


class Paypal {

    private $sdkConfig;

    function __construct()
    {
        $this->sdkConfig = array(
            "mode" => PP_MODE,
            "acct1.UserName" => PP_USERNAME,
            "acct1.Password" => PP_PASSWORD,
            "acct1.Signature" => PP_SIGNATURE,
            "acct1.AppId" => PP_APPID
        );
    }

    function StartPayment($payeeEmail, $amount, $commission=0.5){


        $commissionAmount = $amount * $commission;
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
        $service->ExecutePayment($request);
        $this->get_payment_details($pay_key);

    }

    private function pay($request)
    {
        $service = new AdaptivePaymentsService($this->sdkConfig);
        $response = $service->Pay($request);
        if(strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
            if(PP_MODE=='sandbox')
            {
                $this->get_payment_details($response->payKey);
                exit;
                header("Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=".$response->payKey);
            }
            else
            {
                //change to live paypal URL
                header("Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=".$response->payKey);
            }
            exit;
        }else
        {
            var_dump($response);
        }
    }

    private function get_payment_details($pay_key)
    {
        $service = new AdaptivePaymentsService($this->sdkConfig);
        $request = new PaymentDetailsRequest();
        $request->payKey=$pay_key;
        $envelope = new RequestEnvelope();
        $envelope->errorLanguage = "en_US";
        $request->requestEnvelope = $envelope;
        call_user_func(array("API", "updatePaymentDetails"),$service->PaymentDetails($request));

//        $callback = $this->updateDetailsCallback;
//        if($callback)
//            $callback($service->PaymentDetails($request));
    }
}