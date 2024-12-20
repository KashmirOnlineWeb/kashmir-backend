<x-app-layout>
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">
                    {{ isset($restaurant) ? 'Edit Restaurant' : 'Create Restaurant' }}
                </h1>
            </div>
        </div>
        <form action="{{ isset($restaurant) ? route('restaurant.update', $restaurant->id) : route('restaurant.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($restaurant))
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

            <!-- Section 1: Basic Information -->
            <div class="flex flex-wrap -mx-2 mb-4 border-b border-gray-200">
                <div class="w-full md:w-1/2 px-2 mb-4">
                    <div class="p-4">
                        <h2 class="text-md font-semibold mb-2">Basic Information</h2>
                        <p class="text-sm text-gray-600 mb-4">Provide the basic details of the restaurant.</p>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $restaurant->name ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <input type="text" name="slug" id="slug"
                                value="{{ old('slug', $restaurant->slug ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('slug')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="city_id" class="block text-sm font-medium text-gray-700">City</label>
                            <select name="city_id" id="city_id"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ isset($restaurant) && $restaurant->city_id == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/2 px-2 mb-4">
                    <div class="p-4 border-b border-gray-200 rounded-md">
                        <h2 class="text-md font-semibold mb-2">Featured Image</h2>
                        <p class="text-sm text-gray-600 mb-4">To show in cards.</p>
                        <div class="mb-4">
                            <image-uploader name="image" id="image"
                                v-bind:initial-file="'{{ old('image', $restaurant->image ?? '') }}'"
                                class="mt-1 block rounded-md border-gray-200 shadow-sm py-1"></image-uploader>
                        </div>
                        <div class="mb-4">
                            <label for="image_alt" class="block text-sm font-medium text-gray-700">Featured Image
                                Alt
                                Text</label>
                            <input type="text" name="image_alt" id="image_alt"
                                value="{{ old('image_alt', $restaurant->image_alt ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Additional Information -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <!-- <h2 class="text-md font-semibold mb-2">Additional Information</h2>
                <p class="text-sm text-gray-600 mb-4">Add additional details like image, name, address, contact,
                    description, and content.</p> -->
                <div class="mb-4 flex gap-2">
                    <div class="mb-4 w-full">
                        <label for="google_map" class="block text-sm font-medium text-gray-700">Google Map</label>
                        <input type="text" name="google_map" id="google_map"
                            value="{{ old('google_map', $restaurant->google_map ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1" />
                        @error('google_map')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4 w-full">
                        <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                        <input type="text" name="contact" id="contact"
                            value="{{ old('contact', $restaurant->contact ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1" />
                        @error('contact')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mb-4 flex gap-2">
                    <div class="mb-4 w-full">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" name="address" id="address"
                            value="{{ old('address', $restaurant->address ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1" />
                        @error('address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4 w-full">
                        <label for="type" class="block text-sm font-medium text-gray-700">Restaurant Type</label>
                        <select name="restaurant_type" id="type"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            <option value="" disabled>Select Restaurant Type</option>
                            <option value="Indian">Indian</option>
                            <option value="Pure Vegetarian Restaurant">Pure Vegetarian Restaurant</option>
                            <option value="Chinese">Chinese</option>
                            <option value="Multicuisine">Multicuisine</option>
                            <option value="Cafes">Cafes</option>
                            <option value="Continental">Continental</option>
                            <option value="Fast Food">Fast Food</option>
                            <option value="Others">Others</option>
                        </select>
                        @error('type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mb-4 flex gap-2">
                    <div class="mb-4 w-full">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" value="{{ old('description', $restaurant->description ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                </textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- <restaurants
                    :initial-data="{{ json_encode(old('restaurant_content', $restaurant->restaurant_content ?? [])) }}">
                </restaurants> -->
            </div>

            <!-- SEO Fields Section -->
            <!-- <div id="seo-fields">
                <seo-fields :meta-title="'{{ old('meta_title', $meta->meta_title ?? '') }}'"
                    :meta-description="'{{ old('meta_description', $meta->meta_description ?? '') }}'"
                    :keywords="'{{ old('keywords', $meta->keywords ?? '') }}'">
                </seo-fields>
            </div> -->
            <div class="mb-4">
                <button type="submit"
                    class="bg-black text-white p-2 rounded">{{ isset($restaurant) ? 'Update Restaurant' : 'Save Restaurant' }}</button>
            </div>
        </form>
    </div>
</x-app-layout>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#amenities').select2({
            tags: true,
            tokenSeparators: [',', ' ']
        });
    });
</script>

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
