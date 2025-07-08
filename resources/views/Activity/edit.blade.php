<x-app-layout>
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">
                    {{ isset($activity) ? 'Edit Activity' : 'Create Activity' }}
                </h1>
            </div>
        </div>
        <form action="{{ isset($activity) ? route('activity.update', $activity->id) : route('activity.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @if (isset($activity))
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
                        <p class="text-sm text-gray-600 mb-4">Provide the basic details of the activity.</p>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $activity->name ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <input type="text" name="slug" id="slug"
                                value="{{ old('slug', $activity->slug ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('slug')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/2 px-2 mb-4">
                    <div class="p-4 border-gray-200 rounded-md">
                        <h2 class="text-md font-semibold mb-2">Featured Image</h2>
                        <p class="text-sm text-gray-600 mb-4">To show in cards.</p>
                        <div class="mb-4">
                            <image-uploader name="image" id="image"
                                v-bind:initial-file="'{{ old('image', $activity->image ?? '') }}'"
                                class="mt-1 block rounded-md border-gray-200 shadow-sm py-1"></image-uploader>
                        </div>
                        <div class="mb-4">
                            <label for="image_alt" class="block text-sm font-medium text-gray-700">Featured Image
                                Alt
                                Text</label>
                            <input type="text" name="image_alt" id="image_alt"
                                value="{{ old('image_alt', $activity->image_alt ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                        </div>
                        <!-- <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                <option value="0"
                                    {{ old('status', $activity->status ?? 1) == 0 ? 'selected' : '' }}>
                                    Inactive</option>
                                <option value="1"
                                    {{ old('status', $activity->status ?? 1) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                            </select>
                        </div> -->
                    </div>
                </div>
            </div>

            <!-- Section 2: Tabs for Content and Highlights -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Description</h2>
                <p class="text-sm text-gray-600 mb-4"></p>
                <div class="py-4 border-t border-gray-200">
                            <div class="mb-4">
                                <!-- <label for="description" class="block text-sm font-medium text-gray-700">Description</label> -->
                                <textarea name="description" id="description" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 tinymce">{{ old('description', $activity->description ?? '') }}</textarea>
                            </div>
                        </div>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                @enderror
            </div>

           <!-- Section 3 -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Category, seasons and destinations</h2>
                <p class="text-sm text-gray-600 mb-4">Define more details about the activity.
                </p>
                <div class="grid grid-cols-2 gap-2">
                    <div class="mb-4">
                        <label for="destination" class="block text-sm font-medium text-gray-700">Destination</label>
                        <select name="destination_id" id="destination_id"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @foreach ($destinations as $destination)
                                <option value="{{ $destination->id }}"
                                    {{ isset($activity) && $activity->destination_id == $destination->id ? 'selected' : '' }}>
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
                                    {{ isset($activity) && $activity->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="season" class="block text-sm font-medium text-gray-700">Season</label>
                        <select name="season" id="season"
                            class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            <option value="summer" {{ isset($activity) && $activity->season == 'summer' ? 'selected' : '' }}>Summer</option>
                            <option value="winter" {{ isset($activity) && $activity->season == 'winter' ? 'selected' : '' }}>Winter</option>
                            <option value="rainy" {{ isset($activity) && $activity->season == 'rainy' ? 'selected' : '' }}>Rainy</option>
                            <option value="autumn" {{ isset($activity) && $activity->season == 'autumn' ? 'selected' : '' }}>Autumn</option>
                            <option value="all" {{ isset($activity) && $activity->season == 'all' ? 'selected' : '' }}>All</option>
                        </select>
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
                    class="bg-black text-white p-2 rounded">{{ isset($activity) ? 'Update Activity' : 'Save Activity' }}</button>
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
