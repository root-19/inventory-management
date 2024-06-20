<?php
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/validation.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_FILES["image"]["tmp_name"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["image"]["name"]);
            } else {
                $image = null;
            }
        } else {
            $image = null;
        }
    }

    $sql = "INSERT INTO add_product (name, category, image, quantity, description, price)
            VALUES ('$name', '$category', '$image', $quantity, '$description', $price)";

    if ($conn->query($sql) === TRUE) {
    //    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>";
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
    // $conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Management</title>
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

  <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6">Add New Product</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Product Name</label>
        <input type="text" name="name" class="w-full px-3 py-2 border rounded-lg" required>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Category</label>
        <select name="category" class="w-full px-3 py-2 border rounded-lg" required>
          <option value="Food">Food</option>
          <option value="Drinks">Drinks</option>
          <option value="Appliances">Appliances</option>
        
        </select>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Product Image</label>
        <input type="file" name="image" class="w-full px-3 py-2 border rounded-lg">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Quantity</label>
        <input type="number" name="quantity" class="w-full px-3 py-2 border rounded-lg" required>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Description</label>
        <textarea name="description" class="w-full px-3 py-2 border rounded-lg" required></textarea>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Price</label>
        <input type="number" name="price" step="0.01" class="w-full px-3 py-2 border rounded-lg" required>
      </div>
      <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg">Add Product</button>
    </form>
  </div>

</body>
</html>
