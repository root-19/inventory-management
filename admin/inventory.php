<?php
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../includes/functions.php';

// Check product quantity and send email alert if necessary
checkProductQuantityAndSendAlert($conn);

// Fetch products from the database
$sql = "SELECT name, category, image, expiration, quantity, original_quantity, price FROM add_product";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Management</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
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
        <a href="add-product.php"  class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-plus-square mr-2"></i> Add Products
        </a>
        <a href="display-report.php" data-target="report" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-file-alt mr-2"></i> Report
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
</a
        <a href="logout.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="main w-full flex justify-center items-center min-h-screen">
      <div class="w-full max-w-4xl bg-white p-8 rounded-lg shadow-lg mt-10">
        <h2 class="text-2xl font-bold mb-6">Inventory</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-200">
            <thead>
              <tr class="bg-gray-200">
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">Name</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">Category</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">Image</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">Expiration</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">sold</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">orig quantity</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">Price</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td class="py-2 px-4 border-b border-gray-200"><?php echo $row['name']; ?></td>
                <td class="py-2 px-4 border-b border-gray-200"><?php echo $row['category']; ?></td>
                <td class="py-2 px-4 border-b border-gray-200">
                  <?php if (!empty($row['image'])): ?>
                  <img src="../<?php echo $row['image']; ?>" alt="Product Image" class="w-16 h-16 object-cover">
                  <?php else: ?>
                  No image
                  <?php endif; ?>
                </td>
                <td class="py-2 px-4 border-b border-gray-200"><?php echo $row['expiration']; ?></td>
                <td class="py-2 px-4 border-b border-gray-200"><?php echo $row['quantity']; ?></td>
                <td class="py-2 px-4 border-b border-gray-200"><?php echo $row['original_quantity']; ?></td>
                <td class="py-2 px-4 border-b border-gray-200"><?php echo $row['price']; ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Management</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
     .main {
      margin-left:220px;
    }
 </style>

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
        <a href="inventory.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-boxes mr-2"></i> Inventory
        </a>
        <a href="daily-report.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-file-alt mr-2"></i> Report
        </a>
        <a href="daily-income.php" class="flex items-center py-2 px-4 rounded hover:bg-blue-600">
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

    <!-- Main Content -->
    <div class="main w-full flex justify-center items-center min-h-screen">
      <div class="w-full max-w-4xl bg-white p-8 rounded-lg shadow-lg mt-10">
        <h2 class="text-2xl font-bold mb-6">Inventory</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-200">
            <thead>
              <tr class="bg-gray-200">
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">Name</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">Category</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">Image</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">Expiration Date</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">sold</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">orig Qunatity</th>
                <th class="w-1/6 py-2 px-4 border-b border-gray-200">Price</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($row['category']); ?></td>
                    <td class="py-2 px-4 border-b border-gray-200">
                      <?php if ($row['image']): ?>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" class="h-16 w-16 object-cover">
                      <?php else: ?>
                        No Image
                      <?php endif; ?>
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($row['expiration']); ?></td>
                    <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($row['original_quantity']); ?></td>
                    <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($row['price']); ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td class="py-2 px-4 border-b border-gray-200 text-center" colspan="7">No products found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

<?php
$conn->close();
?>
