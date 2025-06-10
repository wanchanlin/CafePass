<?php
// index.php - Login Page
session_start();
if (isset($_SESSION['id'])) {
    header("Location: " . ($_SESSION['is_admin'] === 'Yes' ? 'admin/adminDashboard.php' : 'users/manageParks.php'));
    exit();
}
include('reusable/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE email = ? AND password = ? LIMIT 1";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows) {
        $record = $result->fetch_assoc();
        $_SESSION['id'] = $record['id'];
        $_SESSION['email'] = $record['email'];
        $_SESSION['is_admin'] = $record['is_admin'];
        $_SESSION['first'] = $record['first'];
        $_SESSION['last'] = $record['last'];
        
        header("Location: " . ($record['is_admin'] === 'Yes' ? 'admin/adminDashboard.php' : 'users/manageParks.php'));
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password!";
    }
}

include('reusable/header.php');
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Welcome Back</h1>
        
        <?php if (isset($_SESSION['error'])) { 
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4' role='alert'>
                    <span class='block sm:inline'>" . $_SESSION['error'] . "</span>
                  </div>"; 
            unset($_SESSION['error']); 
        } ?>

        <form method="POST" class="space-y-6">
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
                    Sign In
                </button>
            </div>

            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="public/register.php" class="text-primary-green hover:text-secondary-green font-medium">
                        Create one
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php include('reusable/footer.php'); ?>