<?php namespace ComBank\Support\Traits;

use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;

trait AmountValidationTrait
{
    /**
     * @param float $amount
     * @throws InvalidArgsException
     * @throws ZeroAmountException
     */
    public function validateAmount(float $amount):void
    {
        if($amount <= 0){
            throw new ZeroAmountException("amount must be greater that 0");
        }
    }
}
