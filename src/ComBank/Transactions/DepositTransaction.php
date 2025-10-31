<?php namespace ComBank\Transactions;

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;

class DepositTransaction extends BaseTransaction implements BankTransactionInterface
{
   
    public function __construct(float $amount, string $info = "Deposit")
    {
        
        parent::__construct($amount, $info); 
       
       if($this -> amount <= 0){
            throw new ZeroAmountException;
        }
    }

    public function applyTransaction(BankAccountInterface $bankAccount) : float
    {
       
        $currentBalance = $bankAccount->getBalance();
        
        
        $newBalance = $currentBalance + $this->getAmount(); 

        
        $bankAccount->setBalance($newBalance);

       
        return $newBalance;
    }

    public function getTransactionInfo() : string
    {
        //return "Deposit of " . $this->getAmount();
        return 'DEPOSIT_TRANSACTION';
    }

    public function getAmount() : float
    {
        return $this->amount; 
    }
    
}