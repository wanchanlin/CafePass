<?php
require_once '../reusable/connection.php';
require_once 'db_helper.php';
session_start();

// Check if user is logged in and is a cafe owner
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'cafe_owner') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit();
}

// Get cafe owner's cafe
$owner_id = $_SESSION['id'];
$cafe = getCafe($owner_id);

// Get QR code data from POST request
$input = json_decode(file_get_contents('php://input'), true);
$qr_data = isset($input['code']) ? $input['code'] : null;

// Validate QR code data
if (empty($qr_data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid QR code data']);
    exit();
}

// Extract user ID from QR code data
$user_id = intval($qr_data);

// Check if user exists
$query = "SELECT id FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit();
}

// Record the visit
$points = 10; // Default points for a visit
$query = "INSERT INTO user_visits (user_id, cafe_id, points_earned) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $user_id, $cafe['id'], $points);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Visit recorded successfully!',
        'points' => $points,
        'visit_date' => date('Y-m-d H:i:s')
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error recording visit']);
}
?>
