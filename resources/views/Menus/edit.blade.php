<x-app-layout>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">Menu</h1>
        </div>
    </div>
    <div class="rounded-lg border shadow-sm">
        <!-- Manage menus with drag and drop here -->
    </div>
    <div class="mt-4">
        <form method="POST" action="{{ isset($menu) ? route('menus.update', $menu) : route('menus.store') }}">
            @csrf
            @if(isset($menu))
                @method('PUT')
            @endif
            <div class="flex w-full mb-4 border-b gap-4 border-gray-200">
                <div class="mb-4 w-full">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name"
                        required
                        value="{{ old('name', $menu->name ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4 w-full">
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                    <input type="text" name="slug" id="slug"
                        required
                        value="{{ old('slug', $menu->slug ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                    @error('slug')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="rounded-lg border shadow-sm">
                <menu-builder
                    :pages="{{ json_encode($pages) }}"
                    :existing-menu="{{ json_encode($existingMenu ?? []) }}"
                />
            </div>
        </form>
    </div>
</x-app-layout>
