<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/geist@1.3.1/dist/font.min.js"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-background font-geist antialiased">
    <div id="app" class="relative flex min-h-screen flex-col bg-background">
        <div class="themes-wrapper bg-background w-full h-full">
            <div class="flex min-h-screen w-full">
                @include('layouts.sidebar')
                <div class="flex flex-col flex-grow w-full">
                    <header
                        class="flex items-center justify-between gap-4 border-b bg-gray-50 px-4 h-14 lg:h-[60px] lg:px-6">
                        <!-- Left section (mobile menu toggle) -->
                        <div class="md:hidden">
                            <button @click="toggleMobileMenu"
                                class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 w-9 shrink-0"
                                type="button" :aria-expanded="mobileMenuOpen">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu h-5 w-5">
                                    <line x1="4" x2="20" y1="12" y2="12"></line>
                                    <line x1="4" x2="20" y1="6" y2="6"></line>
                                    <line x1="4" x2="20" y1="18" y2="18"></line>
                                </svg>
                                <span class="sr-only">Toggle navigation menu</span>
                            </button>
                        </div>

                        <!-- Center section (search) -->
                        <div class="flex-grow max-w-2xl mx-auto">
                        </div>

                        <!-- Right section (user info and menu) -->
                        <div class="relative flex items-center gap-2">
                            <span class="hidden sm:inline-block">{{ Auth::user()->name }}</span>
                            <button @click="toggleUserMenu"
                                class="flex items-center justify-center whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-secondary text-secondary-foreground shadow-sm hover:bg-secondary/80 h-9 w-9 rounded-full"
                                type="button" :aria-expanded="userMenuOpen">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-circle-user h-5 w-5">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <circle cx="12" cy="10" r="3"></circle>
                                    <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"></path>
                                </svg>
                            </button>
                            <transition name="fade">
                                <div v-if="userMenuOpen"
                                    class="absolute right-0 top-12 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                                    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                    tabindex="-1">
                                    <a href="#" class="block px-3 py-1 text-sm leading-6 text-gray-900"
                                        role="menuitem" tabindex="-1" id="user-menu-item-0">Settings</a>
                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-responsive-nav-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                            this.closest('form').submit();"
                                            class="!border-0 hover:border-0 text-sm !text-gray-700 hover:!bg-white">
                                            {{ __('Log Out') }}
                                        </x-responsive-nav-link>
                                    </form>
                                </div>
                            </transition>
                        </div>
                    </header>
                    <main class="flex-grow p-4 lg:p-6">
                        {{ $slot }}
                    </main>
                </div>
            </div>
            <!-- Overlay for closing sidebar by clicking outside -->
            <div v-if="mobileMenuOpen && !isDesktop" @click="toggleMobileMenu"
                class="fixed inset-0 z-10 bg-black opacity-50"></div>
        </div>
    </div>

</body>

</html>
