<?php
header('Content-Type: application/json');

$to = isset($_POST['to']) ? $_POST['to'] : '';
$body = isset($_POST['body']) ? $_POST['body'] : '';

if (empty($to) || empty($body)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$message = $body;

// SMSlenz API config
$userId    = '1048';
$apiKey    = 'a8fa3378-c082-45b6-be4b-da60be2f464b';
$senderId = 'SMSlenzDEMO';

// URL encode message
$encodedMessage = urlencode($message);

// Build API URL
$smsUrl = "https://smslenz.lk/api/send-sms?"
        . "user_id={$userId}"
        . "&api_key={$apiKey}"
        . "&sender_id={$senderId}"
        . "&contact={$to}"
        . "&message={$encodedMessage}";

// Send SMS using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $smsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$curlError = curl_error($ch);

curl_close($ch);

// Handle response
if ($curlError) {
    echo json_encode([
        'success' => false,
        'message' => 'SMS sending failed',
        'error'   => $curlError
    ]);
    exit;
}

// Success
echo json_encode([
    'success'     => true,
    'contact_no'  => $to,
    'sms_response'=> $response
]);
