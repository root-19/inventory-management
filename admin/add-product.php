<?php
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/validation.php';
require_once __DIR__ . '/../includes/functions.php';

// Fetch the count of reports
$sql_count_reports = "SELECT COUNT(*) as report_count FROM product_reports";
$result_count = $conn->query($sql_count_reports);
$report_count = $result_count->fetch_assoc()['report_count'];

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

    // Assign original_quantity to quantity
    $original_quantity = $quantity;

    // Prepare and execute SQL statement
    $sql = "INSERT INTO add_product (name, category, image, expiration, quantity, original_quantity, description, price) 
            VALUES ('$name', '$category', '$image', '$expiration', '$original_quantity', $quantity, '$description', $price)";

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
        
        // Check product quantity and send email alert if necessary
        checkProductQuantityAndSendAlert($conn);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

  
}

// Fetch categories from the database
$category_query = "SELECT name FROM categories";
$category_result = $conn->query($category_query);
  $conn->close();
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
  <!-- Main Content -->
     <div class="flex justify-center items-center w-full h-screen">
        <div class="main w-full max-w-4xl bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-center">Add New Product</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-6">
                <div class="col-span-1">
                    <div class="mb-3">
                        <label class="block text-gray-700 mb-1">Product Name</label>
                        <input type="text" name="name" placeholder="product name" class="w-full px-2 py-1 border rounded-lg" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-700 mb-1">Category</label>
                        <select name="category" class="w-full px-2 py-1 border rounded-lg" aria-placeholder="category" required>
                          <?php
                            if ($category_result->num_rows > 0) {
                                while($row = $category_result->fetch_assoc()) {
                                    echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                }
                            }
                          ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-700 mb-1">Quantity</label>
                        <input type="number" name="quantity" class="w-full px-2 py-1 border rounded-lg" placeholder="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-700 mb-1">Description</label>
                        <textarea name="description" class="w-full px-2 py-1 border rounded-lg" placeholder="description" required></textarea>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="mb-3">
                        <label class="block text-gray-700 mb-1">Price</label>
                        <input type="number" step="0.01" name="price" class="w-full px-2 py-1 border rounded-lg" placeholder="price" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-700 mb-1">Expiration Date</label>
                        <input type="date" name="expiration" class="w-full px-2 py-1 border rounded-lg" placeholder="expiration date" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-700 mb-1">Product Image</label>
                        <input type="file" name="image" class="w-full px-2 py-1 border rounded-lg" placeholder="product image" required>
                    </div>
                </div>
                <div class="col-span-2">
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
