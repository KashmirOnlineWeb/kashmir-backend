<nav class="grid items-start px-2 text-sm font-medium lg:px-4">
    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
        Dashboard
    </x-nav-link>
    <x-nav-link href="#" :active="request()->routeIs('cities')">
        Pages
    </x-nav-link>
    <!-- <x-nav-link href="{{ route('city.index') }}" :active="request()->routeIs('city.index')">
        Cities
    </x-nav-link> -->
    <x-nav-link href="{{ route('destination.index') }}" :active="request()->routeIs('destination.index')">
        Destinations
    </x-nav-link>
    <nav class="grid items-start px-2 text-sm font-normal lg:pl-2">
        <x-nav-link href="{{ route('hotel.index') }}" :active="request()->routeIs('hotel.index')">
            • Hotels
        </x-nav-link>
        <x-nav-link href="{{ route('pharmacy.index') }}" :active="request()->routeIs('pharmacy.index')">
            • Pharmacies
        </x-nav-link>
        <x-nav-link href="{{ route('hospital.index') }}" :active="request()->routeIs('hospital.index')">
            • Hospitals
        </x-nav-link>
        <x-nav-link href="{{ route('collageandschool.index') }}" :active="request()->routeIs('collageandschool.index')">
            • Colleges & Schools
        </x-nav-link>
        <x-nav-link href="{{ route('restaurant.index') }}" :active="request()->routeIs('restaurant.index')">
            • Restaurants
        </x-nav-link>
        <x-nav-link href="#">
            • Shopping Places
        </x-nav-link>
        <x-nav-link href="#">
            • Things to be noted
        </x-nav-link>
        <x-nav-link href="#">
            • Houseboats
        </x-nav-link>
        <x-nav-link href="#">
            • Sightseeings
        </x-nav-link>
        <x-nav-link href="#">
            • Religions Places
        </x-nav-link>
        <x-nav-link href="#">
            • How to reach
        </x-nav-link>
        <x-nav-link href="#">
            • Safety Information
        </x-nav-link>
        <x-nav-link href="#">
            • General Information
        </x-nav-link>
        <x-nav-link href="#">
            • Locations
        </x-nav-link>
    </nav>

    <span class="text-gray-500 text-xs font-semibold mt-10 border-t border-gray-300 pt-4">Planned for future</span>
    <a href="#"
        class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 font-semibold hover:bg-gray-200 transition-all hover:text-primary">Users
    </a>
    <a href="#"
        class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 font-semibold hover:bg-gray-200 transition-all hover:text-primary">Packages
    </a>
    <a href="#"
        class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 font-semibold hover:bg-gray-200 transition-all hover:text-primary">Shop</a>
    <nav class="grid items-start px-2 text-sm font-normal lg:pl-2">
        <x-nav-link href="#">
            • Products
        </x-nav-link>
        <x-nav-link href="#">
            • Orders
        </x-nav-link>
        <x-nav-link href="#">
            • Payments
        </x-nav-link>
        <x-nav-link href="#">
            • Refunds
        </x-nav-link>
    </nav>
</nav>
