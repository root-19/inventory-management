<?php
require_once __DIR__ . '/../config/configuration.php';

// Check if the product ID is set and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch the product details from the database based on the product ID
    $sql = "SELECT * FROM add_product WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product_submit'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];

            $updateSql = "UPDATE add_product SET name = '$name', description = '$description', price = '$price', quantity = '$quantity' WHERE id = $productId";
            if ($conn->query($updateSql) === TRUE) {
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
    <form method="post">
        <input type="hidden" name="product_id" value="<?php echo isset($product['id']) ? $product['id'] : ''; ?>">
        <input type="text" name="name" value="<?php echo isset($product['name']) ? $product['name'] : ''; ?>">
        <input type="text" name="description" value="<?php echo isset($product['description']) ? $product['description'] : ''; ?>">
        <input type="text" name="price" value="<?php echo isset($product['price']) ? $product['price'] : ''; ?>">
        <input type="number" name="quantity" value="<?php echo isset($product['quantity']) ? $product['quantity'] : ''; ?>">
        <button type="submit" name="edit_product_submit">Save Changes</button>
    </form>
</div>

</body>
</html>
