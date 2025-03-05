<?php
session_start();
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/./admin.controller.php';

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    <style>
        .sidebar {
            width: 250px;
            transition: width 0.3s;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar.collapsed span {
            display: none;
        }
        .content {
            transition: margin-left 0.3s;
        }
        .content.expanded {
            margin-left: 80px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar bg-blue-800 text-white h-full fixed flex flex-col py-4 px-3">
            <button id="toggleSidebar" class="text-white text-lg mb-4 focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <nav class="space-y-3">
                <a href="admin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-user-shield"></i> <span class="ml-3">Admin</span>
                </a>
                <a href="add-product.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-plus-square"></i> <span class="ml-3">Add Products</span>
                </a>
                <a href="display-report.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-file-alt"></i> <span class="ml-3">Report</span>
                    <span class="ml-auto bg-red-600 text-xs px-2 py-0.5 rounded-full"> <?= $report_count ?> </span>
                </a>
                <a href="inventory.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-boxes"></i> <span class="ml-3">Inventory</span>
                </a>
                <a href="logout.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-red-600">
                    <i class="fas fa-sign-out-alt"></i> <span class="ml-3">Logout</span>
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div id="mainContent" class="content flex-1 ml-64 p-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-700"><i class="fas fa-list-alt mr-2"></i> Categories</h2>
                    <p class="text-lg font-bold mt-2">Total: <?= $category_count ?></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-700"><i class="fas fa-dollar-sign mr-2"></i> Total Sales</h2>
                    <p class="text-lg font-bold mt-2">Amount: <?= $formatted_total_sales ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('expanded');
        });
    </script>
</body>
</html>