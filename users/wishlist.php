<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');

// Handle remove from wishlist
if (isset($_POST['remove_from_wishlist'])) {
    $cafe_id = $_POST['cafe_id'];
    $user_id = $_SESSION['id'];
    
    $delete_query = "DELETE FROM wishlist WHERE user_id = ? AND cafe_id = ?";
    $delete_stmt = $connect->prepare($delete_query);
    $delete_stmt->bind_param("ii", $user_id, $cafe_id);
    $delete_stmt->execute();
    
    // Redirect to refresh the page
    header("Location: wishlist.php");
    exit();
}

// Get user's wishlist
$user_id = $_SESSION['id'];
$query = "SELECT c.*, w.date_added 
          FROM cafes c 
          INNER JOIN wishlist w ON c.id = w.cafe_id 
          WHERE w.user_id = ? 
          ORDER BY w.date_added DESC";
$stmt = $connect->prepare($query);
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
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">My Wishlist</h2>
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
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($cafe['name']); ?>
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            <?php echo htmlspecialchars($cafe['address']); ?>
                                        </p>
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
                                <a href="searchCafes.php" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Browse Cafes
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>
</body>
</html> 