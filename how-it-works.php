<?php
require_once 'reusable/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How It Works - CoffeePass</title>
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
                    <h2 class="text-3xl font-semibold tracking-tight text-balance sm:text-4xl">How CoffeePass Works</h2>
                    <p class="mt-6 text-lg/8 text-pretty">Your journey to discovering Toronto's best cafés starts here. Learn how to make the most of your CoffeePass experience.</p>
                    <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start">
                        <button class="rounded-md bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700">
                            <a href="#steps">Get Started</a>
                        </button>
                        <button class="rounded-md px-3 py-2 text-sm font-medium">
                            <a href="#benefits">View Benefits <span aria-hidden="true">→</span></a>
                        </button>
                    </div>
                </div>
                <div class="relative mt-16 h-80 lg:mt-8">
                    <img class="mx-auto sm:h-80" src="images/coffeegathering.png" alt="Coffee Gathering">
                </div>
            </div>
        </div>
    </div>

    <!-- Steps Section -->
    <section id="steps" class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 class="text-center text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl">Getting Started with CoffeePass</h2>
            <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <!-- Step 1 -->
                <div class="flex flex-col items-center text-center p-6 border-2 rounded-lg hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user-plus text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">1. Create Your Account</h3>
                    <p class="text-gray-600">Sign up for free and create your personalized profile. Tell us about your coffee preferences.</p>
                </div>

                <!-- Step 2 -->
                <div class="flex flex-col items-center text-center p-6 border-2 rounded-lg hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">2. Explore Cafés</h3>
                    <p class="text-gray-600">Browse through our curated list of cafés. Filter by location, amenities, and coffee style.</p>
                </div>

                <!-- Step 3 -->
                <div class="flex flex-col items-center text-center p-6 border-2 rounded-lg hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-coffee text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">3. Enjoy Your Coffee</h3>
                    <p class="text-gray-600">Visit your chosen café, show your CoffeePass, and enjoy exclusive member benefits.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="bg-gray-50 py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 class="text-center text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl">Member Benefits</h2>
            <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-2">
                <!-- Benefit 1 -->
                <div class="flex items-start gap-4 p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex-shrink-0">
                        <i class="fas fa-percent text-3xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Exclusive Discounts</h3>
                        <p class="text-gray-600">Enjoy special member-only discounts at participating cafés across Toronto.</p>
                    </div>
                </div>

                <!-- Benefit 2 -->
                <div class="flex items-start gap-4 p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex-shrink-0">
                        <i class="fas fa-star text-3xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Rewards Program</h3>
                        <p class="text-gray-600">Earn points with every visit and redeem them for free coffee and exclusive perks.</p>
                    </div>
                </div>

                <!-- Benefit 3 -->
                <div class="flex items-start gap-4 p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex-shrink-0">
                        <i class="fas fa-bell text-3xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Early Access</h3>
                        <p class="text-gray-600">Be the first to know about new café openings and special events.</p>
                    </div>
                </div>

                <!-- Benefit 4 -->
                <div class="flex items-start gap-4 p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-3xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Community Events</h3>
                        <p class="text-gray-600">Join exclusive coffee tastings, barista workshops, and networking events.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-semibold tracking-tight text-gray-900 sm:text-4xl">Ready to Start Your Coffee Journey?</h2>
                <p class="mt-4 text-lg text-gray-600">Join CoffeePass today and discover Toronto's best cafés.</p>
                <div class="mt-8 flex justify-center gap-4">
                    <a href="signup.php" class="rounded-md bg-green-600 px-6 py-3 text-sm font-medium text-white hover:bg-green-700">
                        Sign Up Now
                    </a>
                    <a href="explore-cafes.php" class="rounded-md px-6 py-3 text-sm font-medium text-gray-900 hover:text-green-600">
                        Explore Cafés
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include('reusable/footer.php'); ?>
</body>
</html> 