<?php
// register.php - User Registration
include('../reusable/connection.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first = mysqli_real_escape_string($connect, $_POST['first']);
    $last = mysqli_real_escape_string($connect, $_POST['last']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = md5($_POST['password']);
    $is_admin = 'No';
    $dateadded = date('Y-m-d H:i:s');

    $query = "INSERT INTO users (first, last, email, password, is_admin, dateAdded) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("ssssss", $first, $last, $email, $password, $is_admin, $dateadded);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! You can now log in.";
        header("Location: ../login.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
    }
}

include('../reusable/header.php');
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Create Your Account</h1>
        
        <?php if (isset($_SESSION['error'])) { 
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4' role='alert'>
                    <span class='block sm:inline'>" . $_SESSION['error'] . "</span>
                  </div>"; 
            unset($_SESSION['error']); 
        } ?>

        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" name="first" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" name="last" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
            </div>

            <div class="flex items-center justify-between pt-4">
                <button type="submit" 
                    class="w-full bg-primary-green text-white px-4 py-2 rounded-md hover:bg-secondary-green transition-colors duration-200">
                    Create Account
                </button>
            </div>

            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="../login.php" class="text-primary-green hover:text-secondary-green font-medium">
                        Sign in
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php include('../reusable/footer.php'); ?>
