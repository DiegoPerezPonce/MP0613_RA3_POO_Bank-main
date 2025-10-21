<?php namespace ComBank\Transactions;

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Exceptions\InsufficientFundsException; 
use ComBank\Exceptions\InvalidArgsException; 
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Transactions\Contracts\BankTransactionInterface;


class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{
     
    public function __construct(float $amount, string $info = "Withdrawal")
    {
        parent::__construct($amount, $info);
        
        if ($this->amount <= 0) {
            throw new InvalidArgsException("Withdrawal amount must be positive.");
        }
    }

    public function applyTransaction(BankAccountInterface $bank_account): float
    {
        $currentBalance = $bank_account->getBalance();
        $withdrawAmount = $this->getAmount();
        $newBalance = $currentBalance - $withdrawAmount;

        
        if ($newBalance < 0) {
            $overdraftStrategy = $bank_account->getOverdraft();
            
            $neededOverdraft = abs($newBalance); 

            if (!$overdraftStrategy->isGrantOverdraftFunds($neededOverdraft)) {
                
                throw new FailedTransactionException("Insufficient funds and overdraft not granted for the amount: " . $neededOverdraft);
            }
            
        }

        
        $bank_account->setBalance($newBalance);

        
        return $newBalance;
    }

    public function getTransactionInfo(): string
    {
        
        return "withdraw (-{$this->amount})";
    }

    public function getAmount(): float
    {
       
        return $this->amount;
    }
}
