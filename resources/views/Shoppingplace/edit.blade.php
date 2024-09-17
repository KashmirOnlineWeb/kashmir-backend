<x-app-layout>
    <div id="app">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">
                    {{ isset($shoppingplace) ? 'Edit Shopping Place' : 'Create Shopping Place' }}
                </h1>
            </div>
        </div>
        <form
            action="{{ isset($shoppingplace) ? route('shoppingplace.update', $shoppingplace->id) : route('shoppingplace.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($shoppingplace))
                @method('PUT')
            @endif
            <!-- Section 1 and Section 3 -->
            <div class="flex flex-wrap -mx-2 mb-4 border-b border-gray-200">
                <div class="w-full gap-4 px-2 mb-4">
                    <div class="p-4">
                        <h2 class="text-md font-semibold mb-2">Basic Information</h2>
                        <p class="text-sm text-gray-600 mb-4">Provide the basic details of the destination.</p>
                        <div class="flex w-full mb-4 border-b gap-4 border-gray-200">
                            <div class="mb-4 w-full">
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $shoppingplace->name ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4 w-full">
                                <label for="city_id" class="block text-sm font-medium text-gray-700">City</label>
                                <select name="city_id" id="city_id"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ isset($hotel) && $hotel->city_id == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Tabs for Content and Highlights -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Content</h2>
                <content-repeater :initialData="{{ json_encode(old('contents', $shoppingplace->contents ?? [])) }}"
                    @update:contents="updateContents" />
                @error('contents')
                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                @enderror
            </div>

            <div class="mb-4">
                <button type="submit" :disabled="isUploading"
                    class="bg-black text-white p-2 rounded disabled:opacity-50">{{ isset($shoppingplace) ? 'Update Shopping Place' : 'Save Shopping Place' }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
