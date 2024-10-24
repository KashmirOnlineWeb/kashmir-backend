<x-app-layout>
    <div id="app">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">
                    {{ isset($destination) ? 'Edit Destination' : 'Create Destination' }}
                </h1>
            </div>
        </div>
        <form
            action="{{ isset($destination) ? route('destination.update', $destination->id) : route('destination.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($destination))
                @method('PUT')
            @endif

            <!-- Display Errors -->
            @if ($errors->any())
                <div class="mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Whoops! Something went wrong.</strong></br>
                        <span class="block sm:inline">Please check the form for errors.</span>
                        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            
            <!-- Section 1 and Section 3 -->
            <div class="flex flex-wrap -mx-2 mb-4 border-b border-gray-200">
                <div class="w-full md:w-1/2 px-2 mb-4">
                    <div class="p-4">
                        <h2 class="text-md font-semibold mb-2">Basic Information</h2>
                        <p class="text-sm text-gray-600 mb-4">Provide the basic details of the destination.</p>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $destination->name ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <input type="text" name="slug" id="slug"
                                value="{{ old('slug', $destination->slug ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('slug')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/2 px-2 mb-4">
                    <div class="p-4">
                        <h2 class="text-md font-semibold mb-2">Featured Image</h2>
                        <p class="text-sm text-gray-600 mb-4">To show in cards.</p>
                        <div class="mb-4">
                            <image-uploader name="image" id="image"
                                v-bind:initial-file="'{{ old('image', $destination->image ?? '') }}'"
                                class="mt-1 block rounded-md border-gray-200 shadow-sm py-1"></image-uploader>
                        </div>
                        <div class="mb-4">
                            <label for="image_alt" class="block text-sm font-medium text-gray-700">Featured Image Alt
                                Text</label>
                            <input type="text" name="image_alt" id="image_alt"
                                value="{{ old('image_alt', $destination->image_alt ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Tabs for Content and Highlights -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Content & Highlights</h2>
                <p class="text-sm text-gray-600 mb-4">Add detailed descriptions and highlights of the destination.</p>
                <tabs>
                    <tab name="Content">
                        <div class="py-4 border-t border-gray-200">
                            <div class="mb-4">
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('description', $destination->description ?? '') }}</textarea>
                            </div>
                        </div>
                    </tab>
                    <tab name="Highlights">
                        <div class="py-4 border-t border-gray-200">
                            <div class="mb-4">
                                <label for="highlights_content"
                                    class="block text-sm font-medium text-gray-700">Highlights
                                    Content</label>
                                <textarea name="highlights_content" id="highlights_content"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('highlights_content', $destination->highlights_content ?? '') }}</textarea>
                            </div>
                        </div>
                    </tab>
                    <tab name="Slider">
                        <div class="py-4 border-t border-gray-200">
                        <slider-component 
                                :initial-data='@json(old("image_gallery", $destination->image_gallery ?? []))'
                                name-prefix="image_gallery"
                            ></slider-component>
                        </div>
                    </tab>
                </tabs>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                @enderror
                @error('highlights_content')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Section 4 -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Location and Type</h2>
                <p class="text-sm text-gray-600 mb-4">Select the city where the destination is located and it's type.
                </p>
                <div class="mb-4">
                    <label for="city_id" class="block text-sm font-medium text-gray-700">City</label>
                    <select name="city_id" id="city_id"
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ isset($destination) && $destination->city_id == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="destination_type" class="block text-sm font-medium text-gray-700">Destination
                        Type</label>
                    <select name="destination_type" id="destination_type"
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        <option value="1"
                            {{ isset($destination) && $destination->destination_type == 1 ? 'selected' : '' }}>
                            Non-Religious</option>
                        <option value="2"
                            {{ isset($destination) && $destination->destination_type == 2 ? 'selected' : '' }}>
                            Religious
                        </option>
                    </select>
                </div>
            </div>

            <!-- SEO Fields Section -->
            <div id="seo-fields">
                <seo-fields :meta-title="{{ json_encode(old('meta_title', $meta->meta_title ?? '')) }}"
                    :meta-description="{{ json_encode(old('meta_description', $meta->meta_description ?? '')) }}"
                    :keywords="{{ json_encode(old('keywords', $meta->keywords ?? '')) }}">
                </seo-fields>
            </div>

            <div class="mb-4">
                <button type="submit" :disabled="isUploading"
                    class="bg-black text-white p-2 rounded disabled:opacity-50">{{ isset($destination) ? 'Update Destination' : 'Save Destination' }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        selector: 'textarea#description, textarea#highlights_content',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        branding: false,
        promotion: false
    });
</script>
