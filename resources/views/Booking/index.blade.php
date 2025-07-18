<x-app-layout>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">Bookings</h1>
            <p class="text-sm text-gray-500">Manage your booking here.</p>
        </div>
        <!-- <a href="{{ route('booking.create') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-black border border-transparent rounded-md shadow-sm hover:bg-black/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
            Add Booking
        </a> -->
    </div>
    <div class="rounded-lg border shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Package name
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Booked By
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Created At
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Updated At
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($bookings as $booking)
                
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ ucfirst($booking->status) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $booking->package->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $booking->user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $booking->created_at }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $booking->updated_at }}</div>
                        </td>
                        <!-- <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div id="app-{{ $booking->id }}" class="relative inline-block text-left">
                                <dropdown-menu :edit-url="'{{ route('booking.edit', $booking->id) }}'"
                                    :delete-url="'{{ route('booking.destroy', $booking->id) }}'"></dropdown-menu>
                            </div>
                        </td> -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $bookings->links('components.pagination') }}
    </div>
</x-app-layout>
