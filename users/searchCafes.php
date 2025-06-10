<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');

// Search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$where_clause = '';
if (!empty($search)) {
    $where_clause = "WHERE name LIKE '%$search%' OR address LIKE '%$search%' OR description LIKE '%$search%'";
}

// Get cafes with pagination
$items_per_page = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$query = "SELECT * FROM cafes $where_clause ORDER BY rating DESC LIMIT $offset, $items_per_page";
$result = mysqli_query($connect, $query);

// Get total number of cafes for pagination
$total_query = "SELECT COUNT(*) as count FROM cafes $where_clause";
$total_result = mysqli_query($connect, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['count'] / $items_per_page);
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
        <div class="max-w-7xl mx-auto">
            <!-- Search Section -->
            <div class="mb-8">
                <form action="" method="GET" class="max-w-2xl mx-auto">
                    <div class="flex gap-4">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                            placeholder="Search cafes by name, location, or description...">
                        <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Results Section -->
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
                                <a href="cafeDetails.php?id=<?php echo $cafe['id']; ?>"
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="mt-8 flex justify-center">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 <?php echo $page == $i ? 'z-10 bg-gray-50 border-gray-500' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>
</body>
</html> 