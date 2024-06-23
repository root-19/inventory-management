<?php
require_once __DIR__ . '/../config/configuration.php';

// sales data for today
$date = date('Y-m-d');
$sql = "SELECT p.name, s.quantity_sold, s.total_price, s.sale_date
        FROM sold_products s
        INNER JOIN add_product p ON s.product_id = p.id
        WHERE DATE(s.sale_date) = '$date'";
$result = $conn->query($sql);

$sales = array();
$total_profit = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
        $total_profit += $row['total_price'];
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
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-3xl font-bold mb-6">Sales Report - <?php echo date('F j, Y'); ?></h1>

    <?php if (!empty($sales)): ?>
        <div class="overflow-x-auto">
            <table class="table-auto border-collapse border border-gray-500">
                <thead>
                    <tr>
                        <th class="px-4 py-2 bg-gray-200">Product Name</th>
                        <th class="px-4 py-2 bg-gray-200">Quantity Sold</th>
                        <th class="px-4 py-2 bg-gray-200">Total Price</th>
                        <th class="px-4 py-2 bg-gray-200">Sale Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td class="px-4 py-2"><?php echo $sale['name']; ?></td>
                            <td class="px-4 py-2"><?php echo $sale['quantity_sold']; ?></td>
                            <td class="px-4 py-2"><?php echo $sale['total_price']; ?></td>
                            <td class="px-4 py-2"><?php echo date('F j, Y H:i:s', strtotime($sale['sale_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="mt-6 font-bold">Total Profit: <?php echo $total_profit; ?></p>
    <?php else: ?>
        <p>No sales recorded for today.</p>
    <?php endif; ?>
</body>
</html>
