<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');

// Handle event registration/unregistration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['id'];
    $action = $_POST['action'];

    if ($action === 'register') {
        $query = "INSERT INTO event_registrations (user_id, event_id) VALUES (?, ?)";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("ii", $user_id, $event_id);
        $stmt->execute();
    } else if ($action === 'unregister') {
        $query = "DELETE FROM event_registrations WHERE user_id = ? AND event_id = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("ii", $user_id, $event_id);
        $stmt->execute();
    }
}

// Get all upcoming events with registration status
$user_id = $_SESSION['id'];
$query = "SELECT e.*, c.name as cafe_name, c.address, c.image_path,
          CASE WHEN r.id IS NOT NULL THEN 1 ELSE 0 END as is_registered
          FROM events e 
          INNER JOIN cafes c ON e.cafe_id = c.id 
          LEFT JOIN event_registrations r ON e.id = r.event_id AND r.user_id = ?
          WHERE e.event_date > NOW()
          ORDER BY e.event_date ASC";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$events = $stmt->get_result();
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
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="bg-white shadow-sm rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Upcoming Events</h2>
                        <p class="mt-1 text-sm text-gray-500">Discover and join exciting coffee events near you</p>
                    </div>
                </div>
            </div>

            <!-- Events Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php if (mysqli_num_rows($events) > 0): ?>
                    <?php while ($event = mysqli_fetch_assoc($events)): ?>
                        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                            <div class="aspect-w-16 aspect-h-9">
                                <img src="<?php echo htmlspecialchars($event['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($event['title']); ?>"
                                     class="w-full h-48 object-cover">
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <?php echo htmlspecialchars($event['title']); ?>
                                    </h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $event['is_registered'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                        <?php echo $event['is_registered'] ? 'Registered' : 'Open'; ?>
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-gray-600">
                                    <?php echo htmlspecialchars($event['cafe_name']); ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <?php echo htmlspecialchars($event['address']); ?>
                                </p>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600">
                                        <?php echo date('M d, Y h:i A', strtotime($event['event_date'])); ?>
                                    </p>
                                    <p class="mt-2 text-sm text-gray-600">
                                        <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                                    </p>
                                </div>
                                <div class="mt-6">
                                    <?php if ($event['is_registered']): ?>
                                        <form method="POST" action="" class="inline">
                                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                            <input type="hidden" name="action" value="unregister">
                                            <button type="submit"
                                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Unregister
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" action="" class="inline">
                                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                            <input type="hidden" name="action" value="register">
                                            <button type="submit"
                                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                Register Now
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-12">
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