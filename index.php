<?php

include './vendor/autoload.php';

use \FedaPay\FedaPay;
use \FedaPay\Transaction;

FedaPay::setApiKey("sk_sandbox_QOnQj0rYVtoDb-jOMVurIz60");
FedaPay::setEnvironment('sandbox');

/**
 * Create a transaction
 */
$transaction = Transaction::create([
  "description" => "Transaction for john.doe@example.com",
  "amount" => 1,
  "currency" => ["iso" => "XOF"],
  "customer" => [
      "firstname" => "John",
      "lastname" => "Doe",
      "email" => "john.doe@example.com"
  ]
]);

/**
 * Send a mobile money request
 */
$transaction->sendNow('mtn', ['phone_number' => ['number' => '66994148', "country" => "bj"]]);

/**
 * Or generate a payment link to let the customer choose his payment mode
 *
 * $token = $transaction->generateToken();
 * // to paymen page at $token->url
 */
 