<x-app-layout>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">
                {{ isset($city) ? 'Edit city' : 'Create city' }}
            </h1>
        </div>
    </div>
    <form action="{{ isset($city) ? route('city.update', $city->id) : route('city.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @if (isset($city))
            @method('PUT')
        @endif
        <!-- Section 1 and Section 3 -->
        <div class="flex flex-wrap -mx-2 mb-4 border-b border-gray-200">
            <div class="w-full md:w-1/2 px-2 mb-4">
                <div class="p-4">
                    <h2 class="text-md font-semibold mb-2">Basic Information</h2>
                    <p class="text-sm text-gray-600 mb-4">Provide the basic details of the city.</p>
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $city->name ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $city->slug ?? '') }}"
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
                            v-bind:initial-file="'{{ old('image', $city->image ?? '') }}'"
                            class="mt-1 block rounded-md border-gray-200 shadow-sm py-1"></image-uploader>
                    </div>
                    <div class="mb-4">
                        <label for="image_alt" class="block text-sm font-medium text-gray-700">Featured Image Alt
                            Text</label>
                        <input type="text" name="image_alt" id="image_alt"
                            value="{{ old('image_alt', $city->image_alt ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Tabs for Content and Highlights -->
        <div class="mb-4 p-4 border-b border-gray-200">
            <h2 class="text-md font-semibold mb-2">Content & Highlights</h2>
            <p class="text-sm text-gray-600 mb-4">Add detailed descriptions and highlights of the city.</p>
            <tabs>
                <tab name="Content">
                    <div class="py-4 border-t border-gray-200">
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('description', $city->description ?? '') }}</textarea>
                        </div>
                    </div>
                </tab>
                <tab name="Highlights">
                    <div class="py-4 border-t border-gray-200">
                        <div class="mb-4">
                            <label for="highlights_content" class="block text-sm font-medium text-gray-700">Highlights
                                Content</label>
                            <textarea name="highlights_content" id="highlights_content"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('highlights_content', $city->highlights_content ?? '') }}</textarea>
                        </div>
                    </div>
                </tab>
                <tab name="Slider">
                    <div class="py-4 border-t border-gray-200">
                        <slider-component :initial-data='@json($city->slider ?? [])'></slider-component>
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
            <h2 class="text-md font-semibold mb-2">Zipcode</h2>
            <p class="text-sm text-gray-600 mb-4">Fill the zipcode of the city.</p>
            <div class="mb-4">
                <label for="zip_code" class="block text-sm font-medium text-gray-700">Zipcode</label>
                <input type="text" name="zip_code" id="zip_code"
                    value="{{ old('zip_code', $city->zip_code ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div>
        </div>

        <!-- SEO Fields Section -->
        <div id="seo-fields">
            <seo-fields :meta='@json($meta)' v-model:meta-title="metaTitle"
                v-model:meta-description="metaDescription" v-model:keywords="keywords"></seo-fields>
        </div>

        <div class="mb-4">
            <button type="submit"
                class="bg-black text-white p-2 rounded">{{ isset($city) ? 'Update city' : 'Save city' }}</button>
        </div>
    </form>
</x-app-layout>

<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        selector: 'textarea.tinymce',
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak code',
        toolbar_mode: 'floating',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link code',
        menubar: 'file edit view insert format tools table help',
        height: 500,
        branding: false, // Disable TinyMCE branding
        promotion: false // Disable TinyMCE promotion
    });
</script>

<script>
    new Vue({
        el: '#app',
        data: {
            metaTitle: '{{ old('meta_title', $meta->meta_title ?? '') }}',
            metaDescription: '{{ old('meta_description', $meta->meta_description ?? '') }}',
            keywords: '{{ old('keywords', $meta->keywords ?? '') }}'
        }
    });
</script>
