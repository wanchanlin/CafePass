<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

include('../reusable/connection.php');
include('../reusable/functions.php');

// Get user's total points
$user_id = $_SESSION['id'];
$points_query = "SELECT SUM(points_earned) as total_points FROM user_visits WHERE user_id = ?";
global $conn;
if (!$conn) {
    die("Database connection failed");
}

$points_stmt = $conn->prepare($points_query);
$points_stmt->bind_param("i", $user_id);
$points_stmt->execute();
$total_points = $points_stmt->get_result()->fetch_assoc()['total_points'] ?? 0;

// Get user's recent visits
$visits_query = "SELECT v.*, c.name as cafe_name, c.address, c.image_path 
                FROM user_visits v 
                INNER JOIN cafes c ON v.cafe_id = c.id 
                WHERE v.user_id = ? 
                ORDER BY v.visit_date DESC 
                LIMIT 3";
$visits_stmt = $conn->prepare($visits_query);
if (!$visits_stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$visits_stmt->bind_param("i", $user_id);
$visits_stmt->execute();
$recent_visits = $visits_stmt->get_result();
if (!$recent_visits) {
    die("Query failed: (" . $conn->errno . ") " . $conn->error);
}

// Get user's upcoming events
$events_query = "SELECT e.*, c.name as cafe_name, c.address 
                FROM events e 
                INNER JOIN cafes c ON e.cafe_id = c.id 
                INNER JOIN event_registrations r ON e.id = r.event_id 
                WHERE r.user_id = ? AND e.event_date > NOW() 
                ORDER BY e.event_date ASC 
                LIMIT 3";
$events_stmt = $conn->prepare($events_query);
if (!$events_stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$events_stmt->bind_param("i", $user_id);
$events_stmt->execute();
$upcoming_events = $events_stmt->get_result();
if (!$upcoming_events) {
    die("Query failed: (" . $conn->errno . ") " . $conn->error);
}

// Get user's recent reviews
$reviews_query = "SELECT r.*, c.name as cafe_name, c.address 
                 FROM reviews r 
                 INNER JOIN cafes c ON r.cafe_id = c.id 
                 WHERE r.user_id = ? 
                 ORDER BY r.review_date DESC 
                 LIMIT 3";
$reviews_stmt = $conn->prepare($reviews_query);
if (!$reviews_stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$reviews_stmt->bind_param("i", $user_id);
$reviews_stmt->execute();
$recent_reviews = $reviews_stmt->get_result();
if (!$recent_reviews) {
    die("Query failed: (" . $conn->errno . ") " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cafe Pass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/cafe.css">
</head>

<body class="bg-gray-50">
    <?php include('../reusable/userDbNav.php'); ?>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">



        <div class="max-w-7xl mx-auto gap-4 flex flex-row">
            <!-- Welcome Section -->
            <?php include('../reusable/userSidebar.php'); ?>
            <div>
                <div class="bg-white shadow-sm rounded-lg p-6 mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">Welcome back, <?php echo htmlspecialchars($_SESSION['first']); ?>!</h2>
                            <p class="mt-1 text-sm text-gray-500">Track your coffee journey and manage your activities</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Points</p>
                            <p class="text-3xl font-bold text-gray-900"><?php echo number_format($total_points); ?></p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <!-- Recent Visits -->
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Recent Visits</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <?php if (mysqli_num_rows($recent_visits) > 0): ?>
                                <?php while ($visit = mysqli_fetch_assoc($recent_visits)): ?>
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <img class="h-12 w-12 rounded-lg object-cover"
                                                    src="<?php echo htmlspecialchars($visit['image_path']); ?>"
                                                    alt="<?php echo htmlspecialchars($visit['cafe_name']); ?>">
                                            </div>
                                            <div class="ml-4">
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($visit['cafe_name']); ?>
                                                </h4>
                                                <p class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($visit['address']); ?>
                                                </p>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    Visited on <?php echo date('M d, Y', strtotime($visit['visit_date'])); ?>
                                                </p>
                                            </div>
                                            <div class="ml-auto text-right">
                                                <p class="text-sm font-medium text-gray-900">
                                                    <?php echo $visit['points_earned']; ?> points
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="p-6 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No visits yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Visit a cafe to start earning points.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Upcoming Events</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <?php if (mysqli_num_rows($upcoming_events) > 0): ?>
                                <?php while ($event = mysqli_fetch_assoc($upcoming_events)): ?>
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($event['title']); ?>
                                                </h4>
                                                <p class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($event['cafe_name']); ?> - <?php echo htmlspecialchars($event['address']); ?>
                                                </p>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    <?php echo date('M d, Y h:i A', strtotime($event['event_date'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="p-6 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming events</h3>
                                    <p class="mt-1 text-sm text-gray-500">Check out our events page to find something interesting.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent Reviews -->
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Recent Reviews</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <?php if (mysqli_num_rows($recent_reviews) > 0): ?>
                                <?php while ($review = mysqli_fetch_assoc($recent_reviews)): ?>
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($review['cafe_name']); ?>
                                                </h4>
                                                <p class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($review['address']); ?>
                                                </p>
                                                <div class="mt-1 flex items-center">
                                                    <div class="flex items-center">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <svg class="h-4 w-4 <?php echo $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <p class="ml-2 text-sm text-gray-600">
                                                        <?php echo date('M d, Y', strtotime($review['review_date'])); ?>
                                                    </p>
                                                </div>
                                                <?php if (!empty($review['comment'])): ?>
                                                    <p class="mt-2 text-sm text-gray-600">
                                                        <?php echo htmlspecialchars($review['comment']); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="p-6 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No reviews yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Share your experience by reviewing cafes you've visited.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>

</body>

</html>