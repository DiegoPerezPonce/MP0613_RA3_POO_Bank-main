<?php namespace ComBank\Transactions;

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Exceptions\InvalidArgsException; // Asumo que podrías necesitar esta para la validación de monto

class DepositTransaction extends BaseTransaction implements BankTransactionInterface
{
    // Constructor si es necesario y si BaseTransaction no lo maneja completamente
    // Asumo que BaseTransaction tiene una propiedad $amount y un constructor para inicializarla.
    public function __construct(float $amount, string $info = "Deposit")
    {
        // Llama al constructor de la clase padre (BaseTransaction)
        parent::__construct($amount, $info); 
        // Puedes añadir validaciones adicionales si BaseTransaction no las hace.
        if ($this->amount <= 0) {
            throw new InvalidArgsException("Deposit amount must be positive.");
        }
    }

    public function applyTransaction(BankAccountInterface $bankAccount) : float
    {
        // Obtener el balance actual
        $currentBalance = $bankAccount->getBalance();
        
        // Calcular el nuevo balance
        $newBalance = $currentBalance + $this->getAmount(); // Usamos getAmount() de BaseTransaction

        // Establecer el nuevo balance en la cuenta
        $bankAccount->setBalance($newBalance);

        // Retornar el nuevo balance
        return $newBalance;
    }

    public function getTransactionInfo() : string
    {
        
        return "Deposit of " . $this->getAmount();
    }

    public function getAmount() : float
    {
        
        return $this->amount; 
    }
    
}