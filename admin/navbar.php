<?php require_once __DIR__ . '/../config/configuration.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
  <style>
    .content {
      display: none;
    }
    .active {
      display: block;
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
        <a href="#" data-target="products" class="block py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-box-open mr-2"></i> Products
        </a>
        <a href="#" data-target="add-products" class="block py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-plus-square mr-2"></i> Add Products
        </a>
        <a href="#" data-target="report" class="block py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-file-alt mr-2"></i> Report
        </a>
        <a href="#" data-target="daily-income" class="block py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-dollar-sign mr-2"></i> Daily Income
        </a>
        <a href="#" data-target="group-supply" class="block py-2 px-4 rounded hover:bg-blue-600">
          <i class="fas fa-truck mr-2"></i> Group Supply
        </a>
      </nav>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>
</html>
