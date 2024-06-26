<?php
require_once(__DIR__ . '../config/configuration.php');
require_once(__DIR__ . '../config/validation.php');
session_start();

// If user already has an account
if (isset($_SESSION['user_id'])) {
    $redirect_url = getRedirectUrl($_SESSION['user_type']);
    header("Location: " . $redirect_url);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $user = ValidateLogin($email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_type'] = $user['type'];
        
        $redirect_url = getRedirectUrl($user['type']);
        header("Location: " . $redirect_url);
        exit();
    } else {
        echo "Invalid email or password.";
    }
}

function getRedirectUrl($userType) {
    switch ($userType) {
        case 'admin':
            return '../admin/admin.php';
        case 'staff':
            return '../public/staff.php';
        
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center">
    <div class="flex bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
        <div class="w-1/2 flex items-center justify-center border-r border-gray-300">
            <h2 class="text-2xl font-bold  text-center">Welcome to Sari-Sari store <br> Inventory managament system</h2>


        </div>
        <div class="w-1/2 p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Login your account</h2>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Username</label>
                    <input type="text" name="name" id="name" placeholder="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" placeholder="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" name="password" placeholder="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Login</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
