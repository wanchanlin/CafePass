<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    // Get the current page name for active state
    $current_page = basename($_SERVER['PHP_SELF']);

    // Define base path
    $base_path = '/cafepass';
}
?>
<footer class="rounded-lg ">
    <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
        <img src="<?php echo $base_path; ?>/images/logo.svg" alt="Coffee Pass">
        <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2025 <a href="#"
                class="hover:underline">CafePass™</a>. All Rights Reserved.
        </span>
        <ul class="flex flex-wrap items-center mt-3 gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
            <li>
                <a href="#" class="hover:underline me-4 md:me-6">About</a>
            </li>
            <li>
                <a href="#" class="hover:underline me-4 md:me-6">Privacy Policy</a>
            </li>
            <li>
                <a href="#" class="hover:underline me-4 md:me-6">Licensing</a>
            </li>
            <li>
                <a href="#" class="hover:underline">Contact</a>
            </li>
        </ul>
    </div>
</footer>