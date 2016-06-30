<?php namespace App\Libraries\Authorizenet;
use App\libraries\auth\AuthorizeNetCIM;
use App\libraries\auth\shared\AuthorizeNetCustomer;
use App\libraries\auth\shared\AuthorizeNetPaymentProfile;
use App\libraries\auth\shared\AuthorizeNetAddress;
use App\libraries\auth\shared\AuthorizeNetTransaction;
use App\libraries\auth\shared\AuthorizeNetLineItem;
// We need to add these namespaces
// in order to have access to these classes.
//define("AUTHORIZENET_API_LOGIN_ID", "32px8XM76GZg");
//define("AUTHORIZENET_TRANSACTION_KEY", "9PLV89n5LPD9dx55");
//define("AUTHORIZENET_SANDBOX", true);

       
class Authorizenet {

    protected $AUTHORIZENET_API_LOGIN_ID;
    protected $AUTHORIZENET_TRANSACTION_KEY;
    protected $AUTHORIZENET_SANDBOX;
    
    

    // We instantiate the Imagine library with Imagick or GD
    public function __construct($library = null)
    {
        


        //$this->AUTHORIZENET_API_LOGIN_ID="32px8XM76GZg";
       // $this->AUTHORIZENET_TRANSACTION_KEY="9PLV89n5LPD9dx55";
       // $this->AUTHORIZENET_SANDBOX=true;
    }

   
    
    public function createprofile($card){
        
        //require_once( __DIR__ . DIRECTORY_SEPARATOR .'auth/shared/AuthorizeNetTypes.php');
        //require_once( __DIR__ . DIRECTORY_SEPARATOR .'auth/shared/AuthorizeNetXMLResponse.php');
        //require_once( __DIR__ . DIRECTORY_SEPARATOR .'auth/shared/AuthorizeNetRequest.php');
        //require_once( __DIR__ . DIRECTORY_SEPARATOR .'auth/AuthorizeNetCIM.php');
        //require_once( __DIR__ . DIRECTORY_SEPARATOR .'auth/shared/AuthorizeNetResponse.php');
        //require_once( __DIR__ . DIRECTORY_SEPARATOR .'auth/AuthorizeNetAIM.php');
        //
    $request = new AuthorizeNetCIM;
    $customerProfile = new AuthorizeNetCustomer;
    $customerProfile->description = $card['card_holder_fname']." ".$card['card_holder_lname'];
    $customerProfile->merchantCustomerId = time().rand(1,100);
    $customerProfile->email = $card['email'];
    $response = $request->createCustomerProfile($customerProfile);
   
    $customerProfileId = $response->getCustomerProfileId();
    $customer=array();
        if($customerProfileId){
           $customer['profile_id']=$customerProfileId;
        }
    // Update customer profile
   // $customerProfile->description = "I am Ujjal";
   //$customerProfile->email = "ujjal1.unified@gmail.com";
   // $response = $request->updateCustomerProfile($customerProfileId, $customerProfile);
   
   if($customerProfileId){ 
    
        // Add payment profile.
        $paymentProfile = new AuthorizeNetPaymentProfile;
        $paymentProfile->customerType = "individual";
        $paymentProfile->payment->creditCard->cardNumber = $card['card_number'];
        $paymentProfile->payment->creditCard->expirationDate = $card['expiry_year']."-".$card['expiry_month'];
        $response = $request->createCustomerPaymentProfile($customerProfileId, $paymentProfile);
       // print_r( $response->xml->messages->message->text);
        $paymentProfileId = $response->getPaymentProfileId();
        if($paymentProfileId){
           $customer['payment_profile_id']=$paymentProfileId;  
            
        }
        
        
        
         // Add shipping address.
    $address = new AuthorizeNetAddress;
    $address->firstName = $card['card_holder_fname'];
    $address->lastName = $card['card_holder_lname'];
    if($card['company_name'])
    $address->company = $card['company_name'];
    
    $address->address = $card['card_shiping_address'];
    $address->city = $card['card_shiping_city'];
    $address->state = $card['card_state'];
    $address->zip = $card['card_shipping_postcode'];
    $address->country = $card['country'];
    $address->phoneNumber =  $card['card_shipping_phone_no'];
    if($card['card_shipping_fax'])
    $address->faxNumber = $card['card_shipping_fax'];
    $response = $request->createCustomerShippingAddress($customerProfileId, $address);
    
    if($response->isOk()){
        $customerAddressId = $response->getCustomerAddressId();
        $customer['address_id']=$customerAddressId;
    }
    
   }
    // Update payment profile.
    //$paymentProfile->payment->creditCard->cardNumber = "4111111111111111";
    //$paymentProfile->payment->creditCard->expirationDate = "2017-11";
    //$response = $request->updateCustomerPaymentProfile($customerProfileId,$paymentProfileId, $paymentProfile);
   
    if($customerProfileId && $paymentProfileId ){
         $result=array('customer'=>$customer);
        $result['status']='success';
        $result['message']='success';
        
    }else{
        $result['status']='fail';
        $result['message']=(string)$response->xml->messages->message->text;
    
    }
   return $result;
    }
    
    
    public function transaction($trandata=array()){
        
        
        
    }

}