<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = $_GET['id'];
    
    // Delete the product from the database based on the product ID
    $deleteSql = "DELETE FROM add_product WHERE id = $productId";
    if ($conn->query($deleteSql) === TRUE) {
        // Product deleted successfully, redirect to the product listing page
        header("Location: group-product.php");
        exit();
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    echo "Invalid product ID.";
}