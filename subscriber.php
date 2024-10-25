<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "admindinhphatdat/database/database.php";
include "admindinhphatdat/database/mailsettings.php";
include "admindinhphatdat/database/subscribers.php";
include "admindinhphatdat/database/settings.php";

$database = new database();
$db = $database->connect();

$settings = new settings($db);
$settings->id = 1;
$settings->read();

$mailsettings = new mailsettings($db);
$mailsettings->id = 1;
$mailsettings->read();

// Load Composer's autoloader
require 'admindinhphatdat/mail_app/vendor/autoload.php';

$code = uniqid('verify_'); // Generate unique verification code

// Create PHPMailer instance
$mail = new PHPMailer(true);

// Server settings
$mail->isSMTP();                                         // Send using SMTP
$mail->Host       = $mailsettings->mail_server;          // SMTP server
$mail->SMTPAuth   = true;                                // Enable SMTP authentication
$mail->Username   = $mailsettings->mail_username;        // SMTP username
$mail->Password   = $mailsettings->mail_password;        // SMTP password
$mail->Port       = $mailsettings->mail_port;            // TCP port to connect to

// Recipients
$mail->setFrom($mailsettings->email, 'Support');

// Process POST request
if (!empty($_POST['email'])) {
    $subscribers = new subscribers($db);
    $subscribers->email = $_POST['email'];
    $stmt = $subscribers->checkRequestSubscriber();

    // Resend verification code if user exists
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        if ($row['verified_token'] == 'verified') {
            echo "already_subscriber";
        } else {
            $db_code = $row['verified_token'];
            $title = 'Resend confirmation for creating Blogs account!';
            $content = '<h5>Welcome '.$_POST['email'].'</h5>
                        <p>Thank you for signing up for Blogs.</p>
                        <p>Verify your email address by clicking the link below:</p>
                        <a href="' . $settings->site_link . $db_code . '">Confirm my account</a>
                        <p>Note that unverified accounts are automatically deleted 30 days after sign up.</p>
                        <p>If you didn\'t request this, please ignore this email.</p>';

            try {
                $mail->addAddress($_POST['email']);
                $mail->isHTML(true);
                $mail->Subject = $title;
                $mail->Body    = $content;
                $mail->send();
                echo "resend_mail";
            } catch (Exception $e) {
                echo "Message could not be resent. Mailer Error: ".$mail->ErrorInfo;
            }
        }
    }
    // Create new verification code for new subscriber
    else {
        $subscribers->email = $_POST['email'];
        $subscribers->verified_token = $code;
        $subscribers->status = 0;

        $subscribers->created_at = date('Y-m-d H:i:s');
        $subscribers->updated_at = date('Y-m-d H:i:s');

        if ($subscribers->create()) {
            $title = 'Confirmation for creating Blogs account!';
            $content = '<h5>Welcome '.$_POST['email'].'</h5>
                        <p>Thank you for signing up for Blogs.</p>
                        <p>Verify your email address by clicking the link below:</p>
                        <a href="' . $settings->site_link . $code . '">Confirm my account</a>
                        <p>Note that unverified accounts are automatically deleted 30 days after sign up.</p>
                        <p>If you didn\'t request this, please ignore this email.</p>';

            try {
                $mail->addAddress($_POST['email']);
                $mail->isHTML(true);
                $mail->Subject = $title;
                $mail->Body    = $content;
                $mail->send();
                echo "success";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: ".$mail->ErrorInfo;
            }
        } else {
            echo "false"; // Failed to create subscriber
        }
    }
} else {
    echo "false"; // Empty email POST request
}
?>
