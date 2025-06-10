<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');

// Get user's visited cafes with details
$user_id = $_SESSION['id'];
$query = "SELECT c.*, v.visit_date, v.points_earned 
          FROM cafes c 
          INNER JOIN user_visits v ON c.id = v.cafe_id 
          WHERE v.user_id = ? 
          ORDER BY v.visit_date DESC";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total points
$points_query = "SELECT SUM(points_earned) as total_points FROM user_visits WHERE user_id = ?";
$points_stmt = $connect->prepare($points_query);
$points_stmt->bind_param("i", $user_id);
$points_stmt->execute();
$points_result = $points_stmt->get_result();
$total_points = $points_result->fetch_assoc()['total_points'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visited Cafes - Cafe Pass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/cafe.css">
</head>
<body class="bg-gray-50">
    <?php include('../reusable/userDbNav.php'); ?>
    
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Points Summary -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">My Coffee Journey</h2>
                        <p class="mt-1 text-sm text-gray-500">Track your cafe visits and earned points</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total Points</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo number_format($total_points); ?></p>
                    </div>
                </div>
            </div>

            <h3 class="text-xl font-semibold text-gray-900 mb-6">Visited Cafes</h3>

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
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            <?php echo $cafe['points_earned']; ?> points earned
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Visited on <?php echo date('M d, Y', strtotime($cafe['visit_date'])); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="cafeDetails.php?id=<?php echo $cafe['id']; ?>"
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No visited cafes yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Start visiting cafes to earn points and track your journey.</p>
                    <div class="mt-6">
                        <a href="searchCafes.php" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Find Cafes
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>
</body>
</html> 