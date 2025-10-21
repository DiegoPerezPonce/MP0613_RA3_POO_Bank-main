<?php namespace ComBank\OverdraftStrategy\Contracts;

interface OverdraftInterface
{

    public function isGrantOverdraftFunds(float $found) : bool;
    public function getOverdraftFundsAmount() : float;
}