<x-app-layout>
    <div id="app">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">
                    {{ isset($sightseeing) ? 'Edit Sightseeing' : 'Create Sightseeing' }}
                </h1>
            </div>
        </div>
        <form
            action="{{ isset($sightseeing) ? route('sightseeing.update', $sightseeing->id) : route('sightseeing.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($sightseeing))
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
                        <p class="text-sm text-gray-600 mb-4">Provide the basic details of the sightseeing.</p>
                        <div class="flex flex-col w-full mb-4 border-b gap-4 border-gray-200">
                            <div class="mb-4 w-full">
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $sightseeing->name ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4 w-full">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" name="title" id="title"
                                    value="{{ old('title', $sightseeing->title ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @error('title')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4 w-full">
                                <label for="city_id" class="block text-sm font-medium text-gray-700">City</label>
                                <select name="city_id" id="city_id"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ (old('city_id') == $city->id || (isset($sightseeing) && $sightseeing->city_id == $city->id)) ? 'selected' : '' }}>
                                            {{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 px-2 mb-4">
                    <!-- <div class="p-4 border-b border-gray-200 rounded-md">
                        <h2 class="text-md font-semibold mb-2">Featured Image</h2>
                        <p class="text-sm text-gray-600 mb-4">To show in cards.</p>
                        <div class="mb-4">
                            <image-uploader name="image" id="image"
                                v-bind:initial-file="'{{ old('image', $sightseeing->image ?? '') }}'"
                                class="mt-1 block rounded-md border-gray-200 shadow-sm py-1"></image-uploader>
                        </div>
                        <div class="mb-4">
                            <label for="image_alt" class="block text-sm font-medium text-gray-700">Featured Image
                                Alt
                                Text</label>
                            <input type="text" name="image_alt" id="image_alt"
                                value="{{ old('image_alt', $sightseeing->image_alt ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        </div>
                    </div> -->
                </div>
            </div>

            <!-- Section 2: Tabs for Content and Highlights -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Content</h2>
                <content-repeater :initial-data="{{ json_encode(old('repeater_content', $sightseeing->repeater_content ?? [])) }}"
                    name-prefix="repeater_content" />

                @error('repeater_content')
                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                @enderror
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
                    class="bg-black text-white p-2 rounded disabled:opacity-50">{{ isset($sightseeing) ? 'Update Sightseeing' : 'Save Sightseeing' }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
