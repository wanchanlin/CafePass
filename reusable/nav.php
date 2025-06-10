<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- nav section -->
    <nav class="bg-white shadow-md">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <div class="flex flex-1 items-center justify-between sm:items-stretch sm:justify-start">
                    <div class="flex shrink-0 items-center">
                        <a href="/capstone/CoffeePass/index.php">
                            <img class="h-[360px] w-auto object-contain" src="/capstone/CoffeePass/images/Logo.svg" alt="Coffee Pass">
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-4">
                            <a href="/capstone/CoffeePass/index.php" 
                               class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white <?php echo $current_page === 'index.php' ? 'bg-gray-700 text-white' : ''; ?>">
                                HOME
                            </a>
                            <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white">
                                Explore Cafés
                            </a>
                            <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white">
                                How It Works
                            </a>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <div class="hidden sm:flex sm:space-x-4">
                        <button class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white">
                            <a href="/capstone/CoffeePass/login.php"> Login </a>
                        </button>
                        <button class="rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-700 hover:text-white"> 
                            <a href="/capstone/CoffeePass/public/register.php"> Sign Up </a>
                        </button>
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
                <a href="/capstone/CoffeePass/index.php" 
                   class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white">
                    HOME
                </a>
                <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white">
                    Explore Cafés
                </a>
                <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white">
                    How It Works
                </a>
                <a href="/capstone/CoffeePass/login.php" 
                   class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white">
                    Login
                </a>
                <a href="/capstone/CoffeePass/public/register.php" 
                   class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white">
                    Sign Up
                </a>
            </div>
        </div>
    </nav>

<script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    let isMenuOpen = false;

    mobileMenuButton.addEventListener('click', () => {
        isMenuOpen = !isMenuOpen;
        
        // Toggle menu with animation
        if (isMenuOpen) {
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('animate-fadeIn');
        } else {
            mobileMenu.classList.add('animate-fadeOut');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('animate-fadeOut');
            }, 300);
        }
        
        // Update button aria-expanded
        mobileMenuButton.setAttribute('aria-expanded', isMenuOpen);
    });
</script>
