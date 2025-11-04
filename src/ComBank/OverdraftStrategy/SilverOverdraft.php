<?php namespace ComBank\OverdraftStrategy;

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

class SilverOverdraft implements OverdraftInterface
{
    private const OVERDRAFT_LIMIT = 100.0; // Límite de sobregiro para Silver

    /**
     * Determina si se puede conceder sobregiro para una cantidad dada
     */
    public function isGrantOverdraftFunds(float $amount) : bool
    {
        $limit = $this->getOverdraftFundsAmount();

        // Si la cantidad negativa supera el límite, no se concede
        if ($amount < -$limit) {
            return false;
        }

        // Se concede si la cantidad es menor o igual al límite
        return $amount <= $limit;
    }

    /**
     * Devuelve el límite de fondos de sobregiro permitido
     */
    public function getOverdraftFundsAmount() : float
    {
        return self::OVERDRAFT_LIMIT;
    }
}
