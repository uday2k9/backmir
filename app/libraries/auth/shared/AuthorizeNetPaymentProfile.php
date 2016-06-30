<?php
namespace App\libraries\auth\shared;
class AuthorizeNetPaymentProfile
{
    
    public $customerType;
    public $billTo;
    public $payment;
    public $customerPaymentProfileId;
    
    public function __construct()
    {
        $this->billTo = new AuthorizeNetAddress;
        $this->payment = new AuthorizeNetPayment;
    }

}