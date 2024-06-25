<?php
require_once __DIR__ . '/../config/configuration.php';

// Check if a search query is provided
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Modify SQL query to include search functionality
$sql = "SELECT id, category, name, image, quantity, description, price, expiration 
        FROM add_product 
        WHERE name LIKE '%$searchQuery%' OR category LIKE '%$searchQuery%' 
        ORDER BY category";
$result = $conn->query($sql);

$products = array();
while ($row = $result->fetch_assoc()) {
    $category = $row['category'];
    if (!isset($products[$category])) {
        $products[$category] = array();
    }
    $products[$category][] = $row;
}

// Delete Product Function
if (isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];
    $sql = "DELETE FROM add_product WHERE id = $productId";
    if ($conn->query($sql) === TRUE) {
        header("Location: ".$_SERVER['PHP_SELF']);
    } else {
        echo "Error deleting product: " . $conn->error;
    }
}
// Logout functionality
if (isset($_GET['logout'])) {
    // Destroy the session and redirect to the login page
    session_start();
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grouped Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        #main {
            margin-left: 250px;
        }
    </style>
</head>
<body class="bg-gray-100">

<!-- Sidebar -->
<div class="flex">
    <div class="w-64 h-screen bg-blue-800 text-white flex flex-col fixed">
        <div class="px-6 py-4">
            <h2 class="text-xl font-bold">admin</h2>
        </div>
        <nav class="flex-1 px-4 py-2 space-y-2">
         <a href="admin.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
  <i class="fas fa-user-shield mr-2"></i> Admin
</a>

            <a href="add-product.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-plus-square mr-2"></i> Add Products
            </a>
            <a href="#" data-target="report" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-file-alt mr-2"></i> Report
            </a>
            <a href="#" data-target="daily-income" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-dollar-sign mr-2"></i> Daily Income
            </a>
            <a href="group-product.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-truck mr-2"></i> Group Supply
            </a>
                <a href="logout.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div id="main" class="max-w-6xl mx-auto flex flex-col justify-center items-center main p-6 ">
        <div class="mb-8 w-full">
            <form method="GET" action="" class="flex items-center mb-6">
                <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search products..." class="w-full px-3 py-2 border rounded-lg">
                <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-white rounded-lg">Search</button>
            </form>
        </div>
        <?php foreach ($products as $category => $categoryProducts): ?>
            <div class="mb-8 w-full">
                <h2 class="text-2xl font-bold mb-4"><?php echo $category; ?></h2>
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2">ID</th>
                            <th class="border border-gray-300 px-4 py-2">Image</th>
                            <th class="border border-gray-300 px-4 py-2">Name</th>
                            <th class="border border-gray-300 px-4 py-2">Description</th>
                            <th class="border border-gray-300 px-4 py-2">Price</th>
                            <th class="border border-gray-300 px-4 py-2">Quantity</th>
                            <th class="border border-gray-300 px-4 py-2">Expiration</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categoryProducts as $product): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $product['id']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-16 h-16 object-cover"></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $product['name']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $product['description']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $product['price']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $product['quantity']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $product['expiration']; ?></td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <form action="" method="post" class="inline">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" name="delete_product" class="text-red-500 hover:underline">Delete</button>
                                    </form>
                                    <a href="product-update.php?id=<?php echo $product['id']; ?>" class="text-blue-500 hover:underline">Update</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
