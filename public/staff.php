<?php
require_once __DIR__ . '/../config/configuration.php';

// Fetch products from the database
$sql = "SELECT id, name, image, barcode, description, price, quantity FROM add_product";

$result = $conn->query($sql);

$products = array();
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Initialize alert message
$alertMessage = '';

// Handle selling a product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sell_product'])) {
    $product_id = $_POST['product_id'];
    $quantity_sold = intval($_POST['quantity_sold']);

    // Fetch the product details
    $sql_fetch_product = "SELECT name, price, quantity FROM add_product WHERE id = $product_id";
    $result_fetch_product = $conn->query($sql_fetch_product);
    $product_details = $result_fetch_product->fetch_assoc();

    if ($product_details['quantity'] >= $quantity_sold) {
        // Calculate total price for the sold items
        $total_price = $quantity_sold * floatval($product_details['price']);

        // Update product quantity in the 'add_product' table
        $new_quantity = $product_details['quantity'] - $quantity_sold;
        $sql_update_quantity = "UPDATE add_product SET quantity = $new_quantity WHERE id = $product_id";
        $conn->query($sql_update_quantity);

        // Insert sale record into 'sold_products' table
        $sql_insert_sale = "INSERT INTO sold_products (product_id, quantity_sold, total_price, sale_date) 
                            VALUES ($product_id, $quantity_sold, $total_price, NOW())";
        $conn->query($sql_insert_sale);

        $alertMessage = "Product sold successfully!";
    } else {
        
        $alertMessage = "Insufficient quantity available for sale!";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        .content {
            display: none;
        }
        .active {
            display: block;
        }
        .main {
            margin-left: 220px; /* Adjusted for fixed sidebar */
        }
        .product-image {
            height: 200px; /* Adjust as needed */
            object-fit: cover;
        }
        .quantity-info {
            font-size: 0.8rem;
            color: #999;
            margin-top: 0.5rem;
        }
  
    </style>
</head>
<body class="bg">
    <!-- Sidebar -->
    <div class="flex">
        <div class="w-64 h-screen bg-blue-800 text-white flex flex-col fixed">
            <div class="px-6 py-4">
                <ul>
                    <li class="py-2 flex items-center">
                        <i class="fas fa-user mr-2"></i>
                        <a href="#" class="text-white hover:text-blue-300">Staff</a>
                    </li>
                </ul>
            </div>
            <nav class="flex-1 px-4 py-2 space-y-2">
                <a href="staff.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                    <i class="fas fa-user-shield mr-2"></i> Product
                </a>
                <a href="report.php" data-target="report" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                    <i class="fas fa-file-alt mr-2"></i> Report
                </a>
                <a href="sell-product.php" data-target="daily-income" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                    <i class="fas fa-dollar-sign mr-2"></i> Daily Income
                </a>
                <a href="all-sales.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                    <i class="fas fa-boxes mr-2"></i> Inventory
                </a>
                <a href="logout.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </div>

        <div class="flex-1 ml-64 p-6">
            <h1 class="text-3xl font-bold mb-6">Products</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($products as $product): ?>
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <!-- Product details -->
                        <form method="post">
                        <?php if ($product['quantity'] <= 10): ?>
            <span class="bg-red-500 text-white text-2xl font-bold px-2 py-1 rounded-lg inline-block mt-2">
                Low Stock
            </span>
        <?php endif; ?>
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <img src="../admin/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-full product-image mb-4">
                           <img src="../admin/<?php echo $product['barcode']; ?>" alt="Barcode for <?php echo $product['name']; ?>" class="w-full h-auto mt-2">

                            <h2 class="text-lg font-bold"><?php echo $product['name']; ?></h2>
                            <p class="text-gray-700 mb-4"><?php echo $product['description']; ?></p>
                            <p class="text-gray-900 font-bold">₱<?php echo $product['price']; ?></p>
                            <p class="text-gray-700">Quantity: <?php echo $product['quantity']; ?></p>
                            <div class="flex items-center">
                            <input 
        type="number" 
        name="quantity_sold" 
        class="w-20 bg-white border border-gray-300 rounded-lg py-1 px-2 text-center focus:outline-none focus:ring-2 focus:ring-blue-500"  
        min="1" 
        max="<?php echo $product['quantity']; ?>" 
        required
        placeholder="1"
    >
    <span class="text-sm text-gray-500">Max: <?php echo $product['quantity']; ?></span>
                            </div>
                            <button type="submit" name="sell_product" class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4 hover:bg-blue-600">Sell</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Chat Icon -->
<div class="chat-icon position-fixed bottom-0 end-0 p-3 bg-primary text-white rounded-circle" id="chatIcon" style="cursor: pointer; z-index: 1000;">
    <i class="fas fa-comments"></i>
</div>

<!-- Chat Window -->
<div class="chat-window card position-fixed bottom-0 end-0 mb-5 me-3" id="chatWindow" style="width: 300px; display: none; z-index: 1000;">
    <div class="card-header bg-primary text-white">
        Chat with AI 
        <button type="button" class="btn-close btn-close-white float-end" aria-label="Close" id="closeChatWindow"></button>
    </div>
    <div class="card-body overflow-auto" style="max-height: 200px;" id="chatBody"></div>
    <div class="card-footer">
        <form id="chatForm">
            <div class="input-group">
                <input type="text" name="message" id="messageInput" class="form-control" placeholder="Type a message..." autocomplete="off">
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        let isFirstTimeOpen = true;

        $('#chatIcon').click(function() {
            $('#chatWindow').toggle();

            if (isFirstTimeOpen) {
                $('#chatBody').append('<p><strong>AI:</strong> Hello! I am your virtual assistant. I am here to help you with any questions or concerns you may have. How can I assist you today?</p>');
                isFirstTimeOpen = false;
            }
        });

        $('#closeChatWindow').click(function() {
            $('#chatWindow').hide();
        });

        $('#messageInput').keypress(function(e) {
            if (e.which == 13) { // 13 is the Enter key code
                e.preventDefault(); // Prevent default form submission
                sendMessage();
            }
        });

        function sendMessage() {
            var message = $('#messageInput').val();

            $.ajax({
                type: 'POST',
                url: 'chat.php', 
                data: { message: message },
                dataType: 'json',
                success: function(response) {
                    // Display user message
                    $('#chatBody').append('<p><strong>You:</strong> ' + message + '</p>');

                    // Display AI response
                    if(response.message) {
                        $('#chatBody').append('<p><strong>AI:</strong> ' + response.message + '</p>');
                    }

                    // Display image if available
                    if(response.image) {
                        $('#chatBody').append('<img src="' + response.image + '" alt="Product Image" style="width:100%;">');
                    }

                    $('#messageInput').val(''); 
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
</script>
</body>
</html>
