<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');
include('../reusable/functions.php');

// Get cafe ID from URL
$cafe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get cafe details
$query = "SELECT * FROM cafes WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("i", $cafe_id);
$stmt->execute();
$cafe = $stmt->get_result()->fetch_assoc();

if (!$cafe) {
    header("Location: searchCafes.php");
    exit();
}

// Get user's total points
$user_id = $_SESSION['id'];
$points_query = "SELECT SUM(points_earned) as total_points FROM user_visits WHERE user_id = ?";
$points_stmt = $conn->prepare($points_query);
if (!$points_stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$points_stmt->bind_param("i", $user_id);
$points_stmt->execute();
$total_points = $points_stmt->get_result()->fetch_assoc()['total_points'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($cafe['name']); ?> - Cafe Pass</title>
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
                            <h2 class="text-2xl font-semibold text-gray-900"><?php echo htmlspecialchars($cafe['name']); ?></h2>
                            <p class="mt-1 text-sm text-gray-500"><?php echo htmlspecialchars($cafe['address']); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Points</p>
                            <p class="text-3xl font-bold text-gray-900"><?php echo number_format($total_points); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Cafe Details -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6">
                        <!-- Cafe Image -->
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <img src="../<?php echo htmlspecialchars($cafe['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($cafe['name']); ?>"
                                 class="w-full h-full object-cover rounded-lg">
                        </div>

                        <!-- Cafe Information -->
                        <div class="space-y-6">
                            <!-- Rating -->
                            <div class="flex items-center">
                                <div class="flex items-center bg-white bg-opacity-90 rounded-full px-3 py-1">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-gray-900">
                                        <?php echo number_format($cafe['rating'], 1); ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                                <p class="text-gray-600">
                                    <?php echo nl2br(htmlspecialchars($cafe['description'])); ?>
                                </p>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Contact Information</h3>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-sm text-gray-500">Phone:</span>
                                        <span class="text-sm text-gray-900"><?php echo htmlspecialchars($cafe['phone']); ?></span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500">Email:</span>
                                        <span class="text-sm text-gray-900"><?php echo htmlspecialchars($cafe['email']); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Operating Hours -->
                            <?php if (!empty($cafe['operating_hours'])): ?>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Operating Hours</h3>
                                    <p class="text-sm text-gray-900">
                                        <?php echo htmlspecialchars($cafe['operating_hours']); ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <!-- Additional Information -->
                            <?php if (!empty($cafe['additional_info'])): ?>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Additional Information</h3>
                                    <p class="text-sm text-gray-900">
                                        <?php echo htmlspecialchars($cafe['additional_info']); ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <!-- Amenities -->
                            <?php if (!empty($cafe['amenities'])): ?>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Amenities</h3>
                                    <p class="text-sm text-gray-900">
                                        <?php echo htmlspecialchars($cafe['amenities']); ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <!-- Actions -->
                            <div class="mt-6 flex justify-between items-center">
                                <a href="searchCafes.php" 
                                   class="text-sm font-medium text-gray-800 hover:text-gray-700">
                                    Back to Search
                                </a>
                                <form method="POST" action="addToWishlist.php" class="inline">
                                    <input type="hidden" name="cafe_id" value="<?php echo $cafe_id; ?>">
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        <svg class="-ml-1 mr-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v14l-5-2.5L5 21V5z"/>
                                        </svg>
                                        Add to Wishlist
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>
</body>
</html>
