<x-app-layout>
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                {{ old('hospitals_content') }}
                <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">
                    {{ isset($hospital) ? 'Edit Hospital' : 'Create Hospital' }}
                </h1>
            </div>
        </div>
        <?php 
print_r($errors);
        ?>
        <form action="{{ isset($hospital) ? route('hospital.update', $hospital->id) : route('hospital.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($hospital))
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
                        <p class="text-sm text-gray-600 mb-4">Provide the basic details of the hospital.</p>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $hospital->name ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <input type="text" name="slug" id="slug"
                                value="{{ old('slug', $hospital->slug ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('slug')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                <option value="0"
                                    {{ old('status', $hospital->status ?? 0) == 0 ? 'selected' : '' }}>
                                    Inactive</option>
                                <option value="1"
                                    {{ old('status', $hospital->status ?? 0) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                            </select>
                            @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="city_id" class="block text-sm font-medium text-gray-700">City</label>
                            <select name="city_id" id="city_id"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @php
                                    $cities = [
                                        (object) ['id' => 1, 'name' => 'New York'],
                                        (object) ['id' => 2, 'name' => 'Los Angeles'],
                                        (object) ['id' => 3, 'name' => 'Chicago'],
                                    ];
                                @endphp
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ isset($hospital) && $hospital->city_id == $city->id ? 'selected' : '' }}>
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
                                v-bind:initial-file="'{{ old('image', $hospital->image ?? '') }}'"
                                class="mt-1 block rounded-md border-gray-200 shadow-sm py-1"></image-uploader>
                        </div>
                        <div class="mb-4">
                            <label for="image_alt" class="block text-sm font-medium text-gray-700">Featured Image
                                Alt
                                Text</label>
                            <input type="text" name="image_alt" id="image_alt"
                                value="{{ old('image_alt', $hospital->image_alt ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Content and Highlights -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Specializations</h2>
                <textarea name="content" id="content" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('content', $hospital->content ?? '') }}</textarea>
                @error('content')
                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                @enderror
            </div>
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Facilities</h2>
                <textarea name="facilities" id="facilities" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('facilities', $hospital->facilities ?? '') }}</textarea>
                @error('facilities')
                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                @enderror
            </div>
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Referral System</h2>
                <textarea name="referral_system" id="referral_system"
                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('referral_system', $hospital->referral_system ?? '') }}</textarea>
                @error('referral_system')
                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                @enderror
            </div>

            <!-- Section 3: Additional Information -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Additional Information</h2>
                <p class="text-sm text-gray-600 mb-4">Add additional details like image, name, address, contact,
                    description, and content.</p>
                <div class="mb-4">
                    <div class="flex-grow pl-4 w-full sm:w-auto">
                        <div class="mb-4 flex gap-2">
                            <div class="mb-4 w-full">
                                <label for="introduction"
                                    class="block text-sm font-medium text-gray-700">Introduction</label>
                                <textarea name="introduction" id="introduction" value="{{ old('introduction', $hospital->introduction ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1"></textarea>
                                @error('introduction')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4 w-full">
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" value="{{ old('description', $hospital->description ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                </textarea>
                                @error('description')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4 flex gap-2">
                            <div class="mb-4 w-full">
                                <div class="mb-4 w-full">
                                    <label for="description" class="block text-sm font-medium text-gray-700">How to
                                        Reach</label>
                                    <textarea name="how_to_reach" id="how_to_reach" value="{{ old('how_to_reach', $hospital->how_to_reach ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                </textarea>
                                    @error('how_to_reach')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4 w-full">
                                <div class="mb-4 w-full">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Trauma
                                        Services</label>
                                    <textarea name="trauma_services" id="trauma_services"
                                        value="{{ old('trauma_services', $hospital->trauma_services ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                </textarea>
                                    @error('trauma_services')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="mb-4 w-full">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" name="address"
                                    value="{{ old('address', $hospital->address ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @error('address')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4 w-full">
                                <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                                <input type="text" name="contact"
                                    value="{{ old('contact', $hospital->contact ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @error('contact')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4 w-full">
                                <label for="website_url" class="block text-sm font-medium text-gray-700"> Website
                                    URL</label>
                                <input type="text" name="website_url"
                                    value="{{ old('website_url', $hospital->website_url ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @error('website_url')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4 w-full">
                                <label for="google_map" class="block text-sm font-medium text-gray-700">Google
                                    Maps Link</label>
                                <input type="text" name="google_map"
                                    value="{{ old('google_map', $hospital->google_map ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @error('google_map')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <hospitals
                    :initial-data="{{ json_encode(old('hospitals_content', $hospital->hospitals_content ?? [])) }}">
                </hospitals> -->
            </div>

            <div class="mb-4">
                <button type="submit"
                    class="bg-black text-white p-2 rounded">{{ isset($hospital) ? 'Update Hospital' : 'Save Hospital' }}</button>
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
