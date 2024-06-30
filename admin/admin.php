<?php
require_once __DIR__ . '/../config/configuration.php';

// Fetch the count of reports
$sql_count_reports = "SELECT COUNT(*) as report_count FROM product_reports";
$result_count = $conn->query($sql_count_reports);
$report_count = $result_count->fetch_assoc()['report_count'];


// Fetch the count of reports
$sql_count_reports = "SELECT COUNT(*) as report_count FROM product_reports";
$result_count = $conn->query($sql_count_reports);
$report_count = $result_count->fetch_assoc()['report_count'];

// Query to count products in each category
$sql = "SELECT category, COUNT(*) AS count FROM add_product GROUP BY category";
$result = $conn->query($sql);

// Check if query was successful
if ($result->num_rows > 0) {
    // Store category counts in an array
    while ($row = $result->fetch_assoc()) {
        $category = $row['category'];
        $count = $row['count'];
        $categoryCounts[$category] = $count;
    }
}

// Fetch total sales from the database
$sql_fetch_total_sales = "SELECT SUM(total_price) AS total_sales FROM sold_products";
$result_total_sales = $conn->query($sql_fetch_total_sales);
$total_sales = $result_total_sales->fetch_assoc()['total_sales'];

// Format the total sales amount for display
$formatted_total_sales = number_format($total_sales, 2);

// Fetch the category count
$category_count_sql = "SELECT COUNT(*) as count FROM categories";
$category_count_result = $conn->query($category_count_sql);
$category_count = 0;

if ($category_count_result->num_rows > 0) {
    $category_count_row = $category_count_result->fetch_assoc();
    $category_count = $category_count_row['count'];
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
<body class="bg-white">

<!-- Sidebar -->
<div class="flex">
    <div class="w-64 h-screen bg-blue-800 text-white flex flex-col fixed">
        <div class="px-6 py-4">
            <h2 class="text-xl font-bold">Admin Panel</h2>
        </div>
        <nav class="flex-1 px-4 py-2 space-y-2">
            <a href="admin.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-user-shield mr-2"></i> Admin
            </a>
            <a href="add-product.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-plus-square mr-2"></i> Add Products
            </a>
              <a href="display-report.php" data-target="report" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-file-alt mr-2"></i> Report 
                <span class="ml-2 bg-red-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full"><?= $report_count ?></span>
            </a>
            <a href="inventory.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-boxes mr-2"></i> Inventory
            </a>
            <a href="daily-income.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
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
    <div class="container mx-auto px-4 py-8 ml-64">
        <d class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold mb-2"><i class="fas fa-list-alt mr-2"></i> Category Counts</h2>
                <ul class="list-disc list-inside">
                    <?php
                    // Display category counts
                    foreach ($categoryCounts as $category => $count) {
                        echo "<li>$category: $count</li>";
                    }
                    ?>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold mb-2"><i class="fas fa-dollar-sign mr-2"></i> Total Sales</h2>
                <p class="text-lg font-bold">Total Sales Amount: <?php echo $formatted_total_sales; ?></p>
            </div>
        <br>
           <div class="bg-white rounded-lg shadow p-4">
                  <h2 class="text-lg font-semibold mb-2"><i class="fas fa-list-alt mr-2"></i> Categories</h2>
                <p class="text-lg font-bold">Categories <?php echo $category_count; ?></p>
            </div>
                <br>
        
        </div>
    </div>
</div>

</body>
</html>
