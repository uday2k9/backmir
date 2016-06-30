<?php
namespace App\libraries\auth\shared;

class AuthorizeNetPayment
{
    public $creditCard;
    public $bankAccount;
    
    public function __construct()
    {
        $this->creditCard = new AuthorizeNetCreditCard;
        $this->bankAccount = new AuthorizeNetBankAccount;
    }
}