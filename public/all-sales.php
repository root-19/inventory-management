<?php
require_once __DIR__ . '/../config/configuration.php';

//get today's date
$date = date('Y-m-d');
$current_month_start = date('Y-m-01');
$next_month_start = date('Y-m-d', strtotime('first day of next month'));
$next_month_end = date('Y-m-t', strtotime('first day of next month'));

//fetch sales data for the current month
$sql_current_month = "SELECT p.name, s.quantity_sold, s.total_price, s.sale_date
                      FROM sold_products s
                      INNER JOIN add_product p ON s.product_id = p.id
                      WHERE s.sale_date >= '$current_month_start' AND s.sale_date < '$next_month_start'
                      ORDER BY s.sale_date DESC";
$result_current_month = $conn->query($sql_current_month);

//data for the next month
$sql_next_month = "SELECT p.name, s.quantity_sold, s.total_price, s.sale_date
                   FROM sold_products s
                   INNER JOIN add_product p ON s.product_id = p.id
                   WHERE s.sale_date >= '$next_month_start' AND s.sale_date <= '$next_month_end'
                   ORDER BY s.sale_date DESC";
$result_next_month = $conn->query($sql_next_month);

$sales_current_month = array();
$sales_next_month = array();
$total_profit_current_month = 0;
$total_profit_next_month = 0;

// Fetch and calculate sales data for the current month
if ($result_current_month->num_rows > 0) {
    while ($row = $result_current_month->fetch_assoc()) {
        $sales_current_month[] = $row;
        $total_profit_current_month += $row['total_price'];
    }
}

// Fetch and calculate sales data for the next month
if ($result_next_month->num_rows > 0) {
    while ($row = $result_next_month->fetch_assoc()) {
        $sales_next_month[] = $row;
        $total_profit_next_month += $row['total_price'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 h-screen bg-blue-800 text-white flex flex-col fixed">
            <div class="px-6 py-4">
                <h2 class="text-xl font-bold">Admin</h2>
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
                <a href="group-product.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                    <i class="fas fa-truck mr-2"></i> Group Supply
                </a>
                <a href="logout.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </div>
        
        <!-- Main content -->
        <div class="flex-1 ml-64 p-6">
            <h1 class="text-3xl font-bold mb-6">Sales Report</h1>

            <h2 class="text-2xl font-bold mb-4">Current Month Sales (<?php echo date('F Y'); ?>)</h2>
            <?php if (!empty($sales_current_month)): ?>
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="w-full bg-gray-200 text-left text-sm leading-normal">
                                <th class="py-3 px-4 border-b border-gray-300">Product Name</th>
                                <th class="py-3 px-4 border-b border-gray-300">Quantity Sold</th>
                                <th class="py-3 px-4 border-b border-gray-300">Total Price</th>
                                <th class="py-3 px-4 border-b border-gray-300">Sale Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach ($sales_current_month as $sale): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-4"><?php echo $sale['name']; ?></td>
                                    <td class="py-3 px-4"><?php echo $sale['quantity_sold']; ?></td>
                                    <td class="py-3 px-4"><?php echo $sale['total_price']; ?></td>
                                    <td class="py-3 px-4"><?php echo date('F j, Y H:i:s', strtotime($sale['sale_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="mt-6 text-xl font-bold">Total Profit: <?php echo $total_profit_current_month; ?></p>
            <?php else: ?>
                <p class="text-lg">No sales recorded for this month.</p>
            <?php endif; ?>

            <h2 class="text-2xl font-bold mb-4 mt-8">Next Month Sales (<?php echo date('F Y', strtotime('first day of next month')); ?>)</h2>
            <?php if (!empty($sales_next_month)): ?>
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="w-full bg-gray-200 text-left text-sm leading-normal">
                                <th class="py-3 px-4 border-b border-gray-300">Product Name</th>
                                <th class="py-3 px-4 border-b border-gray-300">Quantity Sold</th>
                                <th class="py-3 px-4 border-b border-gray-300">Total Price</th>
                                <th class="py-3 px-4 border-b border-gray-300">Sale Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach ($sales_next_month as $sale): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-4"><?php echo $sale['name']; ?></td>
                                    <td class="py-3 px-4"><?php echo $sale['quantity_sold']; ?></td>
                                    <td class="py-3 px-4"><?php echo $sale['total_price']; ?></td>
                                    <td class="py-3 px-4"><?php echo date('F j, Y H:i:s', strtotime($sale['sale_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="mt-6 text-xl font-bold">Total Profit: <?php echo $total_profit_next_month; ?></p>
            <?php else: ?>
                <p class="text-lg">No sales recorded for next month.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
