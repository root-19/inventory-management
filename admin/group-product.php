<?php
require_once __DIR__ . '/../config/configuration.php';

$sql = "SELECT id, category, name, image, quantity, description, price FROM add_product ORDER BY category";
$result = $conn->query($sql);

$products = array();
while ($row = $result->fetch_assoc()) {
    $category = $row['category'];
    if (!isset($products[$category])) {
        $products[$category] = array();
    }
    $products[$category][] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grouped Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-6xl mx-auto">
    <?php foreach ($products as $category => $categoryProducts): ?>
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4"><?php echo $category; ?></h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Description</th>
                        <th class="border border-gray-300 px-4 py-2">Price</th>
                        <th class="border border-gray-300 px-4 py-2">Quantity</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categoryProducts as $product): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $product['name']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $product['description']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $product['price']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $product['quantity']; ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="product-update.php?id=<?php echo $product['id']; ?>" class="text-blue-500 hover:underline mr-2">Edit</a>
                                <a href="product-delete.php?id=<?php echo $product['id']; ?>" class="text-red-500 hover:underline">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
