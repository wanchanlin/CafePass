<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

include('../reusable/connection.php');
include('../reusable/functions.php');


// fetch filter options from database
$parkNames = getFilterOptions($connect, 'nationalparks', 'ParkName');
$parkTypes = getFilterOptions($connect, 'nationalparks', 'Type');
$regions = getFilterOptions($connect, 'nationalparks', 'Region');
$ecosystemTypes = getFilterOptions($connect, 'ecological', 'EcosystemType');
$integrityStatuses = getFilterOptions($connect, 'ecological', 'IntegrityStatus');
$integrityTrends = getFilterOptions($connect, 'ecological', 'IntegrityTrend');



// fnitialize filters
$filters = [];
$whereClauses = [];
$params = [];
$paramTypes = "";

// collect selected filters from GET request
if (!empty($_GET['park_name'])) {
    $filters['park_name'] = $_GET['park_name'];
    $whereClauses[] = "np.ParkName LIKE ?";
    $params[] = "%" . $_GET['park_name'] . "%";
    $paramTypes .= "s";
}
if (!empty($_GET['park_type'])) {
    $filters['park_type'] = $_GET['park_type'];
    $whereClauses[] = "np.Type = ?";
    $params[] = $_GET['park_type'];
    $paramTypes .= "s";
}
if (!empty($_GET['region'])) {
    $filters['region'] = $_GET['region'];
    $whereClauses[] = "np.Region = ?";
    $params[] = $_GET['region'];
    $paramTypes .= "s";
}
if (!empty($_GET['ecosystem_type'])) {
    $filters['ecosystem_type'] = $_GET['ecosystem_type'];
    $whereClauses[] = "e.EcosystemType = ?";
    $params[] = $_GET['ecosystem_type'];
    $paramTypes .= "s";
}
if (!empty($_GET['integrity_status'])) {
    $filters['integrity_status'] = $_GET['integrity_status'];
    $whereClauses[] = "e.IntegrityStatus = ?";
    $params[] = $_GET['integrity_status'];
    $paramTypes .= "s";
}
if (!empty($_GET['integrity_trend'])) {
    $filters['integrity_trend'] = $_GET['integrity_trend'];
    $whereClauses[] = "e.IntegrityTrend = ?";
    $params[] = $_GET['integrity_trend'];
    $paramTypes .= "s";
}

// construct WHERE SQL if filters are present
$whereSQL = (!empty($whereClauses)) ? " WHERE " . implode(" AND ", $whereClauses) : "";

// build SQL query with filters
$query = "SELECT np.ID, np.ParkName, np.Type AS ParkType, np.Description, np.DateFounded, np.Region, np.ImagePath, np.ImageSource, 
                 e.EcosystemType, e.IntegrityStatus, e.IntegrityTrend
          FROM nationalparks np 
          LEFT JOIN ecological e ON np.ParkName = e.ParkName
          $whereSQL
          GROUP BY np.ID, np.ParkName, np.Type, np.Description, np.DateFounded, np.Region, np.ImagePath, np.ImageSource, 
                   e.EcosystemType, e.IntegrityStatus, e.IntegrityTrend";

// Prepare and execute the query
$stmt = $connect->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($paramTypes, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM nationalparks WHERE ID = ?";
    $delete_stmt = $connect->prepare($delete_query);
    $delete_stmt->bind_param("i", $delete_id);
    $delete_stmt->execute();
    header("Location: manageParks.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Parks - Coffee Pass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../styles/cafe.css">
</head>

<body class="bg-gray-50">
<?php include('../reusable/userDbNav.php'); ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manage Parks</h2>
                <p class="text-gray-600 mt-1">Welcome, <?php echo htmlspecialchars($_SESSION['first'] . " " . $_SESSION['last']); ?>!</p>
            </div>
            <a href="addPark.php" class="bg-primary-green text-white px-4 py-2 rounded-md hover:bg-secondary-green transition-colors duration-200">
                Add New Park
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Park Name</label>
                <select name="park_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    <option value="">All</option>
                    <?php foreach ($parkNames as $name) : ?>
                        <option value="<?= htmlspecialchars($name); ?>" <?= ($_GET['park_name'] ?? '') == $name ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Park Type</label>
                <select name="park_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    <option value="">All</option>
                    <?php foreach ($parkTypes as $type) : ?>
                        <option value="<?= htmlspecialchars($type); ?>" <?= ($_GET['park_type'] ?? '') == $type ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($type); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Region</label>
                <select name="region" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    <option value="">All</option>
                    <?php foreach ($regions as $region) : ?>
                        <option value="<?= htmlspecialchars($region); ?>" <?= ($_GET['region'] ?? '') == $region ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($region); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Ecosystem Type</label>
                <select name="ecosystem_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    <option value="">All</option>
                    <?php foreach ($ecosystemTypes as $type) : ?>
                        <option value="<?= htmlspecialchars($type); ?>" <?= ($_GET['ecosystem_type'] ?? '') == $type ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($type); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Integrity Status</label>
                <select name="integrity_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    <option value="">All</option>
                    <?php foreach ($integrityStatuses as $status) : ?>
                        <option value="<?= htmlspecialchars($status); ?>" <?= ($_GET['integrity_status'] ?? '') == $status ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($status); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Integrity Trend</label>
                <select name="integrity_trend" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    <option value="">All</option>
                    <?php foreach ($integrityTrends as $trend) : ?>
                        <option value="<?= htmlspecialchars($trend); ?>" <?= ($_GET['integrity_trend'] ?? '') == $trend ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($trend); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="md:col-span-2 lg:col-span-3 flex justify-end">
                <button type="submit" class="bg-primary-green text-white px-6 py-2 rounded-md hover:bg-secondary-green transition-colors duration-200">
                    Apply Filters
                </button>
            </div>
        </form>

        <!-- Parks Table -->
        <?php if ($result->num_rows > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Park Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Founded</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ecosystem</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trend</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="<?= htmlspecialchars('../' . ltrim($row['ImagePath'], '/')); ?>" 
                                         class="h-16 w-16 object-cover rounded-lg" 
                                         alt="<?= htmlspecialchars($row['ParkName']); ?>">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($row['ParkName']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($row['ParkType']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($row['Region']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($row['DateFounded']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    <?php echo htmlspecialchars($row['Description']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($row['EcosystemType'] ?: 'N/A'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($row['IntegrityStatus'] ?: 'N/A'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($row['IntegrityTrend'] ?: 'N/A'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="updatePark.php?id=<?php echo $row['ID']; ?>" 
                                       class="text-primary-green hover:text-secondary-green mr-3">Edit</a>
                                    <a href="deletePark.php?id=<?php echo urlencode($row['ID']); ?>" 
                                       onclick="return confirm('Are you sure you want to delete this park?');"
                                       class="text-red-600 hover:text-red-900">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-500 text-lg">No parks found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include('../reusable/footer.php'); ?>

<?php
$stmt->close();
$connect->close();
?>
</body>
</html>
