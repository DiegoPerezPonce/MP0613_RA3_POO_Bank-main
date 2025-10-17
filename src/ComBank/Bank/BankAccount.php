<?php namespace ComBank\Bank;

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class BankAccount implements BankAccountInterface
{

    use AmountValidationTrait;
    
    private $balance;

    private $status;

    private $overdraft;

    /**
     *  @param float $newBalance;
     *  @throws InvalidArgsException
     *  @throws ZeroAmountException
     * 
     */
   
    public function __construct(float $newBalance = 0.0){

        //$this->validateAmount(amount:$newBalance);
        //$this->setBalance(newBalance:$newBalance);
        $this->balance = $newBalance;
        $this->status = BankAccountInterface::STATUS_OPEN;
        //$this->overdraft = new NoOverdraft();
    }

    public function reopenAccount():void{
        if($this->isOpen()){
            throw new BankAccountException(message:'Bank account already opened');
        }
        $this->status = BankAccountInterface::STATUS_OPEN;
    }

    public function closeAccount():void{
        if(!$this->isOpen()){
            throw new BankAccountException(message:'Bank account already closed.');
        }
        $this->status = BankAccountInterface::STATUS_CLOSED;
    }

    public function getBalance():float{
        return $this->balance;
    }


    public function transaction(BankTransactionInterface $bankTransaction) : void{

        return;
    }

    public function isOpen() : bool{
        if($this->status == BankAccountInterface::STATUS_OPEN ){
            return true;
        }else{
            return false;
        }
        
    }
    
   
    public function getOverdraft() : OverdraftInterface{
        return new OverdraftInterface();
    }
    public function applyOverdraft(OverdraftInterface $overdraft) : void{
        return;
    }
    public function setBalance(float $balance) : void{
        return;
    }

    


}
