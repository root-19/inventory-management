<?php

function ValidateLogin($email, $password) {
    global $conn;
    $sql = "SELECT id, name, type FROM admin WHERE email = ? AND password = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    } else {
        return null;
    }
}

