<div class="border-r bg-gray-50 fixed inset-y-0 left-0 z-20 w-72 transform transition-transform duration-300 ease-in-out"
    :class="{
        '-translate-x-full': !mobileMenuOpen && !isDesktop,
        'translate-x-0': mobileMenuOpen || isDesktop,
        'md:relative md:translate-x-0': isDesktop
    }">
    <div class="flex h-full max-h-screen flex-col gap-2">
        <div class="flex h-14 items-center justify-between border-b px-4 lg:h-[60px] lg:px-6">
            <a href="/dashboard" class="flex items-center gap-2 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-package2-icon h-6 w-6">
                    <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                    <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"></path>
                    <path d="M12 3v6"></path>
                </svg>
                <span class="">Kashmir Online</span>
            </a>
            <button @click="toggleMobileMenu" class="md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-x h-6 w-6">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <span class="sr-only">Close menu</span>
            </button>
        </div>
        <div class="flex-1">
            @include('layouts.navigation')
        </div>
    </div>
</div>
