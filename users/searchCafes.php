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

// Prepare and execute points query
$points_stmt = $conn->prepare($points_query);
if (!$points_stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

// Handle success/error messages
$success = isset($_GET['success']) ? true : false;
$error = isset($_GET['error']) ? true : false;
$already_added = isset($_GET['already_added']) ? true : false;

// Execute points query
$points_stmt->bind_param("i", $user_id);
$points_stmt->execute();
$total_points = $points_stmt->get_result()->fetch_assoc()['total_points'] ?? 0;

// Get all cafes
$query = "SELECT * FROM cafes ORDER BY name";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die("Query failed: (" . $conn->errno . ") " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Cafes - Cafe Pass</title>
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
                            <h2 class="text-2xl font-semibold text-gray-900">Search Cafes</h2>
                            <p class="mt-1 text-sm text-gray-500">Find and explore coffee shops near you</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Points</p>
                            <p class="text-3xl font-bold text-gray-900"><?php echo number_format($total_points); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Search Form -->
                <div class="bg-white shadow-sm rounded-lg mb-8">
                    <div class="p-6">
                        <form method="GET" action="" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                    <input type="text" name="search" id="search" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500 sm:text-sm"
                                           placeholder="Search by name or address"
                                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                </div>
                                <div>
                                    <label for="rating" class="block text-sm font-medium text-gray-700">Minimum Rating</label>
                                    <select name="rating" id="rating" 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm rounded-md">
                                        <option value="">Any Rating</option>
                                        <option value="4" <?php echo isset($_GET['rating']) && $_GET['rating'] == '4' ? 'selected' : ''; ?>>4+ Stars</option>
                                        <option value="3" <?php echo isset($_GET['rating']) && $_GET['rating'] == '3' ? 'selected' : ''; ?>>3+ Stars</option>
                                        <option value="2" <?php echo isset($_GET['rating']) && $_GET['rating'] == '2' ? 'selected' : ''; ?>>2+ Stars</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                                    <select name="sort" id="sort" 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm rounded-md">
                                        <option value="name" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'name' ? 'selected' : ''; ?>>Name</option>
                                        <option value="rating" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'rating' ? 'selected' : ''; ?>>Rating</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Messages -->
                <?php if ($success): ?>
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">Cafe added to your wishlist successfully!</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">Failed to add cafe to wishlist. Please try again.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($already_added): ?>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">This cafe is already in your wishlist.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Results -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php while ($cafe = mysqli_fetch_assoc($result)): ?>
                        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                            <div class="relative h-48">
                                <img src="../<?php echo($cafe['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($cafe['name']); ?>"
                                     class="w-full h-full object-cover">
                                <div class="absolute top-4 right-4">
                                    <div class="flex items-center bg-white bg-opacity-90 rounded-full px-3 py-1">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="ml-1 text-sm font-medium text-gray-900">
                                            <?php echo number_format($cafe['rating'], 1); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <?php echo htmlspecialchars($cafe['name']); ?>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    <?php echo htmlspecialchars($cafe['address']); ?>
                                </p>
                                <div class="mt-4 flex justify-between items-center">
                                    <a href="cafeDetails.php?id=<?php echo $cafe['id']; ?>" 
                                       class="text-sm font-medium text-gray-800 hover:text-gray-700">
                                        View Details
                                    </a>
                                    <form method="POST" action="addToWishlist.php" class="inline">
                                        <input type="hidden" name="cafe_id" value="<?php echo $cafe['id']; ?>">
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
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>
</body>
</html> 