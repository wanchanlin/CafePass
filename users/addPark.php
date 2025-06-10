<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');
include('../reusable/functions.php');

// Fetch dropdown values
$regions = getUniqueValues('Region', 'nationalparks', $connect);
$park_types = getUniqueValues('Type', 'nationalparks', $connect);
$ecosystem_types = getUniqueValues('EcosystemType', 'ecological', $connect);
$integrity_statuses = getUniqueValues('IntegrityStatus', 'ecological', $connect);
$integrity_trends = getUniqueValues('IntegrityTrend', 'ecological', $connect);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $park_name = mysqli_real_escape_string($connect, $_POST['park_name']);
    $park_type = mysqli_real_escape_string($connect, $_POST['park_type']);
    $region = mysqli_real_escape_string($connect, $_POST['region']);
    $date_founded = intval($_POST['date_founded']);
    $description = mysqli_real_escape_string($connect, $_POST['description']);
    $ecosystem_type = mysqli_real_escape_string($connect, $_POST['ecosystem_type']);
    $integrity_status = mysqli_real_escape_string($connect, $_POST['integrity_status']);
    $integrity_trend = mysqli_real_escape_string($connect, $_POST['integrity_trend']);

    // Handle Image Upload
    $imagePath = "uploads/default.jpg"; // Default image
    if (!empty($_FILES['image']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] <= 2097152) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $snake_case_name = strtolower(str_replace(" ", "-", preg_replace("/[^a-zA-Z0-9\s]/", "", $park_name))); // Convert to snake case
            $newFileName = $snake_case_name . "." . $ext;
            $imagePath = "uploads/" . $newFileName;
            move_uploaded_file($_FILES['image']['tmp_name'], "../" . $imagePath);
        } else {
            echo "Invalid file type or size too large!";
            exit();
        }
    }

    // Insert park details into nationalparks table
    $query = "INSERT INTO nationalparks (ParkName, Type, Region, DateFounded, Description, ImagePath) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("sssiss", $park_name, $park_type, $region, $date_founded, $description, $imagePath);

    // Insert park details into ecological table
    if ($stmt->execute()) {
        $eco_query = "INSERT INTO ecological (ParkName, EcosystemType, IntegrityStatus, IntegrityTrend) VALUES (?, ?, ?, ?)";
        $eco_stmt = $connect->prepare($eco_query);
        $eco_stmt->bind_param("ssss", $park_name, $ecosystem_type, $integrity_status, $integrity_trend);
        $eco_stmt->execute();

        header("Location: manageParks.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Park - Cafe Pass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/cafe.css">
</head>
<body class="bg-gray-50">
    <?php include('../reusable/userDbNav.php'); ?>
    
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-sm rounded-lg p-8">
                <h2 class="text-3xl font-semibold text-gray-900 mb-8 text-center">Add a New Park</h2>
                
                <form method="POST" action="addPark.php" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Park Name -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Park Name</label>
                            <input type="text" name="park_name" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                        </div>

                        <!-- Park Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="park_type" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                                <?php foreach ($park_types as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Region -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                            <select name="region" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                                <?php foreach ($regions as $region): ?>
                                    <option value="<?= htmlspecialchars($region) ?>"><?= htmlspecialchars($region) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Date Founded -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Founded</label>
                            <input type="number" name="date_founded" min="1700" max="<?= date('Y'); ?>" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                        </div>

                        <!-- Description -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" required rows="4"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500"></textarea>
                        </div>

                        <!-- Ecosystem Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ecosystem Type</label>
                            <select name="ecosystem_type" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                                <?php foreach ($ecosystem_types as $ecosystem): ?>
                                    <option value="<?= htmlspecialchars($ecosystem) ?>"><?= htmlspecialchars($ecosystem) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Integrity Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ecological Integrity Status</label>
                            <select name="integrity_status" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                                <?php foreach ($integrity_statuses as $status): ?>
                                    <option value="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($status) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Integrity Trend -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ecological Integrity Trend</label>
                            <select name="integrity_trend" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                                <?php foreach ($integrity_trends as $trend): ?>
                                    <option value="<?= htmlspecialchars($trend) ?>"><?= htmlspecialchars($trend) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Image Upload -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Park Image</label>
                            <input type="file" name="image" accept="image/*"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="manageParks.php" 
                           class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Add Park
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>
</body>
</html>
