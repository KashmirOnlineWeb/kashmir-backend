<nav class="grid items-start px-2 text-sm font-medium lg:px-4">
    <!-- Static Links -->
    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
        Dashboard
    </x-nav-link>
    <x-nav-link href="{{ route('menus.index') }}" :active="request()->routeIs('page.menus')">
        Menus
    </x-nav-link>
    <x-nav-link href="{{ route('page.index') }}" :active="request()->routeIs('page.index')">
        Pages
    </x-nav-link>

    <!-- Packages Section -->
    <x-nav-link href="{{ route('package.index') }}" :active="request()->routeIs('package.index')">
        Packages
    </x-nav-link>
    @if(request()->routeIs(['package.index', 'category.index']))
        <nav class="grid items-start px-2 text-sm font-normal lg:pl-2">
            <x-nav-link href="{{ route('category.index') }}" :active="request()->routeIs('category.index')">
                • Categories
            </x-nav-link>
            <x-nav-link href="{{ route('category.index') }}" :active="request()->routeIs('category.index')">
                • Bookings
            </x-nav-link>
        </nav>
    @endif

    <x-nav-link href="#" :active="false">
        Payments
    </x-nav-link>

    <!-- Destinations Section -->
    <x-nav-link href="{{ route('destination.index') }}" :active="request()->routeIs('destination.index')">
        Destinations
    </x-nav-link>
    @if(request()->routeIs([
        'destination.index',
        'hotel.index',
        'pharmacy.index',
        'hospital.index',
        'collageandschool.index',
        'restaurant.index',
        'shoppingplace.index',
        'thingstobenoted.index',
        'thingstodo.index',
        'backgroundhistory.index',
        'sightseeing.index',
        'religiousplace.index',
        'howtoreach.index',
        'safetyinformation.index',
        'generalinformation.index',
        'location.index'
    ]))
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
            <x-nav-link href="{{ route('shoppingplace.index') }}" :active="request()->routeIs('shoppingplace.index')">
                • Shopping Places
            </x-nav-link>
            <x-nav-link href="{{ route('thingstobenoted.index') }}" :active="request()->routeIs('thingstobenoted.index')">
                • Things to be noted
            </x-nav-link>
            <x-nav-link href="{{ route('thingstodo.index') }}" :active="request()->routeIs('thingstodo.index')">
                • Things to do
            </x-nav-link>
            <x-nav-link href="{{ route('backgroundhistory.index') }}" :active="request()->routeIs('backgroundhistory.index')">
                • Background & History
            </x-nav-link>
            <x-nav-link href="{{ route('sightseeing.index') }}" :active="request()->routeIs('sightseeing.index')">
                • Sightseeings
            </x-nav-link>
            <x-nav-link href="{{ route('religiousplace.index') }}" :active="request()->routeIs('religiousplace.index')">
                • Religious Places
            </x-nav-link>
            <x-nav-link href="{{ route('howtoreach.index') }}" :active="request()->routeIs('howtoreach.index')">
                • How to reach
            </x-nav-link>
            <x-nav-link href="{{ route('safetyinformation.index') }}" :active="request()->routeIs('safetyinformation.index')">
                • Safety Information
            </x-nav-link>
            <x-nav-link href="{{ route('generalinformation.index') }}" :active="request()->routeIs('generalinformation.index')">
                • General Information
            </x-nav-link>
            <x-nav-link href="{{ route('location.index') }}" :active="request()->routeIs('location.index')">
                • Locations
            </x-nav-link>
        </nav>
    @endif

    <!-- Future Section -->
    <span class="text-gray-500 text-xs font-semibold mt-10 border-t border-gray-300 pt-4">
        Planned for future
    </span>
    
    <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 font-semibold hover:bg-gray-200 transition-all hover:text-primary">
        Users
    </a>
    
    <!-- Shop Section -->
    <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 font-semibold hover:bg-gray-200 transition-all hover:text-primary">
        Shop
    </a>
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
