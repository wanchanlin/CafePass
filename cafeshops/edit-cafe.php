<?php
require_once '../reusable/connection.php';
require_once 'db_helper.php';
session_start();

// Check if user is logged in and is a cafe owner
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'cafe_owner') {
    header("Location: /capstone/CoffeePass/login.php");
    exit();
}

// Get cafe owner's information
$owner_id = $_SESSION['id'];
$owner = getCafeOwner($owner_id);

// Get cafe information
$cafe = getCafe($owner_id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $opening_hours = $_POST['opening_hours'];
    $closing_hours = $_POST['closing_hours'];

    // Update cafe information
    if (updateCafe($cafe['id'], $name, $address, $description, $phone, $email, $opening_hours, $closing_hours)) {
        $success_message = "Cafe information updated successfully!";
        // Refresh cafe data
        $cafe = getCafe($owner_id);
    } else {
        $error_message = "Error updating cafe information.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cafe - CoffeePass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../styles/cafe.css">
</head>
<body class="bg-gray-100">
    <?php include '../reusable/userDbNav.php'; ?>

    <div class="flex flex-row gap-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php include '../reusable/cafeSideBar.php'; ?>    
    <div class="flex-1">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Edit Cafe Information</h1>
               
            </div>

            <?php if (isset($success_message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $success_message; ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $error_message; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <!-- Cafe Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Cafe Name</label>
                    <input type="text" name="name" id="name" value="<?php echo isset($cafe['name']) ? htmlspecialchars($cafe['name']) : ''; ?>" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo isset($cafe['description']) ? htmlspecialchars($cafe['description']) : ''; ?></textarea>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" id="address" value="<?php echo isset($cafe['address']) ? htmlspecialchars($cafe['address']) : ''; ?>" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="<?php echo isset($cafe['phone']) ? htmlspecialchars($cafe['phone']) : ''; ?>" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo isset($cafe['email']) ? htmlspecialchars($cafe['email']) : ''; ?>" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Operating Hours -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="opening_hours" class="block text-sm font-medium text-gray-700">Opening Hours</label>
                        <input type="time" name="opening_hours" id="opening_hours" value="<?php echo htmlspecialchars($cafe['opening_hours']); ?>" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="closing_hours" class="block text-sm font-medium text-gray-700">Closing Hours</label>
                        <input type="time" name="closing_hours" id="closing_hours" value="<?php echo htmlspecialchars($cafe['closing_hours']); ?>" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class=" px-4 py-2 rounded-md  focus:outline-none focus:ring-2  focus:ring-offset-2">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
    <?php include '../reusable/footer.php'; ?>
</body>
</html> 