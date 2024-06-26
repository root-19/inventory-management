<?php
require_once __DIR__ . '/../config/configuration.php';

// Check if the product ID is provided
if (!isset($_GET['id'])) {
    echo "No product ID provided!";
    exit();
}

$productId = $_GET['id'];

// Fetch product details from the database
$sql = "SELECT * FROM add_product WHERE id = $productId";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Product not found!";
    exit();
}

$product = $result->fetch_assoc();

// Handle form submission for updating the product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $expiration = $_POST['expiration'];

    $sql = "UPDATE add_product SET
            name = '$name',
            category = '$category',
            image = '$image',
            quantity = $quantity,
            description = '$description',
            price = $price,
            expiration = '$expiration'
            WHERE id = $productId";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Product updated successfully');
                window.location.href='group-product.php';
              </script>";
    } else {
        echo "Error updating product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h2 class="text-2xl font-bold mb-6 text-center">Update Product</h2>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-gray-700">Category</label>
                    <input type="text" name="category" id="category" value="<?php echo htmlspecialchars($product['category']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-gray-700">Image URL</label>
                    <input type="text" name="image" id="image" value="<?php echo htmlspecialchars($product['image']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="quantity" class="block text-gray-700">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea name="description" id="description" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-gray-700">Price</label>
                    <input type="number" step="0.01" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="expiration" class="block text-gray-700">Expiration Date</label>
                    <input type="date" name="expiration" id="expiration" value="<?php echo htmlspecialchars($product['expiration']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Update Product</button>
            </form>
        </div>
    </div>
</body>
</html>
