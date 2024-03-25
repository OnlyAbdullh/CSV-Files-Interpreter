<?php
declare(strict_types=1);
function getTransactionFiles(string $dirPath): array
{
    $files = [];
    foreach (scandir($dirPath) as $file) {
        if (is_dir($file)) continue;
        $files[] = $dirPath . $file;
    }
    return $files;
}

function getTransactions(string $fileName, ?callable $transactionHandler = null): array
{
    if (!file_exists($fileName)) {
        trigger_error('File "' . $fileName . '" does not exist.', E_USER_ERROR);
    }

    $file = fopen($fileName, 'r');

    fgetcsv($file);

    $transactions = [];

    while (($transaction = fgetcsv($file)) !== false) {
        if ($transactionHandler !== null) {
            $transaction = $transactionHandler($transaction);
        }
        $transactions[] = $transaction;
    }
    return $transactions;
}

function extractTransaction(array $transactionRow): array
{
    [$date, $chackNumber, $description, $amount] = $transactionRow;

    $amount = (float)str_replace(['$', ','], '', $amount);
    return [
        'date' => $date,
        'checkNumber' => $chackNumber,
        'description' => $description,
        'amount' => $amount];
}

function calculateTotal(array $transactions): array
{
    $total = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];
    foreach ($transactions as $transaction) {
        $total['netTotal'] += $transaction['amount'];
        if ($transaction['amount'] >= 0) {
            $total['totalIncome'] += $transaction['amount'];
        } else {
            $total['totalExpense'] += $transaction['amount'];
        }
    }
    return $total;
}

function formatDollarSign(float $amount): string
{
    $isNegitive = $amount < 0;
    return ($isNegitive ? '-' : '') . '$' . number_format(abs($amount), 2);
}

function formatDate(string $date):string{
    return date('M j, Y',strtotime($date));
}