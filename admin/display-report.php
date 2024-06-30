<?php
require_once __DIR__ . '/../config/configuration.php';
require '../vendor/autoload.php';

// Fetch the count of reports
$sql_count_reports = "SELECT COUNT(*) as report_count FROM product_reports";
$result_count = $conn->query($sql_count_reports);
$report_count = $result_count->fetch_assoc()['report_count'];

// Fetch the count of reports
$sql_count_reports = "SELECT COUNT(*) as report_count FROM product_reports";
$result_count = $conn->query($sql_count_reports);
$report_count = $result_count->fetch_assoc()['report_count'];

// Fetch all reports from the database
$sql_fetch_reports = "SELECT id, product_name, message, report_date FROM product_reports ORDER BY report_date DESC";
$result = $conn->query($sql_fetch_reports);

// Handle report deletion
if (isset($_POST['delete_report'])) {
    $report_id = $_POST['report_id'];

    $sql_delete_report = "DELETE FROM product_reports WHERE id = ?";
    $stmt = $conn->prepare($sql_delete_report);
    $stmt->bind_param('i', $report_id);
    $stmt->execute();
    
    // Redirect to refresh the page after deletion
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reports</title>
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<style>
    .main{
    padding-left: 300px;
    }
</style>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex">
        <div class="w-64 h-screen bg-blue-800 text-white flex flex-col fixed">
            <div class="px-6 py-4">
                <!-- <h2 class="text-xl font-bold">Admin Panel</h2> -->
            </div>
              <nav class="flex-1 px-4 py-2 space-y-2">
        <a href="admin.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-user-shield mr-2"></i> Admin
        </a>
        <a href="add-product.php"  class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-plus-square mr-2"></i> Add Products
        </a>
          <a href="display-report.php" data-target="report" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-file-alt mr-2"></i> Report 
                <span class="ml-2 bg-red-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full"><?= $report_count ?></span>
            </a>
         <a href="inventory.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-boxes mr-2"></i> Inventory
        </a>
        <a href="daily-income.php" data-target="daily-income" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
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
        <div class="ml-64 flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Product Reports</h1>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Date</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            <!-- Added Actions Column -->
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$row['product_name']}</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['message']}</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>{$row['report_date']}</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>
                                        <form method='post'>
                                            <input type='hidden' name='report_id' value='{$row['id']}'>
                                            <button type='submit' name='delete_report' class='text-red-500 hover:text-red-700'>Delete</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>No reports found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>