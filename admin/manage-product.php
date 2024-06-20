<?php
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/validation.php';

// Fetch Products
$sql = "SELECT * FROM add_product";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}

// Delete Product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    $sql = "DELETE FROM add_product WHERE id = $product_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Product deleted successfully',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload();
            });
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body class="bg-gray-100 p-8">

  <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6">Manage Products</h2>

    <!-- Search Bar -->
    <form action="" method="GET" class="mb-4">
      <input type="text" name="search" placeholder="Search by product name" class="border-gray-300 border rounded-md p-2">
      <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg ml-2">Search</button>
    </form>

    <table class="w-full">
      <thead>
        <tr>
          <th>Name</th>
          <th>Category</th>
          <th>Quantity</th>
          <th>Description</th>
          <th>Price</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        // Search Logic
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $filteredProducts = [];
        foreach ($products as $product) {
            if (stripos($product['name'], $search) !== false) {
                $filteredProducts[] = $product;
            }
        }
        foreach ($filteredProducts as $product) : ?>
          <tr>
            <td><?= $product['name']; ?></td>
            <td><?= $product['category']; ?></td>
            <td><?= $product['quantity']; ?></td>
            <td><?= $product['description']; ?></td>
            <td><?= $product['price']; ?></td>
            <td>
              <form action="" method="POST">
                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                <button type="submit" name="delete_product" class="bg-red-500 text-white py-1 px-3 rounded-lg">Delete</button>
              </form>
           
              <button onclick="window.location.href='product-update.php?id=<?= $product['id']; ?>'" class="bg-blue-500 text-white py-1 px-3 rounded-lg">Update</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
