<?php
// contact_handler.php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Load PHPMailer
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            // 1. Save to Database
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message]);

            // 2. Send Email
            $mail = new PHPMailer(true);

            // --- SMTP Settings (Uncomment and configure for SMTP) ---
            /*
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your-email@gmail.com';
            $mail->Password   = 'your-app-password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            */
            // --------------------------------------------------------

            $mail->setFrom('no-reply@dbp-portfolio.com', 'Portfolio Contact Form');
            $mail->addAddress('brightonpaul003@gmail.com');
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);
            $mail->Subject = "New Portfolio Message: " . $subject;
            $mail->Body    = "
                <h3>New message from your portfolio website</h3>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Subject:</strong> {$subject}</p>
                <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
            ";

            if ($mail->send()) {
                echo json_encode(['status' => 'success', 'message' => 'Thank you! Your message has been sent.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Message saved, but email could not be sent.']);
            }

        } catch (Exception $e) {
            // Message was saved to DB but mail failed
            echo json_encode(['status' => 'partial', 'message' => 'Message saved, but there was an error sending the email.']);
        } catch (\PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error. Please try again later.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
