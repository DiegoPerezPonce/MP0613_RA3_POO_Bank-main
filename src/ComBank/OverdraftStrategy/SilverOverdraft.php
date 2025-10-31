<?php namespace ComBank\OverdraftStrategy;

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Exceptions\InvalidArgsException;

class SilverOverdraft implements OverdraftInterface
{
    private const OVERDRAFT_LIMIT = 100.0; // Definimos el límite de sobregiro para Silver

    public function isGrantOverdraftFunds(float $found) : bool
    {
        
        if ($found < -100) {
            
            return false; 
        }
        return $found <= self::OVERDRAFT_LIMIT;
    }

    //usar getOverdraftFundsAmount() quitar el -100.

    public function getOverdraftFundsAmount() : float
    {
        
        return self::OVERDRAFT_LIMIT;
    }
}