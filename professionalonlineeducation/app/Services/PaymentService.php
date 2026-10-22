<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Seminar;
use Illuminate\Http\Request;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use App\Models\PaymentLogs;
use App\Http\Helpers\General\Helper;

class PaymentService
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('auth'); // later enable it when needed user login while payment
    }
    public function getCoursesNames($cart,$returnType='array'){
        if ($returnType === 'string'){
            $string = '';
            $courses = Lesson::whereIn('id',$cart)->pluck('title');
            $i = 0;
            foreach ($courses as $course){
                $i++;
                $string .= $course;
                if (count($courses) != $i){ $string .= ', '; }
            }
            return  $string;
        }
        return Lesson::whereIn('id',$cart)->pluck('title');
    }

    public function handleseminaronlinepay($seminar,$total,$request,$code=0) {
        $input = $request->input();
        $amount = (string)$total;
        $user = auth()->user();
        $transaction_id = '';
        $billingEmail = Helper::resolveBillingEmail($request, $user);

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
   $merchantAuthentication->setName('2mnNj83Be2N');
        $merchantAuthentication->setTransactionKey('46Fh7x8T8Ew946BJ');

        $refId = 'ref' . time();
        $cardNumber = preg_replace('/\s+/', '', $input['cardNumber']);

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($input['expiration-year'] . "-" .$input['expiration-month']);
        $creditCard->setCardCode($input['cvv']);

        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        $order = new AnetAPI\OrderType();

        $order->setDescription(function_exists('mb_substr') ? mb_substr($seminar->paymentHistoryDescription(), 0, 240) : substr($seminar->paymentHistoryDescription(), 0, 240));

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setOrder($order);


        $cust = new AnetAPI\CustomerAddressType();

        $cust->setFirstName($request->first_name);
        $cust->setLastName($request->last_name);
        $cust->setAddress($request->address);
        $cust->setCity($request->city);
        $cust->setState($request->state_id);
        $cust->setZip($request->zipcode);
        $cust->setPhoneNumber($request->phone);
        $cust->setEmail($billingEmail);
        $transactionRequestType->setBillTo($cust);

        $cust = new AnetAPI\CustomerAddressType();
        $cust->setFirstName($request->first_name);
        $cust->setLastName($request->last_name);
        $cust->setAddress($request->address);
        $cust->setCity($request->city);
        $cust->setState($request->state_id);
        $cust->setZip($request->zipcode);
        $cust->setPhoneNumber($request->phone);
        $cust->setEmail($billingEmail);
        $transactionRequestType->setShipTo($cust);




        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($user->id);
        $customerData->setEmail($billingEmail);
        $transactionRequestType->setCustomer($customerData);

//        dd($transactionRequestType);
//        $cust = new AnetAPI\CustomerDataType();
//        $cust->setType("individual");
//        $cust->setEmail($user->email);
//        $cust->setPhoneNumber($user->phone);
//        $transactionRequestType->setCustomer($cust);

        $userLicences = $user->linceses;
        if (count($userLicences)){

            $i = 0;
            foreach ($userLicences as $userLicence){
                $i++;
                $UserFieldType = new AnetAPI\UserFieldType();
                $UserFieldType->setName('licence_'.$i);
                $UserFieldType->setValue($userLicence->licence_no);
                $transactionRequestType->addToUserFields($UserFieldType);
            }
        }


        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('Name on Card');
        $UserFieldType->setValue($request->owner);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User Name');
        $UserFieldType->setValue($user->FullName);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('Receipt Email');
        $UserFieldType->setValue($billingEmail);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User Email');
        $UserFieldType->setValue($user->email);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User Phone');
        $UserFieldType->setValue($user->phone);
        $transactionRequestType->addToUserFields($UserFieldType);


        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User City');
        $UserFieldType->setValue($user->city);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User State');
        $UserFieldType->setValue($user->state);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User ZipCode');
        $UserFieldType->setValue($user->zipcode);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User Address');
        $UserFieldType->setValue($user->address);
        $transactionRequestType->addToUserFields($UserFieldType);

        $requests = new AnetAPI\CreateTransactionRequest();
        $requests->setMerchantAuthentication($merchantAuthentication);
        $requests->setRefId($refId);
        $requests->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($requests);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
//dd($response);
        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
//                    echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
//                    echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
//                    echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
//                    echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
//                    echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";
                    $message_text = $tresponse->getMessages()[0]->getDescription().", Transaction ID: " . $tresponse->getTransId();
                    $msg_type = "success_msg";
                    $transaction_id = $tresponse->getTransId();
                     PaymentLogs::create([
                         'amount' => $amount,
                         'user_id'=>auth()->user()->id,
                         'response_code' => $tresponse->getResponseCode(),
                         'transaction_id' => $tresponse->getTransId(),
                         'auth_id' => $tresponse->getAuthCode(),
                         'message_code' => $tresponse->getMessages()[0]->getCode(),
                         'name_on_card' => trim($input['owner']),
                         'courses'=>$seminar->id,
                         'code'=>$code,
                         'type'=>'seminar',
                         'quantity'=>1
                     ]);
                } else {
                    $message_text = 'There were some issue with the payment. Please try again later.';
                    $msg_type = "error_msg";

                    if ($tresponse->getErrors() != null) {
                        $message_text = $tresponse->getErrors()[0]->getErrorText();
                        $msg_type = "error_msg";
                    }
                }
                // Or, print errors if the API request wasn't successful
            } else {
                $message_text = 'There were some issue with the payment. Please try again later.';
                $msg_type = "error_msg";

                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $message_text = $tresponse->getErrors()[0]->getErrorText();
                    $msg_type = "error_msg";
                } else {
                    $message_text = $response->getMessages()->getMessage()[0]->getText();
                    $msg_type = "error_msg";
                }
            }
        } else {
            $message_text = "No response returned";
            $msg_type = "error_msg";
        }
        return [
            'transaction_id'=>$transaction_id,
            'msgType' => $msg_type,
            'text'=>$message_text];
    }


    public function handleonlinepay(Request $request, $amount, $cart, $code = 0, $seminarCartIds = null)
    {
        $cart = is_array($cart) ? array_values(array_filter($cart)) : [];
        $seminarCartIds = is_array($seminarCartIds) ? array_values(array_filter($seminarCartIds)) : [];

        $coursesNames = count($cart) ? $this->getCoursesNames($cart, 'string') : '';
        if (count($seminarCartIds)) {
            $seminarLabels = Seminar::whereIn('id', $seminarCartIds)->get()->map(function (Seminar $s) {
                return $s->paymentHistoryDescription();
            })->implode(', ');
            $coursesNames = $coursesNames === '' ? $seminarLabels : $coursesNames.' | '.$seminarLabels;
        }
        $user = auth()->user();
        $transaction_id = '';
        $billingEmail = Helper::resolveBillingEmail($request, $user);
//        dd($request->all());
        $amount = ($amount < 1)?1:$amount;
        $input = $request->input();
        $amount = round($amount);
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        // $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        // $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        $merchantAuthentication->setName('2mnNj83Be2N');
        $merchantAuthentication->setTransactionKey('46Fh7x8T8Ew946BJ');
//        $merchantAuthentication->setName('2YBmgY33V');
//        $merchantAuthentication->setTransactionKey('6267YRy5zS5M5Eym');


        $refId = 'ref' . time();
        $cardNumber = preg_replace('/\s+/', '', $input['cardNumber']);

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($input['expiration-year'] . "-" .$input['expiration-month']);
        $creditCard->setCardCode($input['cvv']);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        $order = new AnetAPI\OrderType();

        $order->setDescription(function_exists('mb_substr') ? mb_substr($coursesNames, 0, 240) : substr($coursesNames, 0, 240));
// dd($amount);
        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setOrder($order);
//        dd($transactionRequestType);
        $cust = new AnetAPI\CustomerAddressType();

        $cust->setEmail($billingEmail);

        $cust->setFirstName($request->first_name);
        $cust->setLastName($request->last_name);
        $cust->setAddress($request->address);
        $cust->setCity($request->city);
        $cust->setState($request->state_id);
        $cust->setZip($request->zipcode);
        $cust->setPhoneNumber($request->phone);
        $transactionRequestType->setBillTo($cust);

        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($user->id);
        $customerData->setEmail($billingEmail);
        $transactionRequestType->setCustomer($customerData);


        $userLicences = $user->linceses;
        if (count($userLicences)){
            $i = 0;
            foreach ($userLicences as $userLicence){
                $i++;
                $UserFieldType = new AnetAPI\UserFieldType();
                $UserFieldType->setName('licence_'.$i);
                $UserFieldType->setValue($userLicence->licence_no);
                $transactionRequestType->addToUserFields($UserFieldType);
            }
        }


        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('Name on Card');
        $UserFieldType->setValue($request->owner);
        $transactionRequestType->addToUserFields($UserFieldType);


        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User Name');
        $UserFieldType->setValue($user->FullName);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('Receipt Email');
        $UserFieldType->setValue($billingEmail);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User Email');
        $UserFieldType->setValue($user->email);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User Phone');
        $UserFieldType->setValue($user->phone);
        $transactionRequestType->addToUserFields($UserFieldType);


        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User City');
        $UserFieldType->setValue($user->city);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User State');
        $UserFieldType->setValue($user->state);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User ZipCode');
        $UserFieldType->setValue($user->zipcode);
        $transactionRequestType->addToUserFields($UserFieldType);

        $UserFieldType = new AnetAPI\UserFieldType();
        $UserFieldType->setName('User Address');
        $UserFieldType->setValue($user->address);
        $transactionRequestType->addToUserFields($UserFieldType);

        


        // Assemble the complete transaction request
        $requests = new AnetAPI\CreateTransactionRequest();
        $requests->setMerchantAuthentication($merchantAuthentication);
        $requests->setRefId($refId);
        $requests->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($requests);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        
        if ($response != null) {
            
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {

                    $message_text = $tresponse->getMessages()[0]->getDescription().", Transaction ID: " . $tresponse->getTransId();
                    $msg_type = "success_msg";
                    $transaction_id = $tresponse->getTransId();
                    $hasSeminars = count($seminarCartIds) > 0;
                    $hasCourses = count($cart) > 0;
                    if ($hasSeminars && $hasCourses) {
                        $coursesField = json_encode(['lessons' => $cart, 'seminars' => $seminarCartIds]);
                        $logType = 'unified';
                    } elseif ($hasSeminars) {
                        $coursesField = json_encode(['seminars' => $seminarCartIds]);
                        $logType = 'seminar_cart';
                    } else {
                        $coursesField = json_encode($cart);
                        $logType = 'course';
                    }
                    PaymentLogs::create([
                        'amount' => $amount,
                        'user_id'=>auth()->user()->id,
                        'first_name'=>$request->first_name,
                        'last_name'=>$request->last_name,
                        'address'=>$request->address,
                        'city'=>$request->city,
                        'state'=>$request->state,
                        'zipcode'=>$request->zipcode,
                        'phone'=>$request->phone,
                        'email'=>$request->email,
                        'response_code' => $tresponse->getResponseCode(),
                        'transaction_id' => $tresponse->getTransId(),
                        'auth_id' => $tresponse->getAuthCode(),
                        'message_code' => $tresponse->getMessages()[0]->getCode(),
                        'name_on_card' => trim($input['owner']),
                        'courses'=>$coursesField,
                        'code'=>$code,
                        'type'=>$logType,
                        'quantity'=>1
                    ]);
                } else {
                    $message_text = 'There were some issue with the payment. Please try again later.';
                    $msg_type = "error_msg";

                    if ($tresponse->getErrors() != null) {
                        $message_text = $tresponse->getErrors()[0]->getErrorText();
                        $msg_type = "error_msg";
                    }
                }
                // Or, print errors if the API request wasn't successful
            } else {
                
                $message_text = 'There were some issue with the payment. Please try again later.';
                $msg_type = "error_msg";

                $tresponse = $response->getTransactionResponse();
                
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $message_text = $tresponse->getErrors()[0]->getErrorText();
                    $msg_type = "error_msg";
                } else {
                    // $message_text = $response->getMessages()->getMessage()[0]->getText();
                    $message_text = 'Given card details are invalid';
                    $msg_type = "error_msg";
                }
            }
        } else {
            $message_text = "No response returned";
            $msg_type = "error_msg";
        }
        return [
            'transaction_id'=>$transaction_id,
            'msgType' => $msg_type,
            'text'=>$message_text];
    }
    public function handlewithoutpay(Request $request,$amount,$cart){

        $input = $request->input();
        PaymentLogs::create([
            'amount' => $amount,
            'user_id'=>auth()->user()->id,
            'response_code' => '',
            'transaction_id' => '',
            'auth_id' => '',
            'message_code' => '',
            'name_on_card' => isset($input['owner']) ? trim($input['owner']) : '',
            'courses'=>json_encode($cart),
            'type'=>'course',
            'quantity'=>1
        ]);
        return [
            'msgType' => 'success_msg',
            'text'=>'Payment Success'
        ];

    }

    public function addCourses($logs){
        $paymentlogs = [];

        foreach ($logs as $log){
            [$lessonIds, $seminarIds] = $this->parseLessonAndSeminarIds($log);
            $courses = count($lessonIds) ? Lesson::whereIn('id', $lessonIds)->get() : collect();
            $seminars = count($seminarIds) ? Seminar::whereIn('id', $seminarIds)->get() : collect();
            $paymentlogs[] = [
                'log' => $log,
                'courses' => $courses,
                'seminars' => $seminars,
            ];
        }

        return $paymentlogs;
    }

    /**
     * @return array{0: int[], 1: int[]}
     */
    public function parseLessonAndSeminarIds(PaymentLogs $log): array
    {
        $lessonIds = [];
        $seminarIds = [];
        $type = (string) ($log->type ?? 'course');
        $raw = $log->courses;

        if ($type === 'unified') {
            $decoded = $this->decodeCoursesPayload($raw);
            if (is_array($decoded)) {
                if (! empty($decoded['lessons']) && is_array($decoded['lessons'])) {
                    $lessonIds = array_values(array_filter(array_map('intval', $decoded['lessons'])));
                }
                if (! empty($decoded['seminars']) && is_array($decoded['seminars'])) {
                    $seminarIds = array_values(array_filter(array_map('intval', $decoded['seminars'])));
                }
            }

            return [$lessonIds, $seminarIds];
        }

        if ($type === 'seminar_cart') {
            $decoded = $this->decodeCoursesPayload($raw);
            if (is_array($decoded) && ! empty($decoded['seminars']) && is_array($decoded['seminars'])) {
                $seminarIds = array_values(array_filter(array_map('intval', $decoded['seminars'])));
            }

            return [$lessonIds, $seminarIds];
        }

        if ($type === 'seminar') {
            if (is_numeric($raw) && (int) $raw > 0) {
                return [$lessonIds, [(int) $raw]];
            }
            $decoded = $this->decodeCoursesPayload($raw);
            if (is_array($decoded) && ! empty($decoded['seminars']) && is_array($decoded['seminars'])) {
                $seminarIds = array_values(array_filter(array_map('intval', $decoded['seminars'])));
            }

            return [$lessonIds, $seminarIds];
        }

        $decoded = $this->decodeCoursesPayload($raw);
        if (is_array($decoded)) {
            if ($this->isSequentialIntList($decoded)) {
                $lessonIds = array_values(array_filter(array_map('intval', $decoded)));
            } elseif (isset($decoded['lessons']) || isset($decoded['seminars'])) {
                if (! empty($decoded['lessons']) && is_array($decoded['lessons'])) {
                    $lessonIds = array_values(array_filter(array_map('intval', $decoded['lessons'])));
                }
                if (! empty($decoded['seminars']) && is_array($decoded['seminars'])) {
                    $seminarIds = array_values(array_filter(array_map('intval', $decoded['seminars'])));
                }
            }
        }

        return [$lessonIds, $seminarIds];
    }

    /**
     * @param  mixed  $raw
     */
    protected function decodeCoursesPayload($raw): ?array
    {
        if ($raw === null || $raw === '') {
            return null;
        }
        if (is_array($raw)) {
            return $raw;
        }
        if (! is_string($raw)) {
            return null;
        }
        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : null;
    }

    protected function isSequentialIntList(array $arr): bool
    {
        if ($arr === []) {
            return false;
        }
        $keys = array_keys($arr);

        return $keys === range(0, count($arr) - 1);
    }

}
