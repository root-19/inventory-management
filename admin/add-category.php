<?php
require_once __DIR__ . '/../config/configuration.php';

// Fetch the count of reports
$sql_count_reports = "SELECT COUNT(*) as report_count FROM product_reports";
$result_count = $conn->query($sql_count_reports);
$report_count = $result_count->fetch_assoc()['report_count'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_category = $_POST['new_category'];
    
    // Sanitize input
    $new_category = $conn->real_escape_string($new_category);
    
    // Insert new category into the database
    $sql = "INSERT INTO categories (name) VALUES ('$new_category')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>";
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Successfully added a new category',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                window.location.href = 'add-category.php';
            }, 2000);
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Category</title>
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
      margin-left:400px;
      margin-top: 20%;
    }
  </style>
</head>

<body class="bg-gray-100 ">

    <!-- Sidebar -->
  <div class="flex">
    <div class="w-64 h-screen bg-blue-800 text-white flex flex-col fixed">
      <div class="px-6 py-4">
        <!-- <h2 class="text-xl font-bold">admin</h2> -->
      </div>
      <nav class="flex-1 px-4 py-2 space-y-2">
        <a href="admin.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-user-shield mr-2"></i> Admin
        </a>
        <a href="add-product.php"  class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-plus-square mr-2"></i> Add Products
        </a>
          <a href="display-report.php" data-target="report" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-file-alt mr-2"></i> Report 
                <span class="ml-2 bg-red-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full"><?= $report_count ?></span>
            </a>
         <a href="inventory.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-boxes mr-2"></i> Inventory
        </a>
        <a href="daily-income.php" data-target="daily-income" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-dollar-sign mr-2"></i> Daily Income
        </a>
        <a href="group-product.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-truck mr-2"></i> Group Supply
        </a>
        <a href="add-category.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
  <i class="fas fa-tag mr-2"></i> Category
  </a>
        <a href="logout.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </nav>
    </div>
  <div class="main w-full max-w-md bg-white p-4 rounded-lg shadow-lg">
    <h2 class="text-xl font-bold mb-4">Add New Category</h2>
    <form action="" method="POST">
      <div class="mb-3">
        <label class="block text-gray-700 mb-1">Category Name</label>
        <input type="text" name="new_category" placeholder="category" class="w-full px-2 py-1 border rounded-lg" required>
      </div>
      <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg">Add Category</button>
    </form>
  </div>
</body>
</html>
