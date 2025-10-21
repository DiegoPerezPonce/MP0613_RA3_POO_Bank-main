<?php namespace ComBank\OverdraftStrategy;

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;


class NoOverdraft implements OverdraftInterface
{
 
    // Ajustado el nombre del método para que coincida con la interfaz y el diagrama
    public function isGrantOverdraftFunds(float $found) : bool
    {
        // En una estrategia "NoOverdraft", nunca se conceden fondos de sobregiro
        return false;
    }

    public function getOverdraftFundsAmount() : float
    {
        // En una estrategia "NoOverdraft", la cantidad de fondos es siempre 0
        return 0.0;
    }
}