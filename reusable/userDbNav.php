<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Get the current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);

// Define base path
$base_path = '/cafepass';
?>

<!-- Add Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- nav section -->
<nav class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
            <!-- Logo Section -->
            <div class="flex-shrink-0">
                <a href="<?php echo $base_path; ?>/index.php">
                    <img class="h-8 w-auto" src="/capstone/CoffeePass/images/Logo.svg" alt="cafePass">
                </a>
            </div>

            <!-- Spacer -->
            <div class="flex-1"></div>
             
            <!-- Navigation Items -->
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['id'])): ?>
                    <!-- System Settings Icon -->
                    <a type="button" class="relative p-2 text-gray-600 focus:outline-none rounded-full">
                        <span class="sr-only">System Settings</span>
                        <i class="fas fa-gear text-xl"></i>
                    </a>

                    <!-- Notifications Icon -->
                    <a type="button" class="relative p-2 text-gray-600 focus:outline-none rounded-full">
                        <span class="sr-only">View notifications</span>
                        <i class="fas fa-bell text-xl"></i>
                        <!-- Notification Badge -->
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </a>

                    <!-- Help/Question Icon -->
                    <a type="button" class="relative p-2 text-gray-600 focus:outline-none rounded-full">
                        <span class="sr-only">Help</span>
                        <i class="fas fa-circle-question text-xl"></i>
                    </a>

                    <button class="rounded-md px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                        <a href="<?php echo $base_path; ?>/users/logout.php">
                            Logout
                        </a>
                    </button>
                <?php else: ?>
                    <a href="<?php echo $base_path; ?>/login.php" 
                       class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                        Login
                    </a>
                    <a href="/capstone/CoffeePass/public/register.php" 
                       class="rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                        Sign Up
                    </a>
                <?php endif; ?>
            </div>
            <!-- Mobile menu button -->
            <button type="button"
                class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:ring-2 focus:ring-white focus:outline-hidden focus:ring-inset sm:hidden"
                aria-controls="mobile-menu" aria-expanded="false" id="mobile-menu-button">
                <span class="absolute -inset-0.5"></span>
                <span class="sr-only">Open main menu</span>
                <div class="hamburger-container">
                    <svg class="hamburger-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <!-- Top bar -->
                        <path class="menu-bar top-bar" d="M4 6h16" />
                        <!-- Middle bar -->
                        <path class="menu-bar middle-bar" d="M4 12h16" />
                        <!-- Bottom bar -->
                        <path class="menu-bar bottom-bar" d="M4 18h16" />
                    </svg>
                </div>
            </button>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="hidden sm:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pt-2 pb-3">
            <?php if (isset($_SESSION['id'])): ?>
                <!-- Mobile Navigation Icons -->
                <div class="flex justify-around py-3 border-b border-gray-200">
                    <!-- System Settings -->
                    <a href="#" class="flex flex-col items-center text-gray-600 hover:text-gray-900">
                        <i class="fas fa-gear text-xl mb-1"></i>
                        <span class="text-xs">Settings</span>
                    </a>

                    <!-- Notifications -->
                    <a href="#" class="flex flex-col items-center text-gray-600 hover:text-gray-900 relative">
                        <i class="fas fa-bell text-xl mb-1"></i>
                        <span class="text-xs">Notifications</span>
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </a>

                    <!-- Help -->
                    <a href="#" class="flex flex-col items-center text-gray-600 hover:text-gray-900">
                        <i class="fas fa-circle-question text-xl mb-1"></i>
                        <span class="text-xs">Help</span>
                    </a>
                </div>

                <!-- Mobile Menu Links -->
                <?php if ($_SESSION['is_admin'] === 'Yes'): ?>
                    <a href="/capstone/CoffeePass/admin/adminDashboard.php" 
                       class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                        Admin Dashboard
                    </a>
                <?php endif; ?>
                <button>
                    <a href="<?php echo $base_path; ?>/users/logout.php" 
                       class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                        Logout
                    </a>
                </button>
            <?php else: ?>
                <a href="<?php echo $base_path; ?>/login.php" 
                   class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                    Login
                </a>
                <a href="<?php echo $base_path; ?>/public/register.php" 
                   class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                    Sign Up
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
document.getElementById('mobile-menu-button').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    const isHidden = mobileMenu.classList.contains('hidden');
    
    if (isHidden) {
        mobileMenu.classList.remove('hidden');
    } else {
        mobileMenu.classList.add('hidden');
    }
});
</script>