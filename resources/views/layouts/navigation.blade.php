<nav class="grid items-start px-2 text-sm font-medium lg:px-4">
    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
        Dashboard
    </x-nav-link>
    <x-nav-link href="{{ route('city.index') }}" :active="request()->routeIs('city.index')">
        Cities
    </x-nav-link>
    <x-nav-link href="{{ route('destination.index') }}" :active="request()->routeIs('destination.index')">
        Destinations
    </x-nav-link>
    <nav class="grid items-start px-2 text-sm font-normal lg:pl-2">
        <x-nav-link href="{{ route('hotel.index') }}">
            • Hotels
        </x-nav-link>
        <x-nav-link href="{{ route('pharmacy.index') }}">
            • Pharmacies
        </x-nav-link>
        <x-nav-link href="{{ route('destination.index') }}">
            • Hospitals
        </x-nav-link>
        <x-nav-link href="{{ route('destination.index') }}">
            • Colleges & Schools
        </x-nav-link>
    </nav>
    <x-nav-link href="#" :active="request()->routeIs('cities')">
        Pages
    </x-nav-link>

    <span class="text-gray-500 text-xs font-semibold mt-10">Planned for future</span>
    <a href="#"
        class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 font-semibold hover:bg-gray-200 transition-all hover:text-primary">Users
    </a>
    <a href="#"
        class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 font-semibold hover:bg-gray-200 transition-all hover:text-primary">Orders
    </a>
    <a href="#"
        class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 font-semibold hover:bg-gray-200 transition-all hover:text-primary">Packages
    </a>
    <a href="#"
        class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 font-semibold hover:bg-gray-200 transition-all hover:text-primary">Hospitals
    </a>
    <a href="#"
        class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 font-semibold hover:bg-gray-200 transition-all hover:text-primary">Schools
        and Collages
    </a>
</nav>
