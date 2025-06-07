<?php
// Load PHPMailer classes
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set content type
header('Content-Type: application/json');

// Get POST data
$from = isset($_POST['from']) ? $_POST['from'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$to = isset($_POST['to']) ? $_POST['to'] : '';
$subject = isset($_POST['subject']) ? $_POST['subject'] : '';
$body = isset($_POST['body']) ? $_POST['body'] : '';

// Validate
if (empty($from) || empty($name) || empty($to) || empty($subject) || empty($body)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'mail.spacemail.com';           // Replace with your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@orbislk.com';     // Replace with your email
    $mail->Password   = '6BB7A5A6-106c-4553-a856-f1D04F71C68D';              // Replace with your email password or app password
    $mail->SMTPSecure = 'ssl';                        // Use 'ssl' or 'tls'
    $mail->Port       = 465;                          // Use 587 for TLS, 465 for SSL

    // Recipients
    $mail->setFrom($from, $name);
    $mail->addAddress($to);

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Email sent successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
}
