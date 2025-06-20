<?php
require_once '../reusable/connection.php';
require_once __DIR__ . '/db_helper.php';
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

// Get statistics using helper functions
$stats = getCafeStatistics($owner_id);
$recent_visits = getRecentVisits($owner_id, 7);
$active_passes = getActivePasses($owner_id);

// Get visit data for the last 30 days
$visits_query = "SELECT DATE(visit_date) as visit_date, COUNT(*) as total_visits 
                FROM user_visits 
                WHERE cafe_id = (SELECT id FROM cafes WHERE owner_id = (SELECT id FROM cafe_owners WHERE user_id = ?)) 
                AND visit_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(visit_date)
                ORDER BY visit_date";
$visits_stmt = $conn->prepare($visits_query);
$visits_stmt->bind_param("i", $owner_id);
$visits_stmt->execute();
$visits_data = $visits_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get active passes data for the last 30 days
$passes_query = "SELECT COUNT(DISTINCT user_id) as total_passes 
                FROM user_visits 
                WHERE cafe_id = (SELECT id FROM cafes WHERE owner_id = (SELECT id FROM cafe_owners WHERE user_id = ?)) 
                AND visit_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$passes_stmt = $conn->prepare($passes_query);
$passes_stmt->bind_param("i", $owner_id);
$passes_stmt->execute();
$passes_result = $passes_stmt->get_result();
$passes_data = $passes_result->fetch_assoc();
if (!$passes_data) {
    $passes_data = ['total_passes' => 0];
}

// Get events
$events_query = "SELECT e.*, COUNT(r.id) as registrations 
                FROM events e 
                LEFT JOIN event_registrations r ON e.id = r.event_id 
                WHERE e.cafe_id = ? 
                GROUP BY e.id 
                ORDER BY e.event_date DESC 
                LIMIT 5";
$stmt = $conn->prepare($events_query);
$stmt->bind_param("i", $cafe['id']);
$stmt->execute();
$events_result = $stmt->get_result();
$events = $events_result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Data - CoffeePass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../styles/cafe.css">
</head>
<body class="bg-gray-100">
    <?php include '../reusable/userDbNav.php'; ?>   

    <div class="flex flex-row gap-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php include '../reusable/cafeSideBar.php'; ?>    
    <div class="flex-1">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Analytics & Reports</h1>
                <a href="dashboard.php" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Visits -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-600 text-sm">Total Visits</h2>
                            <p class="text-2xl font-semibold text-gray-900">
                                <?php echo isset($visits_data) ? array_sum(array_column($visits_data, 'total_visits')) : 0; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Active Passes -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-ticket-alt text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-600 text-sm">Active Passes</h2>
                            <p class="text-2xl font-semibold text-gray-900">
                                <?php echo $passes_data['total_passes']; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Average Daily Visits -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-600 text-sm">Avg. Daily Visits</h2>
                            <p class="text-2xl font-semibold text-gray-900">
                                <?php 
                                $total_visits = array_sum(array_column($visits_data, 'total_visits'));
                                $days = count($visits_data);
                                echo $days > 0 ? round($total_visits / $days, 1) : 0;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visit Trends Chart -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Visit Trends (Last 7 Days)</h2>
                <canvas id="visitsChart" height="100"></canvas>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
                <div class="space-y-4">
                    <?php if (empty($visits_data)): ?>
                        <p class="text-gray-500 text-center">No recent activity</p>
                    <?php else: ?>
                        <?php foreach ($visits_data as $visit): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">
                                            <?php echo $visit['total_visits']; ?> visits
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            <?php echo date('F j, Y', strtotime($visit['visit_date'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php include '../reusable/footer.php'; ?>

    <script>
        // Visit Trends Chart
        const ctx = document.getElementById('visitsChart').getContext('2d');
        const visitsData = <?php echo json_encode($visits_data); ?>;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: visitsData.map(item => item.visit_date),
                datasets: [{
                    label: 'Daily Visits',
                    data: visitsData.map(item => item.total_visits),
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html> 