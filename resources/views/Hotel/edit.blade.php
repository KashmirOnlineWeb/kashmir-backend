<x-app-layout>
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">
                    {{ isset($hotel) ? 'Edit Hotel' : 'Create Hotel' }}
                </h1>
            </div>
        </div>
        <form action="{{ isset($hotel) ? route('hotel.update', $hotel->id) : route('hotel.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @if (isset($hotel))
                @method('PUT')
            @endif
            <!-- Section 1: Basic Information -->
            <div class="flex flex-wrap -mx-2 mb-4 border-b border-gray-200">
                <div class="w-full md:w-1/2 px-2 mb-4">
                    <div class="p-4">
                        <h2 class="text-md font-semibold mb-2">Basic Information</h2>
                        <p class="text-sm text-gray-600 mb-4">Provide the basic details of the hotel.</p>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $hotel->name ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <input type="text" name="slug" id="slug"
                                value="{{ old('slug', $hotel->slug ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('slug')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                            <input type="text" name="contact" id="contact"
                                value="{{ old('contact', $hotel->contact ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('contact')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="total_washrooms" class="block text-sm font-medium text-gray-700">Total
                                Washrooms</label>
                            <input type="number" name="total_washrooms" id="total_washrooms"
                                value="{{ old('total_washrooms', $hotel->total_washrooms ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('total_washrooms')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="total_lobbys" class="block text-sm font-medium text-gray-700">Total
                                Lobbys</label>
                            <input type="number" name="total_lobbys" id="total_lobbys"
                                value="{{ old('total_lobbys', $hotel->total_lobbys ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('total_lobbys')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="total_rooms" class="block text-sm font-medium text-gray-700">Total
                                Rooms</label>
                            <input type="number" name="total_rooms" id="total_rooms"
                                value="{{ old('total_rooms', $hotel->total_rooms ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('total_rooms')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="star" class="block text-sm font-medium text-gray-700">Star</label>
                            <select name="star" id="star"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                <option value="1" {{ old('star', $hotel->star ?? '') == 1 ? 'selected' : '' }}>
                                    1 Star</option>
                                <option value="2" {{ old('star', $hotel->star ?? '') == 2 ? 'selected' : '' }}>
                                    2 Star
                                </option>
                                <option value="3" {{ old('star', $hotel->star ?? '') == 3 ? 'selected' : '' }}>
                                    3 Star
                                </option>
                                <option value="4" {{ old('star', $hotel->star ?? '') == 4 ? 'selected' : '' }}>
                                    4 Star
                                </option>
                                <option value="5" {{ old('star', $hotel->star ?? '') == 5 ? 'selected' : '' }}>
                                    5 Star
                                </option>
                            </select>
                            @error('star')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" name="location" id="location"
                                value="{{ old('location', $hotel->location ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('location')
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
                                class="mt-1 block rounded-md border-gray-200 shadow-sm py-1"></image-uploader>
                        </div>
                        <div class="mb-4">
                            <label for="image_alt" class="block text-sm font-medium text-gray-700">Featured Image
                                Alt
                                Text</label>
                            <input type="text" name="image_alt" id="image_alt"
                                value="{{ old('image_alt', $hotel->image_alt ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                <option value="0"
                                    {{ old('status', $hotel->status ?? 1) == 0 ? 'selected' : '' }}>
                                    Inactive</option>
                                <option value="1"
                                    {{ old('status', $hotel->status ?? 1) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="p-4">
                        <h2 class="text-md font-semibold mb-2">Amenities</h2>
                        <p class="text-sm text-gray-600 mb-4">Select the amenities and features available at the
                            hotel.
                        </p>
                        <div class="mb-4">
                            <label for="amenities" class="block text-sm font-medium text-gray-700">Amenities</label>
                            <select name="amenities[]" id="amenities" multiple
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 select2">
                                <option value="wifi"
                                    {{ in_array('wifi', old('amenities', $hotel->amenities ?? [])) ? 'selected' : '' }}>
                                    WiFi</option>
                                <option value="pool"
                                    {{ in_array('pool', old('amenities', $hotel->amenities ?? [])) ? 'selected' : '' }}>
                                    Pool</option>
                                <option value="parking"
                                    {{ in_array('parking', old('amenities', $hotel->amenities ?? [])) ? 'selected' : '' }}>
                                    Parking</option>
                                <option value="gym"
                                    {{ in_array('gym', old('amenities', $hotel->amenities ?? [])) ? 'selected' : '' }}>
                                    Gym</option>
                                <option value="spa"
                                    {{ in_array('spa', old('amenities', $hotel->amenities ?? [])) ? 'selected' : '' }}>
                                    Spa</option>
                                <option value="restaurant"
                                    {{ in_array('restaurant', old('amenities', $hotel->amenities ?? [])) ? 'selected' : '' }}>
                                    Restaurant</option>
                                <option value="bar"
                                    {{ in_array('bar', old('amenities', $hotel->amenities ?? [])) ? 'selected' : '' }}>
                                    Bar</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="balcony" class="block text-sm font-medium text-gray-700">Balcony</label>
                            <select name="balcony" id="balcony"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                <option value="0"
                                    {{ old('balcony', $hotel->balcony ?? 0) == 0 ? 'selected' : '' }}>Not
                                    Available</option>
                                <option value="1"
                                    {{ old('balcony', $hotel->balcony ?? 0) == 1 ? 'selected' : '' }}>
                                    Available</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="breakfast" class="block text-sm font-medium text-gray-700">Breakfast</label>
                            <select name="breakfast" id="breakfast"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                <option value="0"
                                    {{ old('breakfast', $hotel->breakfast ?? 0) == 0 ? 'selected' : '' }}>
                                    Not Available</option>
                                <option value="1"
                                    {{ old('breakfast', $hotel->breakfast ?? 0) == 1 ? 'selected' : '' }}>
                                    Available</option>
                                <option value="2"
                                    {{ old('breakfast', $hotel->breakfast ?? 0) == 2 ? 'selected' : '' }}>
                                    Available a extra charges</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Section 2: Tabs for Content and Highlights -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Content & Highlights</h2>
                <p class="text-sm text-gray-600 mb-4">Add detailed descriptions and highlights of the hotel.</p>
                <tabs>
                    <tab name="Content">
                        <div class="py-4 border-t border-gray-200">
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                                <textarea name="content" id="content" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('content', $hotel->content ?? '') }}</textarea>
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
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('highlights_content', $hotel->highlights_content ?? '') }}</textarea>
                            </div>
                        </div>
                    </tab>
                </tabs>
                @error('content')
                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                @enderror
                @error('highlights_content')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Section 3: Amenities and Pricing -->
            <div class="flex flex-wrap -mx-2 mb-4 border-b border-gray-200">
                <div class="w-full md:w-1/2 px-2 mb-4">
                    <div class="p-4">
                        <h2 class="text-md font-semibold mb-2">Location</h2>
                        <p class="text-sm text-gray-600 mb-4">Select the city where the hotel is located.</p>
                        <div class="mb-4">
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

                <div class="w-full md:w-1/2 px-2 mb-4">
                    <div class="p-4">
                        <h2 class="text-md font-semibold mb-2">Pricing</h2>
                        <p class="text-sm text-gray-600 mb-4">Provide the pricing details and status of the hotel.
                        </p>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="text" name="price" id="price"
                                value="{{ old('price', $hotel->price ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('price')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="tax" class="block text-sm font-medium text-gray-700">Tax</label>
                            <input type="text" name="tax" id="tax"
                                value="{{ old('tax', $hotel->tax ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('tax')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Fields Section -->
            <div id="seo-fields">
                <seo-fields :meta-title="'{{ old('meta_title', $meta->meta_title ?? '') }}'"
                    :meta-description="'{{ old('meta_description', $meta->meta_description ?? '') }}'"
                    :keywords="'{{ old('keywords', $meta->keywords ?? '') }}'">
                </seo-fields>
            </div>

            <div class="mb-4">
                <button type="submit"
                    class="bg-black text-white p-2 rounded">{{ isset($hotel) ? 'Update Hotel' : 'Save Hotel' }}</button>
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
