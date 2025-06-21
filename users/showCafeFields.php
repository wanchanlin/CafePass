<?php
session_start();
include('../reusable/connection.php');

// Get the cafes table structure
$result = $conn->query("DESCRIBE cafes");

if ($result) {
    echo "<pre>";
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
    echo "</pre>";
}
?>
