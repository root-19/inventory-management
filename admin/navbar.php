<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

  <!-- Sidebar -->
  <div class="flex">
    <div class="w-64 h-screen bg-blue-800 text-white flex flex-col fixed">
      <div class="px-6 py-4">
        <h2 class="text-xl font-bold">admin</h2>
      </div>
      <nav class="flex-1 px-4 py-2 space-y-2">
        <a href="#products" class="block py-2 px-4 rounded hover:bg-blue-600">Products</a>
        <a href="#add-product.php" class="block py-2 px-4 rounded hover:bg-blue-600">Add Products</a>
        <a href="#report" class="block py-2 px-4 rounded hover:bg-blue-600">Report</a>
        <a href="#daily-income" class="block py-2 px-4 rounded hover:bg-blue-600">Daily Income</a>
        <a href="#group-supply" class="block py-2 px-4 rounded hover:bg-blue-600">Group Supply</a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 ml-64 p-8">
      <h1 class="text-3xl font-bold">Welcome to the Dashboard</h1>
     
  </div>

</body>
</html>
