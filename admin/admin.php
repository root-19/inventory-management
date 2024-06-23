<?php
require_once __DIR__ . '/../config/configuration.php';

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
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
    <div class="w-64 h-screen bg-blue-800 text-white flex flex-col fixed ">
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
                <a href="?logout=true" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </nav>
    </div> 

<div class="container mx-auto px-4 py-8 ml-64">
        <h1 class="text-2xl font-bold mb-4">Product Counts</h1>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold mb-2">Category Counts</h2>
                <ul class="list-disc list-inside">
                    <?php
                    // Display category counts
                    foreach ($categoryCounts as $category => $count) {
                        echo "<li>$category: $count</li>";
                    }
                    ?>
                </ul>
            </div>
           
        </div>
    </div>
</body>
</html>
