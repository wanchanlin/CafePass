<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');

// Handle remove from wishlist
if (isset($_POST['remove']) && isset($_POST['cafe_id'])) {
    $cafe_id = (int)$_POST['cafe_id'];
    $user_id = $_SESSION['id'];
    $query = "DELETE FROM wishlist WHERE user_id = ? AND cafe_id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("ii", $user_id, $cafe_id);
    $stmt->execute();
}

// Get user's wishlist with cafe details
$user_id = $_SESSION['id'];
$query = "SELECT c.*, w.added_date 
          FROM cafes c 
          INNER JOIN wishlist w ON c.id = w.cafe_id 
          WHERE w.user_id = ? 
          ORDER BY w.added_date DESC";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
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
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-semibold text-gray-900 mb-8">My Wishlist</h2>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php while ($cafe = mysqli_fetch_assoc($result)): ?>
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="aspect-w-16 aspect-h-9">
                                <img src="<?php echo htmlspecialchars($cafe['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($cafe['name']); ?>"
                                     class="w-full h-48 object-cover">
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <?php echo htmlspecialchars($cafe['name']); ?>
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    <?php echo htmlspecialchars($cafe['address']); ?>
                                </p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="ml-1 text-sm text-gray-600">
                                            <?php echo number_format($cafe['rating'], 1); ?>
                                        </span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="cafeDetails.php?id=<?php echo $cafe['id']; ?>"
                                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                            View Details
                                        </a>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="cafe_id" value="<?php echo $cafe['id']; ?>">
                                            <button type="submit" name="remove"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Added on <?php echo date('M d, Y', strtotime($cafe['added_date'])); ?>
                                </p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No cafes in wishlist</h3>
                    <p class="mt-1 text-sm text-gray-500">Start adding cafes to your wishlist to see them here.</p>
                    <div class="mt-6">
                        <a href="searchCafes.php" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Browse Cafes
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>
</body>
</html> 