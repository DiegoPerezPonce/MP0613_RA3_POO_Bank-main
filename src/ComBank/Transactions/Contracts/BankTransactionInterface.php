<?php namespace ComBank\Transactions\Contracts;

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use PhpParser\Node\Expr\Cast\String_;

interface BankTransactionInterface
{
    public function applyTransaction(BankAccountInterface $bank_account) : float;
    public function getTransactionInfo() : string;
    public function getAmount() : float;
}