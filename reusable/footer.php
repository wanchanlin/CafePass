<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<footer class="bg-white shadow-md mt-8">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between space-y-4 sm:flex-row sm:space-y-0">
            <div class="flex items-center">
                <a href="/capstone/CoffeePass/index.php">
                    <img class="h-[360px] w-auto object-contain" src="/capstone/CoffeePass/images/Logo.svg" alt="Coffee Pass">
                </a>
            </div>
            <div class="flex space-x-6">
                <a href="#" class="text-gray-500 hover:text-gray-900">
                    <span class="sr-only">Facebook</span>
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-900">
                    <span class="sr-only">Instagram</span>
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-900">
                    <span class="sr-only">Twitter</span>
                    <i class="fab fa-twitter"></i>
                </a>
            </div>
        </div>
        <div class="mt-8 border-t border-gray-200 pt-8">
            <p class="text-center text-base text-gray-500">
                &copy; 2024 Coffee Pass. All rights reserved.
            </p>
        </div>
    </div>
</footer>