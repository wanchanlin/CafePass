<?php
// Database configuration
$db_config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'database' => 'cafepass_db',
    'charset' => 'utf8mb4'
];

// Session configuration
$session_config = [
    'lifetime' => 3600, // 1 hour
    'path' => '/',
    'secure' => false, // Change to true for HTTPS
    'httponly' => true
];

// QR Code configuration
$qr_config = [
    'size' => 4, // QR Code size
    'padding' => 4, // Padding around QR Code
    'error_correction' => 'L' // Error correction level (L, M, Q, H)
];

// Points configuration
$points_config = [
    'visit_points' => 10, // Points earned per visit
    'event_points' => 20, // Points earned for attending an event
    'review_points' => 5 // Points earned for leaving a review
];
?>
