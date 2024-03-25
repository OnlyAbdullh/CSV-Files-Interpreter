<?php
declare(strict_types=1);
$root=dirname(__DIR__). DIRECTORY_SEPARATOR;

define('APP_PATH',$root.'APP'.DIRECTORY_SEPARATOR);
define('FILES_PATH',$root.'Files'.DIRECTORY_SEPARATOR);
define('VIEWS_PATH',$root.'views'.DIRECTORY_SEPARATOR);
require APP_PATH . "project.php";
$files=getTransactionFiles(FILES_PATH);
$transactions=[];
foreach ($files as $file) {
    $transactions = array_merge($transactions, getTransactions($file, 'extractTransaction'));
}

$total=calculateTotal($transactions);
require VIEWS_PATH .'transaction.php';