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

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $cafe_id = $_POST['cafe_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['id'];

    // Check if user has already reviewed this cafe
    $check_query = "SELECT id FROM reviews WHERE user_id = ? AND cafe_id = ?";
    $check_stmt = $conn->prepare($check_query);
if (!$check_stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
    $check_stmt->bind_param("ii", $user_id, $cafe_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update existing review
        $update_query = "UPDATE reviews SET rating = ?, comment = ?, review_date = NOW() WHERE user_id = ? AND cafe_id = ?";
        $update_stmt = $connect->prepare($update_query);
        $update_stmt->bind_param("isii", $rating, $comment, $user_id, $cafe_id);
        $update_stmt->execute();
    } else {
        // Insert new review
        $insert_query = "INSERT INTO reviews (user_id, cafe_id, rating, comment, review_date) VALUES (?, ?, ?, ?, NOW())";
        $insert_stmt = $connect->prepare($insert_query);
        $insert_stmt->bind_param("iiis", $user_id, $cafe_id, $rating, $comment);
        $insert_stmt->execute();
    }

    // Update cafe's average rating
    $avg_query = "UPDATE cafes c SET rating = (
        SELECT AVG(rating) FROM reviews WHERE cafe_id = c.id
    ) WHERE id = ?";
    $avg_stmt = $connect->prepare($avg_query);
    $avg_stmt->bind_param("i", $cafe_id);
    $avg_stmt->execute();
}

// Get user's reviews
$query = "SELECT r.*, c.name as cafe_name, c.address, c.image_path 
          FROM reviews r 
          INNER JOIN cafes c ON r.cafe_id = c.id 
          WHERE r.user_id = ? 
          ORDER BY r.review_date DESC";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Get visited cafes for review form
$visited_query = "SELECT DISTINCT c.* 
                 FROM cafes c 
                 INNER JOIN user_visits v ON c.id = v.cafe_id 
                 WHERE v.user_id = ? 
                 ORDER BY c.name";
$visited_stmt = $conn->prepare($visited_query);
if (!$visited_stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$visited_stmt->bind_param("i", $user_id);
$visited_stmt->execute();
$visited_result = $visited_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Stores - Cafe Pass</title>
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
                            <h2 class="text-2xl font-semibold text-gray-900">Review Cafes</h2>
                            <p class="mt-1 text-sm text-gray-500">Share your experience with other coffee lovers</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Points</p>
                            <p class="text-3xl font-bold text-gray-900"><?php echo number_format($total_points); ?></p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <!-- Review Form -->
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Write a Review</h3>
                        </div>
                        <div class="p-6">
                            <form method="POST" action="">
                                <div class="space-y-4">
                                    <div>
                                        <label for="cafe_id" class="block text-sm font-medium text-gray-700">Select Cafe</label>
                                        <select name="cafe_id" id="cafe_id" required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm rounded-md">
                                            <option value="">Choose a cafe...</option>
                                            <?php while ($cafe = mysqli_fetch_assoc($visited_result)): ?>
                                                <option value="<?php echo $cafe['id']; ?>">
                                                    <?php echo htmlspecialchars($cafe['name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                        <select name="rating" id="rating" required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm rounded-md">
                                            <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                            <option value="4">⭐⭐⭐⭐ Very Good</option>
                                            <option value="3">⭐⭐⭐ Good</option>
                                            <option value="2">⭐⭐ Fair</option>
                                            <option value="1">⭐ Poor</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="comment" class="block text-sm font-medium text-gray-700">Your Review</label>
                                        <textarea name="comment" id="comment" rows="4" required
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500 sm:text-sm"
                                                  placeholder="Share your experience..."></textarea>
                                    </div>

                                    <div>
                                        <button type="submit" name="submit_review"
                                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium ">
                                            Submit Review
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Your Reviews</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($review = mysqli_fetch_assoc($result)): ?>
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <img class="h-16 w-16 rounded-lg object-cover"
                                                     src="<?php echo htmlspecialchars($review['image_path']); ?>"
                                                     alt="<?php echo htmlspecialchars($review['cafe_name']); ?>">
                                            </div>
                                            <div class="ml-4 flex-grow">
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
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <p class="ml-2 text-sm text-gray-600">
                                                        <?php echo date('M d, Y', strtotime($review['review_date'])); ?>
                                                    </p>
                                                </div>
                                                <?php if (!empty($review['comment'])): ?>
                                                    <p class="mt-2 text-sm text-gray-600">
                                                        <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
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
                                    <p class="mt-1 text-sm text-gray-500">Start reviewing cafes you've visited.</p>
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