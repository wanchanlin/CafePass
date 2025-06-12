<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');
include('../reusable/functions.php');

// Get user's total points
$user_id = $_SESSION['id'];
$points_query = "SELECT SUM(points_earned) as total_points FROM user_visits WHERE user_id = ?";
$points_stmt = $connect->prepare($points_query);
$points_stmt->bind_param("i", $user_id);
$points_stmt->execute();
$total_points = $points_stmt->get_result()->fetch_assoc()['total_points'] ?? 0;

// Get all events
$query = "SELECT e.*, c.name as cafe_name, c.address, c.image_path 
          FROM events e 
          INNER JOIN cafes c ON e.cafe_id = c.id 
          WHERE e.event_date >= CURDATE() 
          ORDER BY e.event_date ASC";
$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Cafe Pass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/cafe.css">
</head>
<body class="bg-gray-50">
    <?php include('../reusable/userDbNav.php'); ?>
    
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto gap-4 flex flex-row">
            <!-- Sidebar -->
            <?php include('../reusable/userSidebar.php'); ?>
            
            <div>
                <!-- Points Summary -->
                <div class="bg-white shadow-sm rounded-lg p-6 mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">Upcoming Events</h2>
                            <p class="mt-1 text-sm text-gray-500">Discover coffee events and workshops near you</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Points</p>
                            <p class="text-3xl font-bold text-gray-900"><?php echo number_format($total_points); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Events List -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php while ($event = mysqli_fetch_assoc($result)): ?>
                        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                            <div class="relative h-48">
                                <img src="<?php echo htmlspecialchars($event['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($event['title']); ?>"
                                     class="w-full h-full object-cover">
                                <div class="absolute top-4 right-4">
                                    <div class="bg-white bg-opacity-90 rounded-full px-3 py-1">
                                        <p class="text-sm font-medium text-gray-900">
                                            <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <?php echo htmlspecialchars($event['title']); ?>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    <?php echo htmlspecialchars($event['cafe_name']); ?>
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    <?php echo htmlspecialchars($event['address']); ?>
                                </p>
                                <p class="mt-2 text-sm text-gray-600">
                                    <?php echo htmlspecialchars($event['description']); ?>
                                </p>
                                <div class="mt-4 flex justify-between items-center">
                                    <div class="text-sm text-gray-500">
                                        <p>Time: <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                                        <p>Price: $<?php echo number_format($event['price'], 2); ?></p>
                                    </div>
                                    <form method="POST" action="registerEvent.php" class="inline">
                                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                            Register
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php if (mysqli_num_rows($result) === 0): ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming events</h3>
                        <p class="mt-1 text-sm text-gray-500">Check back later for new events.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>
</body>
</html> 