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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $capacity = $_POST['capacity'];
    $price = $_POST['price'];

    // Combine date and time
    $event_datetime = $event_date . ' ' . $start_time;

    if (createEvent($cafe['id'], $title, $description, $event_datetime, $end_time, $capacity, $price)) {
        $success_message = "Event created successfully!";
    } else {
        $error_message = "Error creating event.";
    }
}

// Get existing events
$events_query = "SELECT * FROM events WHERE cafe_id = ? ORDER BY event_date DESC";
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
    <title>Create Events - CoffeePass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../styles/cafe.css">
</head>
<body class="bg-gray-100">
    <?php include '../reusable/userDbNav.php'; ?>

    <div class="flex flex-row gap-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php include '../reusable/cafeSideBar.php'; ?>    
    <div class="flex-1">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Create Events</h1>
                
            </div>

            <?php if (isset($success_message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $success_message; ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $error_message; ?></span>
                </div>
            <?php endif; ?>

            <!-- Create Event Form -->
            <form method="POST" class="space-y-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Event Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
                        <input type="text" name="title" id="title" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Event Date -->
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700">Event Date</label>
                        <input type="date" name="event_date" id="event_date" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="time" name="start_time" id="start_time" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="time" name="end_time" id="end_time" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                        <input type="number" name="capacity" id="capacity" required min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" name="price" id="price" required min="0" step="0.01"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class=" px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Create Event
                    </button>
                </div>
            </form>

            <!-- Existing Events -->
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Existing Events</h2>
                <div class="space-y-4">
                    <?php if (empty($events)): ?>
                        <p class="text-gray-500 text-center">No events created yet</p>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($event['title']); ?></h3>
                                        <p class="text-sm text-gray-500">
                                            <?php echo date('F j, Y', strtotime($event['event_date'])); ?> |
                                            <?php echo date('g:i A', strtotime($event['start_time'])); ?> - 
                                            <?php echo date('g:i A', strtotime($event['end_time'])); ?>
                                        </p>
                                        <p class="mt-2 text-gray-600"><?php echo htmlspecialchars($event['description']); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">$<?php echo number_format($event['price'], 2); ?></p>
                                        <p class="text-sm text-gray-500">Capacity: <?php echo $event['capacity']; ?></p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $event['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo ucfirst($event['status']); ?>
                                        </span>
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
</body>
</html> 