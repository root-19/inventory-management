<?php
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/validation.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $expiration = $_POST['expiration'];
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFileName = $_FILES['image']['name'];
        $imageTempName = $_FILES['image']['tmp_name'];
        $imageUploadPath = __DIR__ . '/../uploads/' . $imageFileName;
        move_uploaded_file($imageTempName, $imageUploadPath);
        $image = 'uploads/' . $imageFileName;  // Store relative path
    }

    // Sanitize inputs
    $name = $conn->real_escape_string($name);
    $category = $conn->real_escape_string($category);
    $quantity = (int)$quantity;
    $description = $conn->real_escape_string($description);
    $price = (float)$price;
    $expiration = $conn->real_escape_string($expiration);
    $image = $image ? $conn->real_escape_string($image) : 'NULL';

    // Prepare and execute SQL statement
    $sql = "INSERT INTO add_product (name, category, image, expiration, quantity, description, price) 
            VALUES ('$name', '$category', '$image', '$expiration', $quantity, '$description', $price)";

    if ($conn->query($sql) === TRUE) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>";
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Successfully added a new product',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

// Logout functionality
if (isset($_GET['logout'])) {
    // Destroy the session and redirect to the login page
    session_start();
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Management</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
  <style>
    .content {
      display: none;
    }
    .active {
      display: block;
    }
    .main {
      margin-left:220px;
    }
  </style>
</head>




<body class="bg-gray-100 ">

  <!-- Sidebar -->
  <div class="flex">
    <div class="w-64 h-screen bg-blue-800 text-white flex flex-col fixed">
      <div class="px-6 py-4">
        <h2 class="text-xl font-bold">admin</h2>
      </div>
      <nav class="flex-1 px-4 py-2 space-y-2">
      <a href="admin.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
  <i class="fas fa-user-shield mr-2"></i> Admin
</a>

        <a href="add-product.php"  class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-plus-square mr-2"></i> Add Products
        </a>
        <a href="#" data-target="report" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-file-alt mr-2"></i> Report
        </a>
        <a href="#" data-target="daily-income" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-dollar-sign mr-2"></i> Daily Income
        </a>
        <a href="group-product.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-truck mr-2"></i> Group Supply
        </a>
         <a href="?logout=true" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
      </nav>
    </div>

  <!-- Main Content -->
<div class="flex justify-center items-center w-full">
    <div class="main w-full max-w-md bg-white p-4 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Add New Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Product Name</label>
                <input type="text" name="name" class="w-full px-2 py-1 border rounded-lg" required>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Category</label>
                <select name="category" class="w-full px-2 py-1 border rounded-lg" required>
                    <option value="Food">Food</option>
                    <option value="Drinks">Drinks</option>
                    <option value="Appliances">Appliances</option>
                    <option value="Household Essential">Household Essential</option>
                    <option value="Cooking Essential">Cooking Essential</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Product Image</label>
                <input type="file" name="image" class="file-input file-input-bordered file-input-primary w-full max-w-xs">
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Expiration Date</label>
                <input type="date" name="expiration" class="w-full px-2 py-1 border rounded-lg">
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Quantity</label>
                <input type="number" name="quantity" class="w-full px-2 py-1 border rounded-lg" required>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Description</label>
                <textarea name="description" class="w-full px-2 py-1 border rounded-lg" required></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Price</label>
                <input type="number" name="price" step="0.01" class="w-full px-2 py-1 border rounded-lg" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg">Add Product</button>
        </form>
    </div>
</div>  </div>
</body>
</html>
