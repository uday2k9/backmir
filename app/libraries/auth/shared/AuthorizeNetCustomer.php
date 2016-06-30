<?php
namespace App\libraries\auth\shared;
class AuthorizeNetCustomer
{
    public $merchantCustomerId;
    public $description;
    public $email;
    public $paymentProfiles = array();
    public $shipToList = array();
    public $customerProfileId;
    
}
 
?>