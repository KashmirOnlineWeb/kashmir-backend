<x-app-layout>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">Users</h1>
            <p class="text-sm text-gray-500">Manage your users here.</p>
        </div>
        <a href="{{ route('user.create') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-black border border-transparent rounded-md shadow-sm hover:bg-black/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
            Add User
        </a>
    </div>
    <div class="rounded-lg border shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Mobile
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Role
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
                @foreach ($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->mobile }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ ucwords(implode(', ', $user->getRoleNames()->toArray())) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->created_at }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->updated_at }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 cursor-pointer">
                                <a href="{{ route('user.show', $user->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 5c-6.307 0-9.367 5.683-9.91 6.808a.44.44 0 0 0 0 .384C2.632 13.317 5.692 19 12 19s9.367-5.683 9.91-6.808a.44.44 0 0 0 0-.384C21.368 10.683 18.308 5 12 5"/><circle cx="12" cy="12" r="3"/></g></svg>
                                </a>    
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div id="app-{{ $user->id }}" class="relative inline-block text-left">
                                <dropdown-menu :edit-url="'{{ route('user.edit', $user->id) }}'"
                                    :delete-url="'{{ route('user.destroy', $user->id) }}'"></dropdown-menu>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $users->links('components.pagination') }}
    </div>
</x-app-layout>
