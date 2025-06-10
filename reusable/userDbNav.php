<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- nav section -->
<nav class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
            <div class="flex flex-1 items-center justify-between sm:items-stretch sm:justify-start">
                <div class="flex shrink-0 items-center">
                    <a href="/capstone/CoffeePass/index.php">
                        <img class="h-8 w-auto" src="/capstone/CoffeePass/images/Logo.svg" alt="cafePass">
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:block">
                    <div class="flex space-x-4">
                        <?php if (isset($_SESSION['id'])): // Only for logged-in users ?>
                            <a href="/capstone/CoffeePass/users/manageParks.php" 
                               class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                               Dashboard
                            </a>
                            <a href="/capstone/CoffeePass/users/manageAccount.php" 
                               class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                               Show QR Code
                            </a>
                            <a href="/capstone/CoffeePass/users/searchCafes.php" 
                               class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                               Search Cafes             
                            </a>
                           
                            <a href="/capstone/CoffeePass/users/reviewStores.php" 
                               class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                               Review Stores           
                            </a>
                            <a href="" 
                               class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                               Visited           
                            </a>
                            <a href="" 
                               class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                               Wish List          
                            </a>
                           
                            <a href="" 
                               class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                               events        
                            </a>



                            <?php if ($_SESSION['is_admin'] === 'Yes'): // Only for admins ?>
                                <a href="/capstone/CoffeePass/admin/adminDashboard.php" 
                                   class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                                    Admin Dashboard
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                <div class="hidden sm:flex sm:space-x-4">
                    <?php if (isset($_SESSION['id'])): ?>
                        <a href="/capstone/CoffeePass/users/logout.php" 
                           class="rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="/capstone/CoffeePass/login.php" 
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
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="hidden sm:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pt-2 pb-3">
            <?php if (isset($_SESSION['id'])): ?>
                <a href="/capstone/CoffeePass/users/manageParks.php" 
                   class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                    Manage Parks
                </a>
                <a href="/capstone/CoffeePass/users/manageAccount.php" 
                   class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                    Manage My Account
                </a>
                <?php if ($_SESSION['is_admin'] === 'Yes'): ?>
                    <a href="/capstone/CoffeePass/admin/adminDashboard.php" 
                       class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                        Admin Dashboard
                    </a>
                <?php endif; ?>
                <a href="/capstone/CoffeePass/users/logout.php" 
                   class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                    Logout
                </a>
            <?php else: ?>
                <a href="/capstone/CoffeePass/login.php" 
                   class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">
                    Login
                </a>
                <a href="/capstone/CoffeePass/public/register.php" 
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