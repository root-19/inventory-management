<?php
require_once __DIR__ . '/../config/configuration.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = $_GET['id'];
    
    // Fetch the product details from the database based on the product ID
    $sql = "SELECT * FROM add_product WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        // Handle form submission for editing the product
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Update the product in the database with the new values
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];

            $updateSql = "UPDATE add_product SET name = '$name', description = '$description', price = '$price', quantity = '$quantity' WHERE id = $productId";
            if ($conn->query($updateSql) === TRUE) {
                // Product updated successfully, redirect to the product listing page
                header("Location: group-product.php");
                exit();
            } else {
                echo "Error updating product: " . $conn->error;
            }
        }
    } else {
        echo "Product not found.";
    }
} else {
    echo "Invalid product ID.";
}
?>

<!-- HTML form for editing the product -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Edit Product</h2>
    <form action="" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </div>
        <!-- Add other input fields for description, price, quantity, etc. -->
        <!-- Example:
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"><?php echo $product['description']; ?></textarea>
        </div>
        -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save Changes</button>
    </form>
</div>

</body>
</html>

