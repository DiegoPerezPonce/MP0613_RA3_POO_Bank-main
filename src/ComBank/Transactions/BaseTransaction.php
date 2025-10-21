<?php namespace ComBank\Transactions;


use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Support\Traits\AmountValidationTrait;

abstract class BaseTransaction
{
    public float $amount;

    public function __construct(float $amount)
    {
        $this-> amount = $amount;
    }

    public function getAmount() : float{
        return $this->amount;
    }
}
