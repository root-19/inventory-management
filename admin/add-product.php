<?php
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/validation.php';
require_once __DIR__ . '/../includes/functions.php';

// Fetch categories from the database
$category_query = "SELECT name FROM categories";
$category_result = $conn->query($category_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $quantity = intval($_POST['quantity']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $expiration = $_POST['expiration'];
    $barcode = '';

    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFileName = uniqid() . "_" . basename($_FILES['image']['name']);
        $imageTempName = $_FILES['image']['tmp_name'];
        $imageUploadPath = __DIR__ . "/uploads/" . $imageFileName;

        if (move_uploaded_file($imageTempName, $imageUploadPath)) {
            $image = "uploads/" . $imageFileName;
        }
    }

    $barcode = ''; // Initialize barcode variable

    // Save barcode image
    if (!empty($_POST['barcodeImage'])) {
        $barcodeFileName = "barcode_" . uniqid() . ".png";
        $barcodeFilePath = "uploads/" . $barcodeFileName; // Remove leading '/'
    
        $barcodeImage = str_replace(['data:image/png;base64,', ' '], ['', '+'], $_POST['barcodeImage']);
        $barcodeData = base64_decode($barcodeImage);
    
        if (file_put_contents(__DIR__ . "/" . $barcodeFilePath, $barcodeData)) {
            $barcode = $barcodeFilePath; // Assign correct path to $barcode
        }
    }
    
    // Insert product into database using prepared statements
    $stmt = $conn->prepare("INSERT INTO add_product (name, category, image, expiration, quantity, description, price, barcode) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("ssssisss", $name, $category, $image, $expiration, $quantity, $description, $price, $barcode);

if ($stmt->execute()) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>";
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Product added successfully with a barcode.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'add-product.php';
        });
    </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

  }    
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Management</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 h-full bg-blue-800 text-white flex flex-col fixed transition-transform duration-300 ease-in-out">
      <div class="p-4 text-center text-xl font-bold">Admin Panel</div>
      <nav class="flex-1 space-y-2 p-4">
        <a href="admin.php" class="flex items-center p-2 rounded-lg hover:bg-blue-600"><i class="fas fa-user-shield mr-2"></i> Admin</a>
        <a href="add-product.php" class="flex items-center p-2 rounded-lg hover:bg-blue-600"><i class="fas fa-plus-square mr-2"></i> Add Product</a>
        <a href="display-report.php" class="flex items-center p-2 rounded-lg hover:bg-blue-600"><i class="fas fa-file-alt mr-2"></i> Report</a>
        <a href="inventory.php" class="flex items-center p-2 rounded-lg hover:bg-blue-600"><i class="fas fa-boxes mr-2"></i> Inventory</a>
        <a href="daily-income.php" class="flex items-center p-2 rounded-lg hover:bg-blue-600"><i class="fas fa-dollar-sign mr-2"></i> Daily Income</a>
        <a href="group-product.php" class="flex items-center p-2 rounded-lg hover:bg-blue-600"><i class="fas fa-truck mr-2"></i> Group Supply</a>
        <a href="logout.php" class="flex items-center p-2 rounded-lg hover:bg-red-600"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex justify-center items-center pl-64 p-8">
      <div class="w-full max-w-4xl bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">Add New Product</h2>
        <form action="" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-6">
          <div>
            <label class="block text-gray-700 font-medium">Product Name</label>
            <input type="text" name="name" placeholder="Enter product name" class="input input-bordered w-full" required>
          </div>
          <div>
            <label class="block text-gray-700 font-medium">Category</label>
            <select name="category" class="select select-bordered w-full" required>
              <?php while($row = $category_result->fetch_assoc()) { echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>"; } ?>
            </select>
          </div>
          <div>
            <label class="block text-gray-700 font-medium">Quantity</label>
            <input type="number"  name="quantity" class="input input-bordered w-full" required>
          </div>
          <div>
            <label class="block text-gray-700 font-medium">Price</label>
            <input type="number" step="0.01" name="price" class="input input-bordered w-full" required>
          </div>
          <div>
            <label class="block text-gray-700 font-medium">Expiration Date</label>
            <input type="date" name="expiration" class="input input-bordered w-full" required>
          </div>
          <div>
            <label class="block text-gray-700 font-medium">Product Image</label>
            <input type="file" name="image" class="file-input file-input-bordered w-full" required>
          </div>
          <div class="col-span-2">
            <label class="block text-gray-700 font-medium">Description</label>
            <textarea name="description" class="textarea textarea-bordered w-full" required></textarea>
          </div>
          <input type="text" name="barcode" id="barcode" class="input input-bordered w-full" required readonly>
<svg id="barcodeSvg" class="mt-4"></svg>
<input type="hidden" name="barcodeImage" id="barcodeImage">

   
          <div class="col-span-2">
            <button type="submit" class="w-full btn btn-primary">Add Product</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
  <script>
let productDatabase = {}; // Temporary storage for testing

document.querySelector("form").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission

    generateBarcode(); // Generate barcode first

    // Save barcode as an image, then submit the form
    saveBarcodeAsImage(() => {
        setTimeout(() => {
            document.querySelector("form").submit();
        }, 1000); // Delay to ensure image is processed
    });
});

function generateBarcode() {
    let name = document.querySelector("input[name='name']").value.trim();
    let price = document.querySelector("input[name='price']").value.trim();
    let expiration = document.querySelector("input[name='expiration']").value.trim();
    let quantity = document.querySelector("input[name='quantity']").value.trim();
    let description = document.querySelector("textarea[name='description']").value.trim();

    if (!name || !price || !expiration || !quantity || !description) {
        alert("Please fill in all fields!");
        return;
    }

    // ✅ Store product details in a structured text format
    let barcodeData = `${name}|${price}|${expiration}|${quantity}|${description}`;

    // ✅ Store product details with barcode as key
    productDatabase[barcodeData] = { name, price, expiration, quantity, description };

    // Generate Code 128 barcode
    JsBarcode("#barcodeSvg", barcodeData, {
        format: "CODE128",
        displayValue: true, // Show text under barcode
        width: 2,
        height: 50,
        margin: 10
    });

    setTimeout(saveBarcodeAsImage, 500);
}

function saveBarcodeAsImage(callback) {
    let svg = document.getElementById("barcodeSvg");
    let serializer = new XMLSerializer();
    let svgString = serializer.serializeToString(svg);

    let canvas = document.createElement("canvas");
    let ctx = canvas.getContext("2d");
    let img = new Image();

    img.onload = function () {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);

        let barcodeImage = canvas.toDataURL("image/png");
        document.getElementById("barcodeImage").value = barcodeImage;

        if (typeof callback === "function") {
            callback();
        }
    };

    img.src = "data:image/svg+xml;base64," + window.btoa(unescape(encodeURIComponent(svgString)));
}

// ✅ Scan barcode and display product details
function scanBarcode() {
    let scannedCode = document.querySelector("#scannedBarcode").value.trim();

    // Check if barcode exists in our database
    if (productDatabase[scannedCode]) {
        let product = productDatabase[scannedCode];

        // ✅ Display the product details
        document.getElementById("displayName").textContent = `Product Name: ${product.name}`;
        document.getElementById("displayPrice").textContent = `Price: ₱${product.price}`;
        document.getElementById("displayExpiration").textContent = `Expiration: ${product.expiration}`;
        document.getElementById("displayQuantity").textContent = `Quantity: ${product.quantity}`;
        document.getElementById("displayDescription").textContent = `Description: ${product.description}`;
    } else {
        alert("❌ Product not found! Please ensure the barcode is correct.");
    }
}


</script>



</body>
</html>