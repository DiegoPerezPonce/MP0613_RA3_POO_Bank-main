<?php
namespace ComBank\Transactions;

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{
    /**
     * Constructor
     * @param float $amount
     * @param string $info
     * @throws ZeroAmountException
     */
    public function __construct(float $amount, string $info = "Withdraw")
    {
        parent::__construct($amount, $info);

        if ($amount <= 0) {
            throw new ZeroAmountException("Amount must be positive");
        }
    }

    /**
     * Aplica la transacción al BankAccount
     * @param BankAccountInterface $bankAccount
     * @return float Nuevo balance
     * @throws InvalidOverdraftFundsException
     * @throws FailedTransactionException
     */
    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        $currentBalance = $bankAccount->getBalance();
        $withdrawAmount = $this->getAmount();

        // Si hay suficiente saldo, resta directamente
        if ($withdrawAmount <= $currentBalance) {
            $newBalance = $currentBalance - $withdrawAmount;
        } else {
            $overdraft = $bankAccount->getOverdraft();
            $neededOverdraft = $withdrawAmount - $currentBalance;

            // Caso 1: No hay sobregiro aplicado
            if ($overdraft === null) {
                throw new InvalidOverdraftFundsException("No overdraft strategy applied.");
            }

            // Caso 2: Sobregiro existe pero no concede fondos suficientes
            if (!$overdraft->isGrantOverdraftFunds($neededOverdraft)) {
                throw new FailedTransactionException("Overdraft not granted for withdrawal.");
            }

            // Caso 3: Sobregiro concedido
            $newBalance = $currentBalance - $withdrawAmount;
        }

        $bankAccount->setBalance($newBalance);
        return $newBalance;
    }

    /**
     * 
     * @return string
     */
    public function getTransactionInfo(): string
    {
        return 'WITHDRAW_TRANSACTION';
    }

    /**
     * Devuelve el monto de la transacción
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
}
