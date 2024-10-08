<x-app-layout>
    <div id="app">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">
                    {{ isset($destination) ? 'Edit Package' : 'Create Package' }}
                </h1>
            </div>
        </div>
        <form
            action="{{ isset($package) ? route('package.update', $package->id) : route('package.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($package))
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
                        <p class="text-sm text-gray-600 mb-4">Provide the basic details of the package.</p>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $package->name ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <input type="text" name="slug" id="slug"
                                value="{{ old('slug', $package->slug ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('slug')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="text" name="price" id="price"
                                value="{{ old('price', $package->price ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('price')
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
                                v-bind:initial-file="'{{ old('image', $package->image ?? '') }}'"
                                class="mt-1 block rounded-md border-gray-200 shadow-sm py-1"></image-uploader>
                        </div>
                        <div class="mb-4">
                            <label for="image_alt" class="block text-sm font-medium text-gray-700">Featured Image Alt
                                Text</label>
                            <input type="text" name="image_alt" id="image_alt"
                                value="{{ old('image_alt', $package->image_alt ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Tabs for Content and Highlights -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Content & Highlights</h2>
                <p class="text-sm text-gray-600 mb-4">Add detailed descriptions and highlights of the package.</p>
                <tabs>
                    <tab name="Content">
                        <div class="py-4 border-t border-gray-200">
                            <div class="mb-4">
                                <label for="content"
                                    class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="content" id="content"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('content', $package->content ?? '') }}</textarea>
                            </div>
                        </div>
                    </tab>
                    <tab name="Itinerary">
                        <div class="py-4 border-t border-gray-200">
                            <div class="mb-4">
                                <label for="itenery_content"
                                    class="block text-sm font-medium text-gray-700">Itinerary</label>
                                    <Itinerary :initial-data="{{ json_encode(old('itenery_content', $package->itenery_content ?? [])) }}"
                                        name-prefix="itenery_content" />

                                    @error('itenery_content')
                                        <span class="text-red-500 text-sm">{{ $message }}</span></br>
                                    @enderror
                            </div>
                        </div>
                    </tab>
                    <tab name="Addons">
                        <div class="py-4 border-t border-gray-200">
                            <div class="mb-4">
                                <label for="addons_editor"
                                    class="block text-sm font-medium text-gray-700">Addons
                                    Content</label>
                                <textarea name="addons_editor" id="addons_editor"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('addons_editor', $package->addons_editor ?? '') }}</textarea>
                            </div>
                        </div>
                    </tab>
                    <tab name="Exclusions">
                        <div class="py-4 border-t border-gray-200">
                            <div class="mb-4">
                                <label for="exclusions_editor" class="block text-sm font-medium text-gray-700">Exclusions</label>
                                <textarea name="exclusions_editor" id="exclusions_editor"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('exclusions_editor', $package->exclusions_editor ?? '') }}</textarea>
                            </div>
                        </div>
                    </tab>
                    <tab name="Slider">
                        <div class="py-4 border-t border-gray-200">
                        <slider-component 
                                :initial-data='@json(old("image_gallery", $package->image_gallery ?? []))'
                                name-prefix="image_gallery"
                            ></slider-component>
                        </div>
                    </tab>
                </tabs>
                @error('content')
                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                @enderror
                @error('addons_editor')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
                @error('exclusions_editor')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">FAQs</h2>
                <div class="mb-4 p-4 border-b border-gray-200">
                    <label for="exclusions_editor" class="block text-sm font-medium text-gray-700">Package FAQs</label>
                    <Faqs :initial-data="{{ json_encode(old('faqs_content', $package->faqs_content ?? [])) }}"
                        name-prefix="faqs_content" />

                    @error('faqs_content')
                        <span class="text-red-500 text-sm">{{ $message }}</span></br>
                    @enderror
                </div>
            </div>
            <!-- Section 4 -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Location and Type</h2>
                <p class="text-sm text-gray-600 mb-4">Select the city where the package is located and it's type.
                </p>
                <div class="grid grid-cols-2 gap-2">
                    <div class="mb-4">
                        <label for="illusions_content" class="block text-sm font-medium text-gray-700">Inclusions</label>
                        <select name="illusions_content[]" id="illusions_content" multiple
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 select2">
                            <option value="breakfast"
                                {{ in_array('breakfast', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Breakfast</option>
                            <option value="wifi"
                                {{ in_array('wifi', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                WiFi</option>
                            <option value="ac"
                                {{ in_array('ac', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Ac</option>
                            <option value="kitchen"
                                {{ in_array('kitchen', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Kitchen</option>
                            <option value="heater"
                                {{ in_array('heater', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Heater</option>
                            <option value="pickup_drop"
                                {{ in_array('pickup_drop', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Pickup/drop</option>
                            <option value="price"
                                {{ in_array('price', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Price</option>
                            <option value="transportation"
                                {{ in_array('transportation', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Transportation</option>
                            <option value="houseboat"
                                {{ in_array('houseboat', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Houseboat</option>
                            <option value="support_service"
                                {{ in_array('support_service', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Support Service</option>
                            <option value="reels_shooting"
                                {{ in_array('reels_shooting', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Reels/Shooting</option>
                            <option value="sightseeing"
                                {{ in_array('sightseeing', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Sightseeing</option>
                            <option value="pool"
                                {{ in_array('pool', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Pool</option>
                            <option value="parking"
                                {{ in_array('parking', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Parking</option>
                            <option value="gym"
                                {{ in_array('gym', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Gym</option>
                            <option value="spa"
                                {{ in_array('spa', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Spa</option>
                            <option value="restaurant"
                                {{ in_array('restaurant', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Restaurant</option>
                            <option value="bar"
                                {{ in_array('bar', old('illusions_content', $package->illusions_content ?? [])) ? 'selected' : '' }}>
                                Bar</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="city_id" class="block text-sm font-medium text-gray-700">City</label>
                        <select name="city_id" id="city_id"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}"
                                    {{ isset($package) && $package->city_id == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="destination" class="block text-sm font-medium text-gray-700">Destination</label>
                        <select name="destination_id" id="destination_id"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @foreach ($destinations as $destination)
                                <option value="{{ $destination->id }}"
                                    {{ isset($package) && $package->destination_id == $destination->id ? 'selected' : '' }}>
                                    {{ $destination->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="category_id"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ isset($package) && $package->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="season" class="block text-sm font-medium text-gray-700">Season</label>
                        <select name="season" id="season"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            <option value="summer" {{ isset($package) && $package->season == 'summer' ? 'selected' : '' }}>Summer</option>
                            <option value="winter" {{ isset($package) && $package->season == 'winter' ? 'selected' : '' }}>Winter</option>
                            <option value="rainy" {{ isset($package) && $package->season == 'rainy' ? 'selected' : '' }}>Rainy</option>
                            <option value="autumn" {{ isset($package) && $package->season == 'autumn' ? 'selected' : '' }}>Autumn</option>
                            <option value="all" {{ isset($package) && $package->season == 'all' ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="budget_type" class="block text-sm font-medium text-gray-700">Budget Type</label>
                        <select name="budget_type" id="budget_type"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            <option value="low" {{ isset($package) && $package->budget_type == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="luxery" {{ isset($package) && $package->budget_type == 'luxery' ? 'selected' : '' }}>Luxery</option>
                            <option value="premium" {{ isset($package) && $package->budget_type == 'premium' ? 'selected' : '' }}>Premium</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="accommodations" class="block text-sm font-medium text-gray-700">Accommodations</label>
                        <input type="text" name="accommodations" id="accommodations"
                            value="{{ old('accommodations', $package->accommodations ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        @error('accommodations')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="available_slots" class="block text-sm font-medium text-gray-700">Available Slots</label>
                        <input type="text" name="available_slots" id="available_slots"
                            value="{{ old('available_slots', $package->available_slots ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        @error('available_slots')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="days" class="block text-sm font-medium text-gray-700">Days</label>
                        <input type="text" name="days" id="days"
                            value="{{ old('days', $package->days ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        @error('days')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="nights" class="block text-sm font-medium text-gray-700">Nights</label>
                        <input type="text" name="nights" id="nights"
                            value="{{ old('nights', $package->nights ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        @error('nights')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="max_capacity" class="block text-sm font-medium text-gray-700">Max Capacity</label>
                        <input type="text" name="max_capacity" id="max_capacity"
                            value="{{ old('max_capacity', $package->max_capacity ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        @error('max_capacity')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="hotel_star" class="block text-sm font-medium text-gray-700">Hotel Star</label>
                        <select name="hotel_star" id="hotel_star"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            <option value="1" {{ old('hotel_star', $package->hotel_star ?? '') == 1 ? 'selected' : '' }}>
                                1 Star</option>
                            <option value="2" {{ old('hotel_star', $package->hotel_star ?? '') == 2 ? 'selected' : '' }}>
                                2 Star
                            </option>
                            <option value="3" {{ old('hotel_star', $package->hotel_star ?? '') == 3 ? 'selected' : '' }}>
                                3 Star
                            </option>
                            <option value="4" {{ old('hotel_star', $package->hotel_star ?? '') == 4 ? 'selected' : '' }}>
                                4 Star
                            </option>
                            <option value="5" {{ old('hotel_star', $package->hotel_star ?? '') == 5 ? 'selected' : '' }}>
                                5 Star
                            </option>
                        </select>
                        @error('star')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="start_date"
                            value="{{ old('start_date', isset($package) && $package->start_date ? $package->start_date->format('Y-m-d') : '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        @error('start_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" id="end_date"
                            value="{{ old('end_date', isset($package) && $package->end_date ? $package->end_date->format('Y-m-d') : '') }}"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        @error('end_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="is_specials" class="block text-md font-bold mb-1 text-gray-900">Special Package</label>
                        <label for="is_special" class="block text-sm font-medium text-gray-900">
                        <input type="checkbox" name="is_special" id="is_special" 
                            value="1" {{ old('is_special', $package->is_special ?? false) ? 'checked' : '' }}
                            class="mt-1"> Treat as Special Package?</input></label>
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
                <button type="submit" :disabled="isUploading"
                    class="bg-black text-white p-2 rounded disabled:opacity-50">{{ isset($destination) ? 'Update Destination' : 'Save Destination' }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#illusions_content').select2({
            tags: true,
            tokenSeparators: [',', ' ']
        });
    });
</script>
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        selector: 'textarea#content, textarea#addons_editor, textarea#exclusions_editor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        branding: false,
        promotion: false
    });
</script>