<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
require_once __DIR__ . '/../config/configuration.php';

function checkProductQuantityAndSendAlert($conn) {
    // Fetch products with quantity <= 25
    $sql = "SELECT name, category, quantity FROM add_product WHERE quantity <= 25";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            sendEmailAlert($row['name'], $row['quantity']);
        }
    }
}

function sendEmailAlert($productName, $quantity) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
       
         $mail->Username   = 'wasieacuna@gmail.com'; // SMTP username
         $mail->Password   = 'qipc vais smfq rwim';   // SMTP password
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
         $mail->Port       = 587;

            //Recipients
         $mail->setFrom('wasieacuna@gmail.com', 'hi manager');
         $mail->addAddress('wasieacuna@gmail.com', 'Admin'); // Add a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Low Stock Alert';
        $mail->Body    = "The product <b>$productName</b> is low on stock. Only <b>$quantity</b> items left.";

        $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
