<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');
include('../reusable/functions.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cafe_id'])) {
        $user_id = $_SESSION['id'];
        $cafe_id = $_POST['cafe_id'];
        
        // Check if cafe is already in wishlist
        $check_query = "SELECT id FROM wishlist WHERE user_id = ? AND cafe_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ii", $user_id, $cafe_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows == 0) {
            // Add to wishlist
            $insert_query = "INSERT INTO wishlist (user_id, cafe_id, date_added) VALUES (?, ?, NOW())";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ii", $user_id, $cafe_id);
            
            if ($insert_stmt->execute()) {
                // Success - redirect back to search page with success message
                header("Location: searchCafes.php?success=1");
                exit();
            } else {
                // Error - redirect back with error message
                header("Location: searchCafes.php?error=1");
                exit();
            }
        } else {
            // Already in wishlist - redirect back with message
            header("Location: searchCafes.php?already_added=1");
            exit();
        }
    }
}

// If we get here, something went wrong
header("Location: searchCafes.php");
exit();
