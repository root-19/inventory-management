<?php
session_start();
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/./admin.controller.php';

// Fetch the count of reports
$sql_count_reports = "SELECT COUNT(*) as report_count FROM product_reports";
$result_count = $conn->query($sql_count_reports);
$report_count = $result_count->fetch_assoc()['report_count'];

// Query to count products in each category
$sql = "SELECT category, COUNT(*) AS count FROM add_product GROUP BY category";
$result = $conn->query($sql);

$categoryLabels = [];
$categoryCounts = [];

while ($row = $result->fetch_assoc()) {
    $categoryLabels[] = $row['category'];
    $categoryCounts[] = $row['count'];
}

// Fetch total sales data for chart
$sql_fetch_sales = "SELECT DATE_FORMAT(order_date, '%M %Y') AS month, SUM(total_price) AS total_sales FROM sold_products GROUP BY month ORDER BY MIN(order_date)";
$result_sales = $conn->query($sql_fetch_sales);

$salesLabels = [];
$salesData = [];

while ($row = $result_sales->fetch_assoc()) {
    $salesLabels[] = $row['month'];
    $salesData[] = $row['total_sales'];
}

// Fetch total sales amount
$sql_fetch_total_sales = "SELECT SUM(total_price) AS total_sales FROM sold_products";
$result_total_sales = $conn->query($sql_fetch_total_sales);
$total_sales = $result_total_sales->fetch_assoc()['total_sales'];
$formatted_total_sales = number_format($total_sales, 2);

// Fetch the category count
$category_count_sql = "SELECT COUNT(*) as count FROM categories";
$category_count_result = $conn->query($category_count_sql);
$category_count = $category_count_result->fetch_assoc()['count'] ?? 0;

// Fetch best-selling products (JOIN add_product & sold_products)
$sql_best_sellers = "
    SELECT add_product.name, SUM(sold_products.quantity_sold) AS total_sold 
    FROM sold_products 
    JOIN add_product ON sold_products.product_id = add_product.id 
    GROUP BY add_product.name 
    ORDER BY total_sold DESC 
    LIMIT 5";

$result_best_sellers = $conn->query($sql_best_sellers);

$bestSellerNames = [];
$bestSellerSales = [];

while ($row = $result_best_sellers->fetch_assoc()) {
    $bestSellerNames[] = $row['name'];
    $bestSellerSales[] = $row['total_sold'];
}

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
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet">
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar { width: 250px; transition: width 0.3s; }
        .sidebar.collapsed { width: 80px; }
        .sidebar.collapsed span { display: none; }
        .content { transition: margin-left 0.3s; }
        .content.expanded { margin-left: 80px; }
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
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-700"><i class="fas fa-list-alt mr-2"></i> Categories</h2>
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-700"><i class="fas fa-dollar-sign mr-2"></i> Total Sales</h2>
                    <p class="text-lg font-bold mt-2">₱<?= $formatted_total_sales ?></p>
                    <canvas id="salesChart"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-700"><i class="fas fa-star mr-2"></i> Best Sellers</h2>
                    <canvas id="bestSellerChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('expanded');
        });

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($categoryLabels) ?>,
                datasets: [{
                    label: 'Product Count',
                    data: <?= json_encode($categoryCounts) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($salesLabels) ?>,
                datasets: [{
                    label: 'Total Sales',
                    data: <?= json_encode($salesData) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Best Seller Chart
        const bestSellerCtx = document.getElementById('bestSellerChart').getContext('2d');
        new Chart(bestSellerCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($bestSellerNames) ?>,
                datasets: [{
                    label: 'Total Sold',
                    data: <?= json_encode($bestSellerSales) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>
