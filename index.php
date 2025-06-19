<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Pass</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/cafe.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


</head>

<body>
    <!-- nav section -->
   <?php include('reusable/nav.php'); ?>
    <!-- nav section end -->
    <!-- hero section -->
    <div class="bg-white">
        <div class="mx-auto max-w-7xl sm:px-6  lg:px-8">
            <div
                class="relative isolate overflow-hidden px-6 pt-16 sm:rounded-3xl sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0">

                <div class="mx-auto max-w-md text-center lg:mx-0 lg:flex-auto lg:py-32 lg:text-left">
                    <h2 class="text-3xl font-semibold tracking-tight text-balance  sm:text-4xl">Explore Toronto One Sip
                        at a Time</h2>
                    <p class="mt-6 text-lg/8 text-pretty ">Your passport to the city's best indie cafés. Check in, earn rewards, and uncover signature drinks across Toronto.</p>
                    <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start">
                        <button class="rounded-md px-3 py-2 text-sm font-medium"> <a href="#">Get
                                started</a>
                        </button>
                        <button class="rounded-md px-3 py-2 text-sm font-medium">
                            <a href="#">Learn
                                more <span aria-hidden="true">→</span></a>
                        </button>
                    </div>
                </div>
                <div class="relative mt-16 h-80  lg:mt-8">
                    <img class="mx-auto sm:h-80" src="images/coffeegathering.png" alt="cofee">
                </div>
            </div>
        </div>
    </div>

    <!-- hero section end -->
    <!-- how it work section -->
    <section class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 class="text-center text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl"> How It
                Works </h2>
            <div class="mx-auto mt-10 max-w-2xl lg:mx-0 lg:max-w-none space-x-6 lg:flex-row flex flex-col">
                <div class=" text-center relative flex flex-col my-6w-96 p-6 ">
                    <p class=" mx-auto text-dark-text bg-primary-green
                            font-medium relative rounded-full block text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                        STEP 1</p>
                    <div class="w-60 h-60 mx-auto flex items-center justify-center mb-4">
                        <i class="fas fa-map-marker-alt text-6xl text-green-600"></i>
                    </div>
                    <h5 class="ml-3 text-slate-800 text-xl font-semibold">Find a Café</h5>
                    <p class="block text-slate-600 leading-normal font-light mb-4">
                        Browse a curated map of indie coffee shops near you.
                    </p>
                </div>
                <div class=" text-center relative flex flex-col my-6w-96 p-6 ">
                    <p class=" mx-auto text-dark-text bg-primary-green
                            font-medium relative rounded-full block text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                        STEP 2</p>
                    <div class="w-60 h-60 mx-auto flex items-center justify-center mb-4">
                        <i class="fas fa-qrcode text-6xl text-green-600"></i>
                    </div>
                    <h5 class="ml-3 text-slate-800 text-xl font-semibold">Check In</h5>
                    <p class="block text-slate-600 leading-normal font-light mb-4">
                        Scan the café's QR code or get your physical Coffee Pass stamped. </p>
                </div>
                <div class=" text-center relative flex flex-col my-6w-96 p-6 ">
                    <p class=" mx-auto text-dark-text bg-primary-green
                        font-medium relative rounded-full block text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                        STEP 3</p>
                    <div class="w-60 h-60 mx-auto flex items-center justify-center mb-4">
                        <i class="fas fa-gift text-6xl text-green-600"></i>
                    </div>
                    <h5 class="ml-3 text-slate-800 text-xl font-semibold">Collect Rewards</h5>
                    <p class="block text-slate-600 leading-normal font-light mb-4">
                        Earn points, unlock secret menu items, and redeem free drinks! </p>
                </div>
            </div>
        </div>
    </section>
    <!-- why join section -->
    <section class="bg-white p-24  sm:py-32">
        <h2 class="text-center tracking-tight text-pretty text-gray-900 sm:text-5xl">
             Why Join Coffee Pass?</h2>
          <ul role="list" class="mx-auto grid max-w-7xl grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2  my-10">
            <li>
              <div class="flex items-center gap-x-6">
                <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-store text-3xl text-green-600"></i>
                </div>
                <div>
                  <p class="text-sm/6 font-semibold text-gray-900">Support local, independent cafés</p>
                </div>
              </div>
            </li>
             <li>
              <div class="flex items-center gap-x-6">
                <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-mug-hot text-3xl text-green-600"></i>
                </div>
                <div>
                  <p class="text-sm/6 font-semibold "> Discover each café's signature drink</p>
                </div>
              </div>
            </li>
            <li>
                <div class="flex items-center gap-x-6">
                  <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-stamp text-3xl text-green-600"></i>
                  </div>
                  <div>
                    <p class="text-sm/6 font-semibold "> Collect digital stamps & real rewards</p>
                  </div>
                </div>
              </li>
              <li>
                <div class="flex items-center gap-x-6">
                  <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-book-open text-3xl text-green-600"></i>
                  </div>
                  <div>
                    <p class="text-sm/6 font-semibold "> Log your coffee journey in your Brew Log</p>
                  </div>
                </div>
              </li>
      
            <!-- More details... -->
          </ul>
        
      </section>
    <!-- how it works section end -->

    <section class=" py-24 sm:py-32">
        <h2 class="text-center tracking-tight text-pretty text-gray-900 sm:text-5xl">
            Who It's For?</h2>
       
            <div class="relative isolate overflow-hidden  px-6 py-24 sm:py-32 lg:overflow-visible lg:px-0">
               
                <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 lg:mx-0 lg:max-w-none lg:grid-cols-2 lg:items-start lg:gap-y-10">
                  <div class="lg:col-span-2 lg:col-start-1 lg:row-start-1 lg:mx-auto lg:grid lg:w-full lg:max-w-7xl lg:grid-cols-2 lg:gap-x-8 lg:px-8">
                    <div class="lg:col-start-2 lg:pr-4">
                      <div class="lg:max-w-lg">
                        <p class="text-base/7 font-semibold text-green">Coffee Drinkers</p>
                        <h3 class="mt-2 text-3xl font-semibold tracking-tight text-pretty text-gray-900 ">Explore new cafés, earn perks, and track your coffee journey.</h3>
                        <p class="mt-6 text-xl/8 text-gray-700">Discover new cafés, earn rewards, and track your coffee journey.</p>
                      </div>
                    </div>
                    <div class="-mt-12 -ml-auto p-12 lg:sticky lg:top-4 lg:col-start-1 lg:row-span-2 lg:row-start-1 lg:overflow-hidden">
                    <img class="h-[360px] w-auto object-contain" src="images/coffeedrinker.png" alt="">
                  </div>
                  </div>
                  
                </div>
            </div>
            <div class="relative isolate overflow-hidden px-6 py-24 sm:py-32 lg:overflow-visible lg:px-0">
                <div class="absolute inset-0 -z-10 overflow-hidden">
                  <svg class="absolute top-0 left-[max(50%,25rem)] h-256 w-512 -translate-x-1/2 mask-[radial-gradient(64rem_64rem_at_top,white,transparent)] stroke-gray-200" aria-hidden="true">
                    <defs>
                      <pattern id="e813992c-7d03-4cc4-a2bd-151760b470a0" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                        <path d="M100 200V.5M.5 .5H200" fill="none" />
                      </pattern>
                    </defs>
                  </svg>
                </div>
                <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 lg:mx-0 lg:max-w-none lg:grid-cols-2 lg:items-start lg:gap-y-10">
                  <div class="lg:col-span-2 lg:col-start-1 lg:row-start-1 lg:mx-auto lg:grid lg:w-full lg:max-w-7xl lg:grid-cols-2 lg:gap-x-8 lg:px-8">
                    <div class="lg:pr-4">
                      <div class="lg:max-w-lg">
                        <p class="text-base/7 font-semibold text-green">Cafe Owner</p>
                        <h3 class="mt-2 text-3xl font-semibold tracking-tight text-pretty text-gray-900 ">Attract new customers and boost loyalty with real-time insights and reward tools.</h3>
                        <p class="mt-6 text-xl/8 text-gray-700">Understand your customers, boost loyalty, and grow your business with our tools.</p>
                      </div>
                    </div>
                     <div class="-mt-12 -ml-12 p-12 lg:sticky lg:top-4 lg:col-start-2 lg:row-span-2 lg:row-start-1 lg:overflow-hidden">
                    <img class="h-[360px] w-auto object-contain" src="images/coffeeStore.png" alt="">
                  </div>
                  </div>
                 
                </div>
            </div>
              
      

    </section>

    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto  lg:mx-0">
                <h2 class="text-center tracking-tight text-pretty text-gray-900 sm:text-5xl">Real
                    Coffee Lovers, Real Reviews
                </h2>

            </div>
            <div
                class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16  pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3 ">
                <article
                    class="flex max-w-xl flex-col items-start justify-between border-gray-200 border-2 rounded-lg p-4">

                    <div class="group relative ">

                        <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">"I discovered so many cozy cafés I'd never
                            heard of before. The rewards are a nice bonus!"</p>
                    </div>
                    <div class="relative mt-8 flex items-center gap-x-4">
                        <img src="images/avatar.jpg" alt="" class=" w-12 rounded-full bg-gray-50">
                        <div class="text-sm/6">
                            <p class="font-semibold text-gray-900">
                                <a href="#">
                                    <span class="absolute inset-0"></span>
                                    Emma T
                                </a>
                            </p>
                            <p class="text-gray-600">Student at UofT</p>
                        </div>
                    </div>
                </article>
                <article
                    class="flex max-w-xl flex-col items-start justify-between border-gray-200 border-2 rounded-lg p-4">

                    <div class="group relative ">

                        <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">"I discovered so many cozy cafés I'd never
                            heard of before. The rewards are a nice bonus!"</p>
                    </div>
                    <div class="relative mt-8 flex items-center gap-x-4">
                        <img src="images/avatar.jpg" alt="" class=" w-12 rounded-full bg-gray-50">
                        <div class="text-sm/6">
                            <p class="font-semibold text-gray-900">
                                <a href="#">
                                    <span class="absolute inset-0"></span>
                                    Emma T
                                </a>
                            </p>
                            <p class="text-gray-600">Student at UofT</p>
                        </div>
                    </div>
                </article>

                <!-- More posts... -->
            </div>
        </div>
    </div>
    <!-- call to action section -->
    <section class="bg-gray-100 py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl">Join the
                    Coffee Adventure</h2>
                <p class="mt-6 text-lg/8 text-pretty text-gray-600">Sign up today and start exploring Toronto's indie
                    coffee scene!</p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <button class="  font-semibold rounded-md px-3 py-2 text-sm font-medium ">
                        <a href="#">Get started</a></button>
                    <button class="  font-semibold rounded-md px-3 py-2 text-sm font-medium ">
                        <a href="#" class="text-sm/6 font-semibold ">Learn more <span aria-hidden="true">→</span></a>
                </div>
            </div>
        </div>
    </section>

   <?php include('reusable/footer.php'); ?>

   

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
</body>

</html>