<?php
require_once __DIR__ . '/../config/configuration.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$messageStatus = '';

// Handle report submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_report'])) {
    $product_name = $_POST['product_name'];
    $message = $_POST['message'];

    // Insert report into the database
    $sql_insert_report = "INSERT INTO product_reports (product_name, message, report_date) 
                          VALUES ('$product_name', '$message', NOW())";
    if ($conn->query($sql_insert_report)) {
        // Send email notification using PHPMailer
        $mail = new PHPMailer();
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'wasieacuna@gmail.com'; // SMTP username
            $mail->Password   = 'qipc vais smfq rwim';   // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('wasieacuna@gmail.com', 'hi manager');
            $mail->addAddress('wasieacuna@gmail.com', 'Admin'); // Add a recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Product Report';
            $mail->Body    = "Product: $product_name<br>Message: $message";

  $mail->send();
            $messageStatus = 'success';
        } catch (Exception $e) {
            $messageStatus = 'email_error';
        }
    } else {
        $messageStatus = 'db_error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Product Issue</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <div class="w-64 h-screen bg-blue-800 text-white flex flex-col fixed">
        <div class="px-6 py-4">
            <h2 class="text-xl font-bold">Admin</h2>
        </div>
        <nav class="flex-1 px-4 py-2 space-y-2">
            <a href="staff.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-user-shield mr-2"></i> Product
            </a>
            <a href="report.php" data-target="report" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-file-alt mr-2"></i> Report
            </a>
            <a href="sell-product.php" data-target="daily-income" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-dollar-sign mr-2"></i> Daily Income
            </a>
            <a href="?logout=true" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8">
        <h1 class="text-3xl font-bold mb-6">Report Product Issue</h1>
        <form method="post" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="product_name" class="block text-gray-700 font-bold mb-2">Product Name</label>
                <input type="text" id="product_name" name="product_name" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>
            <div class="mb-4">
                <label for="message" class="block text-gray-700 font-bold mb-2">Message</label>
                <textarea id="message" name="message" required class="w-full p-2 border border-gray-300 rounded-lg"></textarea>
            </div>
            <button type="submit" name="submit_report" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Submit Report</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            <?php if ($messageStatus === 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Report Submitted',
                    text: 'Report submitted successfully and email notification sent.',
                });
            <?php elseif ($messageStatus === 'email_error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Email Error',
                    text: 'Report submitted, but email notification failed. Please try again.',
                });
            <?php elseif ($messageStatus === 'db_error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Database Error',
                    text: 'Failed to submit report. Please try again.',
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>