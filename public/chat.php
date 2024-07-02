<?php
require_once __DIR__ . '/../config/configuration.php';
// Handle POST request from chat form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assume you have sanitized the input for security (not shown here)
    $message = $_POST['message'];

    // Log received message for debugging
    error_log("Received message: " . $message); // Check your PHP error log or console for this output

    // Function to sanitize user input for searching
    $sanitizedMessage = mysqli_real_escape_string($conn, $message);
    
    // Query to fetch product details based on user input
    $query = "SELECT p.id, p.name, p.category, p.image, p.quantity, p.description, 
                     p.price, p.expiration, p.original_quantity, 
                     s.total_price, s.sale_date, s.quantity_sold 
              FROM add_product p 
              LEFT JOIN sold_products s ON p.id = s.product_id 
              WHERE p.name LIKE '%$sanitizedMessage%'";
              
    // Prepare and execute the statement
    $result = mysqli_query($conn, $query);

    // Check if query returned any results
    if (mysqli_num_rows($result) > 0) {
        // Fetch and return data as JSON
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode($data);
    } else {
        // No matching product found
        echo json_encode(array('message' => 'No matching product found.'));
    }
}
?>