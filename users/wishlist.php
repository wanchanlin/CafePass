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
global $conn;
if (!$conn) {
    die("Database connection failed");
}

$points_stmt = $conn->prepare($points_query);
$points_stmt->bind_param("i", $user_id);
$points_stmt->execute();
$total_points = $points_stmt->get_result()->fetch_assoc()['total_points'] ?? 0;

// Handle remove from wishlist
if (isset($_POST['remove_from_wishlist'])) {
    $cafe_id = $_POST['cafe_id'];
    $user_id = $_SESSION['id'];
    
    $delete_query = "DELETE FROM wishlist WHERE user_id = ? AND cafe_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
if (!$delete_stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
    $delete_stmt->bind_param("ii", $user_id, $cafe_id);
    $delete_stmt->execute();
    
    // Redirect to refresh the page
    header("Location: wishlist.php");
    exit();
}

// Get user's wishlist
$query = "SELECT c.*, w.date_added 
          FROM cafes c 
          INNER JOIN wishlist w ON c.id = w.cafe_id 
          WHERE w.user_id = ? 
          ORDER BY w.date_added DESC";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$wishlist = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - Cafe Pass</title>
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
                            <h2 class="text-2xl font-semibold text-gray-900">My Wishlist</h2>
                            <p class="mt-1 text-sm text-gray-500">Save cafes you want to visit later</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Points</p>
                            <p class="text-3xl font-bold text-gray-900"><?php echo number_format($total_points); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Wishlist Items -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Saved Cafes</h3>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        <?php if (mysqli_num_rows($wishlist) > 0): ?>
                            <?php while ($cafe = mysqli_fetch_assoc($wishlist)): ?>
                                <div class="p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <img class="h-16 w-16 rounded-lg object-cover"
                                                 src="<?php echo htmlspecialchars($cafe['image_path']); ?>"
                                                 alt="<?php echo htmlspecialchars($cafe['name']); ?>">
                                        </div>
                                        <div class="ml-4 flex-grow">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($cafe['name']); ?>
                                            </h4>
                                            <p class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars($cafe['address']); ?>
                                            </p>
                                            <div class="mt-1 flex items-center">
                                                <div class="flex items-center">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <svg class="h-4 w-4 <?php echo $i <= $cafe['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    <?php endfor; ?>
                                                </div>
                                                <p class="ml-2 text-sm text-gray-600">
                                                    <?php echo number_format($cafe['rating'], 1); ?>
                                                </p>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-600">
                                                Added on <?php echo date('M d, Y', strtotime($cafe['date_added'])); ?>
                                            </p>
                                            <?php if (!empty($cafe['description'])): ?>
                                                <p class="mt-2 text-sm text-gray-600">
                                                    <?php echo htmlspecialchars($cafe['description']); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4 flex-shrink-0 flex space-x-4">
                                            <a href="cafeDetails.php?id=<?php echo $cafe['id']; ?>" 
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                View Details
                                            </a>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="cafe_id" value="<?php echo $cafe['id']; ?>">
                                                <button type="submit" name="remove_from_wishlist" 
                                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No cafes in wishlist</h3>
                                <p class="mt-1 text-sm text-gray-500">Start adding cafes to your wishlist to save them for later.</p>
                                <div class="mt-6">
                                    <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium">
                                    <a href="searchCafes.php">
                                        Browse Cafes
                                    </a>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>
</body>
</html> 