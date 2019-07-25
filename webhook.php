<?php
include './vendor/autoload.php';

$payload = @file_get_contents('php://input');
$event = null;

try {
    $event = new \FedaPay\Event(
        json_decode($payload, true)
    );
} catch(\Exception $e) {
    // Invalid payload
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
