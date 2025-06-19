<?php

require_once '../reusable/connection.php';
require_once 'db_helper.php';
session_start();

// Check if user is logged in and is a cafe owner
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'cafe_owner') {
    header("Location: /capstone/CoffeePass/login.php");
    exit();
}

// Get cafe owner's information
$owner_id = $_SESSION['id'];
$owner = getCafeOwner($owner_id);

// Get cafe information
$cafe = getCafe($owner_id);

// Get statistics
$stats = getCafeStatistics($owner_id);
$total_visits = $stats['total_visits'];
$active_passes = $stats['active_passes'];
$upcoming_events = $stats['upcoming_events'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Owner Dashboard - CoffeePass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <?php include '../reusable/userDbNav.php'; ?>
    
    <div class="flex flex-row gap-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php include '../reusable/cafeSideBar.php'; ?>
        <div>
        <div>

            <!-- Welcome Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Welcome, <?php echo isset($owner) && isset($owner['first']) && isset($owner['last']) ? htmlspecialchars($owner['first'] . ' ' . $owner['last']) : 'Cafe Owner'; ?></h1>
                <p class="text-gray-600 mt-2">Manage your cafe and track customer visits</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Visits -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-600 text-sm">Total Visits</h2>
                            <p class="text-2xl font-semibold text-gray-900">0</p>
                        </div>
                    </div>
                </div>

                <!-- Active Passes -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-ticket-alt text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-600 text-sm">Active Passes</h2>
                            <p class="text-2xl font-semibold text-gray-900">0</p>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-600 text-sm">Upcoming Events</h2>
                            <p class="text-2xl font-semibold text-gray-900">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Scan QR -->
                <a href="scan.php" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-qrcode text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-900 font-semibold">Scan QR</h2>
                            <p class="text-gray-600 text-sm">Scan customer passes</p>
                        </div>
                    </div>
                </a>

                <!-- Edit Cafe -->
                <a href="edit-cafe.php" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-store text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-900 font-semibold">Edit Cafe</h2>
                            <p class="text-gray-600 text-sm">Update cafe information</p>
                        </div>
                    </div>
                </a>

                <!-- View Data -->
                <a href="view-data.php" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-chart-bar text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-900 font-semibold">View Data</h2>
                            <p class="text-gray-600 text-sm">Analytics and reports</p>
                        </div>
                    </div>
                </a>

                <!-- Create Events -->
                <a href="create-events.php" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-calendar-plus text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-900 font-semibold">Create Events</h2>
                            <p class="text-gray-600 text-sm">Manage cafe events</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    </div>
    <script>
        // Add any necessary JavaScript here
    </script>
</body>

</html>