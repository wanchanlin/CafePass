<?php
require_once 'reusable/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Cafés - CoffeePass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/cafe.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include('reusable/nav.php'); ?>

    <!-- Hero Section -->
    <div class="bg-white">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="relative isolate overflow-hidden px-6 pt-16 sm:rounded-3xl sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0">
                <div class="mx-auto max-w-md text-center lg:mx-0 lg:flex-auto lg:py-32 lg:text-left">
                    <h2 class="text-3xl font-semibold tracking-tight text-balance sm:text-4xl">Discover Toronto's Best Cafés</h2>
                    <p class="mt-6 text-lg/8 text-pretty">Find your perfect coffee spot, from cozy neighborhood gems to trendy urban hangouts.</p>
                    <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start">
                        <button class="rounded-md bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700">
                            <a href="#featured">Featured Cafés</a>
                        </button>
                        <button class="rounded-md px-3 py-2 text-sm font-medium">
                            <a href="#categories">Browse Categories <span aria-hidden="true">→</span></a>
                        </button>
                    </div>
                </div>
                <div class="relative mt-16 h-80 lg:mt-8">
                    <img class="mx-auto sm:h-80" src="images/coffeegathering.png" alt="Coffee Gathering">
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Cafés Section -->
    <section id="featured" class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 class="text-center text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl">Featured Cafés</h2>
            <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <!-- Café Card 1 -->
                <div class="flex flex-col items-start border-2 rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <img class="w-full h-48 object-cover rounded-lg mb-4" src="images/coffeeStore.png" alt="Morning Brew">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-coffee text-green-600"></i>
                        <h3 class="text-xl font-semibold">Morning Brew</h3>
                    </div>
                    <p class="text-gray-600 mb-2">123 Coffee Street, Downtown</p>
                    <p class="text-yellow-500">⭐ 4.8 (120 reviews)</p>
                </div>

                <!-- Café Card 2 -->
                <div class="flex flex-col items-start border-2 rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <img class="w-full h-48 object-cover rounded-lg mb-4" src="images/coffeedrinker.png" alt="Urban Grind">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-coffee text-green-600"></i>
                        <h3 class="text-xl font-semibold">Urban Grind</h3>
                    </div>
                    <p class="text-gray-600 mb-2">456 Bean Avenue, Westside</p>
                    <p class="text-yellow-500">⭐ 4.6 (98 reviews)</p>
                </div>

                <!-- Café Card 3 -->
                <div class="flex flex-col items-start border-2 rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <img class="w-full h-48 object-cover rounded-lg mb-4" src="images/coffeegathering.png" alt="Artisan Roast">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-coffee text-green-600"></i>
                        <h3 class="text-xl font-semibold">Artisan Roast</h3>
                    </div>
                    <p class="text-gray-600 mb-2">789 Brew Lane, Eastside</p>
                    <p class="text-yellow-500">⭐ 4.9 (156 reviews)</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="bg-gray-50 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-semibold tracking-tight text-gray-900 sm:text-4xl">Find Your Perfect Café</h2>
                <p class="mt-4 text-lg text-gray-600">Search by location, amenities, or coffee style</p>
            </div>
            <div class="mx-auto mt-10 max-w-xl">
                <div class="flex gap-4">
                    <input type="text" placeholder="Enter your location..." class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <button class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 class="text-center text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl">Café Categories</h2>
            <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-4">
                <div class="flex flex-col items-center text-center p-6 border-2 rounded-lg hover:shadow-lg transition-shadow">
                    <i class="fas fa-wifi text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Work-Friendly</h3>
                    <p class="text-gray-600">Perfect for remote work</p>
                </div>
                <div class="flex flex-col items-center text-center p-6 border-2 rounded-lg hover:shadow-lg transition-shadow">
                    <i class="fas fa-book text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Study Spots</h3>
                    <p class="text-gray-600">Quiet spaces for studying</p>
                </div>
                <div class="flex flex-col items-center text-center p-6 border-2 rounded-lg hover:shadow-lg transition-shadow">
                    <i class="fas fa-users text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Social Hubs</h3>
                    <p class="text-gray-600">Great for meeting friends</p>
                </div>
                <div class="flex flex-col items-center text-center p-6 border-2 rounded-lg hover:shadow-lg transition-shadow">
                    <i class="fas fa-leaf text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Eco-Friendly</h3>
                    <p class="text-gray-600">Sustainable practices</p>
                </div>
            </div>
        </div>
    </section>

    <?php include('reusable/footer.php'); ?>
</body>
</html> 