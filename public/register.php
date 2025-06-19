<?php
// Initialize session and include required files
session_start();
require_once('../reusable/connection.php');

// Function to validate email format
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate password strength
function isStrongPassword($password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    return strlen($password) >= 8 
        && preg_match('/[A-Z]/', $password) 
        && preg_match('/[a-z]/', $password) 
        && preg_match('/[0-9]/', $password);
}

// Function to sanitize input
function sanitizeInput($data) {
    global $connect;
    return mysqli_real_escape_string($connect, trim($data));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    
    // Validate and sanitize input
    $first = sanitizeInput($_POST['first']);
    $last = sanitizeInput($_POST['last']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    // Validation checks
    if (empty($first) || empty($last)) {
        $errors[] = "First and last name are required.";
    }

    if (!isValidEmail($email)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (!isStrongPassword($password)) {
        $errors[] = "Password must be at least 8 characters long and contain uppercase, lowercase, and numbers.";
    }

    // Check if email already exists
    $checkEmail = $connect->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = "This email is already registered.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Using secure password hashing
        $is_admin = 0; // Changed from 'No' to 0 for integer value
        $dateadded = date('Y-m-d H:i:s');

        $query = "INSERT INTO users (first, last, email, password, is_admin, dateAdded) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("ssssis", $first, $last, $email, $hashedPassword, $is_admin, $dateadded); // Changed 'ssssss' to 'ssssis' for integer
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! You can now log in.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again later.";
        }
    }

    // If there are errors, store them in session
    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Pass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/cafe.css">
</head>
<body class="bg-gray-50">
    <?php include('../reusable/nav.php'); ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Create Your Account</h1>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" class="space-y-6" novalidate>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="first" name="first" required 
                            value="<?php echo isset($_POST['first']) ? htmlspecialchars($_POST['first']) : ''; ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    </div>
                    <div>
                        <label for="last" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="last" name="last" required 
                            value="<?php echo isset($_POST['last']) ? htmlspecialchars($_POST['last']) : ''; ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" required 
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Must be at least 8 characters with uppercase, lowercase, and numbers</p>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <button type="submit" 
                        class="w-full bg-primary-green  px-4 py-2 rounded-md hover:bg-secondary-green ">
                        Create Account
                    </button>
                </div>

                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="login.php" class="text-primary-green hover:text-secondary-green font-medium">
                            Sign in
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <?php include('../reusable/footer.php'); ?>
</body>
</html>
