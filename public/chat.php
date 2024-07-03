<?php
require_once __DIR__ . '/../config/configuration.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $response = [
        'message' => 'I didn\'t understand that.',
        'image' => null,
        'expiration' => null,
        'category' => null,
        'price' => null
    ];

    

    // Extract keywords from the message
    $keywords = explode(' ', strtolower($message));


    if (in_array('quantity', $keywords)) {
        // Example: "How many quantity of corn?"
        $productName = '';
        foreach ($keywords as $keyword) {
            if ($keyword != 'quantity' && $keyword != 'how' && $keyword != 'many' && $keyword != 'of' && $keyword != 'the') {
                $productName = $keyword;
                break;
            }
        }

        if ($productName) {
            $sql = "SELECT quantity FROM add_product WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $productName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $response['message'] = "The quantity of $productName is " . $row['quantity'];
            } else {
                $response['message'] = "Sorry, I couldn't find the quantity for $productName.";
            }

            $stmt->close();
        }
    } elseif (in_array('image', $keywords)) {
        // Example: "Can you send the image of corn?"
        $productName = '';
        foreach ($keywords as $keyword) {
            if ($keyword != 'image' && $keyword != 'can' && $keyword != 'you' && $keyword != 'send' && $keyword != 'the' && $keyword != 'of') {
                $productName = $keyword;
                break;
            }
        }

        if ($productName) {
            $sql = "SELECT image FROM add_product WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $productName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $response['message'] = "Here is the image of $productName.";            
                $response['image'] = '../admin/' . $row['image']; 
            } else {
                $response['message'] = "Sorry, I couldn't find the image for $productName.";
            }

            $stmt->close();
        }
    } elseif (in_array('expiration', $keywords)) {
        // Example: "What is the expiration of corn?"
        $productName = '';
        foreach ($keywords as $keyword) {
            if ($keyword != 'expiration' && $keyword != 'of' && $keyword != 'the' && $keyword != 'is' && $keyword != 'what') {
                $productName = $keyword;
                break;
            }
        }
        if ($productName) {
            $sql = "SELECT expiration FROM add_product WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $productName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $response['message'] = "The expiration of $productName is " . $row['expiration'];
            } else {
                $response['message'] = "Sorry, I couldn't find the expiration for $productName.";
            }
            $stmt->close();
        }
    } elseif (in_array('category', $keywords)) {
        // Example: "What is the category of corn?"
        $productName = '';
        foreach ($keywords as $keyword) {
            if ($keyword != 'category' && $keyword != 'of' && $keyword != 'the' && $keyword != 'is' && $keyword != 'what') {
                $productName = $keyword;
                break;
            }
        }
        if ($productName) {
            $sql = "SELECT category FROM add_product WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $productName);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $response['message'] = "The category of $productName is " . $row['category'];
            } else {
                $response['message'] = "Sorry, I couldn't find the category for $productName.";
            }
            $stmt->close();
        }
    } elseif (in_array('price', $keywords)) {
        // Example: "What is the price of corn?"
        $productName = '';
        foreach ($keywords as $keyword) {
            if ($keyword != 'price' && $keyword != 'of' && $keyword != 'the' && $keyword != 'is' && $keyword != 'what') {
                $productName = $keyword;
                break;
            }
        }
        if ($productName) {
            $sql = "SELECT price FROM add_product WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $productName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $response['message'] = "The price of $productName is ₱ " . $row['price'];
            } else {
                $response['message'] = "Sorry, I couldn't find the price for $productName.";
            }

            $stmt->close();
        }
    }

    echo json_encode($response);
    $conn->close();
}

