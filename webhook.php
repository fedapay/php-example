<?php
include './vendor/autoload.php';

// You can find your endpoint's secret in your webhook settings
$endpoint_secret = 'wh_xxxxx';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_X_FEDAPAY_SIGNATURE'];
$event = null;

try {
    $event = \FedaPay\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    file_put_contents('webhook.log', 'UnexpectedValueException');

    http_response_code(400);
    exit();
} catch(\FedaPay\Error\SignatureVerification $e) {
    // Invalid signature
    file_put_contents('webhook.log', '\FedaPay\Error\SignatureVerification');

    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->name) {
    case 'transaction.created':
        file_put_contents('webhook.log', 'TRANSACTION CREATE: ' . $event->__toJSON());
        break;
    case 'transaction.approved':
        file_put_contents('webhook.log', 'TRANSACTION APPROVED: ' . $event->__toJSON());
        break;
    case 'transaction.canceled':
        file_put_contents('webhook.log', 'TRANSACTION CANCELED: ' . $event->__toJSON());
        break;
    default:
        http_response_code(400);
        exit();
}

http_response_code(200);
