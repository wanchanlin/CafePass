<?php
require_once '../reusable/connection.php';

// Get cafe owner information
function getCafeOwner($owner_id) {
    global $conn;
    $query = "SELECT c.*, u.first, u.last FROM cafe_owners c JOIN users u ON c.user_id = u.id WHERE c.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Get cafe information
function getCafe($owner_id) {
    global $conn;
    $query = "SELECT * FROM cafes WHERE owner_id = (SELECT id FROM cafe_owners WHERE user_id = ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Get cafe statistics
function getCafeStatistics($cafe_id) {
    global $conn;
    $stats = [];
    
    // Total visits
    $query = "SELECT COUNT(*) as visit_count FROM user_visits WHERE cafe_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cafe_id);
    $stmt->execute();
    $stats['total_visits'] = $stmt->get_result()->fetch_assoc()['visit_count'] ?? 0;
    
    // Active passes (last 30 days)
    $query = "SELECT COUNT(DISTINCT user_id) as active_passes FROM user_visits WHERE cafe_id = ? AND visit_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cafe_id);
    $stmt->execute();
    $stats['active_passes'] = $stmt->get_result()->fetch_assoc()['active_passes'] ?? 0;
    
    // Upcoming events
    $query = "SELECT COUNT(*) as event_count FROM events WHERE cafe_id = ? AND event_date > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cafe_id);
    $stmt->execute();
    $stats['upcoming_events'] = $stmt->get_result()->fetch_assoc()['event_count'] ?? 0;
    
    return $stats;
}

// Get recent visits
function getRecentVisits($owner_id, $days = 7) {
    global $conn;
    $query = "SELECT v.*, u.first, u.last 
              FROM user_visits v 
              JOIN users u ON v.user_id = u.id 
              WHERE v.cafe_id = (SELECT id FROM cafes WHERE owner_id = (SELECT id FROM cafe_owners WHERE user_id = ?)) 
              AND v.visit_date >= DATE_SUB(NOW(), INTERVAL ? DAY)
              ORDER BY v.visit_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $owner_id, $days);
    $stmt->execute();
    return $stmt->get_result();
}

// Get active passes
function getActivePasses($owner_id) {
    global $conn;
    $query = "SELECT DISTINCT u.id, u.first, u.last 
              FROM users u 
              JOIN user_visits v ON u.id = v.user_id 
              WHERE v.cafe_id = (SELECT id FROM cafes WHERE owner_id = (SELECT id FROM cafe_owners WHERE user_id = ?)) 
              AND v.visit_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Create new event
function createEvent($cafe_id, $title, $description, $event_date) {
    global $conn;
    $query = "INSERT INTO events (cafe_id, title, description, event_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $cafe_id, $title, $description, $event_date);
    return $stmt->execute();
}

// Update cafe information
function updateCafe($cafe_id, $name, $address, $description, $image_path = null) {
    global $conn;
    $query = "UPDATE cafes SET name = ?, address = ?, description = ?, image_path = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $name, $address, $description, $image_path, $cafe_id);
    return $stmt->execute();
}

// Get all events for a cafe
function getCafeEvents($cafe_id) {
    global $conn;
    $query = "SELECT * FROM events WHERE cafe_id = ? ORDER BY event_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cafe_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get event registrations
function getEventRegistrations($event_id) {
    global $conn;
    $query = "SELECT r.*, u.first, u.last 
              FROM event_registrations r 
              JOIN users u ON r.user_id = u.id 
              WHERE r.event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Register user for event
function registerForEvent($user_id, $event_id) {
    global $conn;
    $query = "INSERT INTO event_registrations (user_id, event_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $event_id);
    return $stmt->execute();
}
?>
