<?php namespace ComBank\OverdraftStrategy;

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Exceptions\InvalidArgsException; // Podrías necesitar esta excepción para validación

class SilverOverdraft implements OverdraftInterface
{
    private const OVERDRAFT_LIMIT = 100.0; // Definimos el límite de sobregiro para Silver

    public function isGrantOverdraftFunds(float $found) : bool
    {
        // Si el monto solicitado como sobregiro es mayor que el límite permitido,
        // no se conceden los fondos. Asumimos que $found es el monto que se intentaría "sobregirar".
        // También validamos que el monto no sea negativo.
        if ($found < 0) {
            // O podrías lanzar una InvalidArgsException aquí si lo consideras apropiado
            return false; 
        }
        return $found <= self::OVERDRAFT_LIMIT;
    }

    public function getOverdraftFundsAmount() : float
    {
        // Retorna la cantidad máxima de fondos de sobregiro disponibles
        return self::OVERDRAFT_LIMIT;
    }
}