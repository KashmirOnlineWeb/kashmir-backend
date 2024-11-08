<x-app-layout>
    <div id="app">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">
                    {{ isset($page) ? 'Edit Page' : 'Create Page' }}
                </h1>
            </div>
        </div>
        <form action="{{ isset($page) ? route('page.update', $page->id) : route('page.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @if (isset($page))
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
            <div class="flex flex-wrap -mx-2 mb-4 border-gray-200">
                <div class="w-full gap-4 px-2 mb-4">
                    <div class="p-4">
                        <h2 class="text-md font-semibold mb-2">Basic Information</h2>
                        <p class="text-sm text-gray-600 mb-4">Provide the basic details of the page.</p>
                        <div class="flex w-full mb-4 border-b gap-4 border-gray-200">
                            <div class="mb-4 w-full">
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $page->name ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4 w-full">
                                <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                                <input type="text" name="slug" id="slug"
                                    value="{{ old('slug', $page->slug ?? '') }}"
                                    class="read-only:opacity-60 read-only:cursor-not-allowed mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1"
                                    {{ isset($page) && ($page->slug == 'home' || $page->slug == 'faqs' || $page->slug == 'testimonials') ? 'readonly' : '' }}>
                                @error('slug')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($page) && $page->slug == 'home')
                <div class="mb-4 p-4 border-b border-gray-200">
                    <h2 class="text-md font-semibold mb-2">Home Slider</h2>
                    <home-slider 
                        :initial-data="{{ json_encode(old('content3', $page->content3 ?? [])) }}"
                        name-prefix="content3"
                    />
                    @error('content3')
                        <span class="text-red-500 text-sm">{{ $message }}</span></br>
                    @enderror
                </div>
            @endif

            @if(isset($page) && $page->slug == 'faqs')
                <div class="mb-4 p-4 border-b border-gray-200">
                    <h2 class="text-md font-semibold mb-2">FAQs</h2>
                    <Faqs :initial-data="{{ json_encode(old('content1', $page->content1 ?? [])) }}"
                        name-prefix="content1" />

                    @error('content1')
                        <span class="text-red-500 text-sm">{{ $message }}</span></br>
                    @enderror
                </div>
            @elseif(isset($page) && $page->slug == 'testimonials')
                <div class="mb-4 p-4 border-b border-gray-200">
                    <h2 class="text-md font-semibold mb-2">Testimonials</h2>
                    <Testimonials :initial-data="{{ json_encode(old('content1', $page->content1 ?? [])) }}"
                        name-prefix="content1" />

                    @error('content1')
                        <span class="text-red-500 text-sm">{{ $message }}</span></br>
                    @enderror
                </div>
            @else
                <!-- Section 2: Tabs for Content and Highlights -->
                <div class="mb-4 p-4 border-b border-gray-200">
                    <h2 class="text-md font-semibold mb-2">Content</h2>
                    <p class="text-sm text-gray-600 mb-4">Add content or just images for parent page.</p>
                    <tabs>
                        <tab name="Content">
                            <div class="py-4 border-t border-gray-200">
                                <content-repeater 
                                    :initial-data="{{ json_encode(old('content1', $page->content1 ?? [])) }}"
                                    @update:contents="updateContents"
                                    name-prefix="content1"
                                />
                                @error('content1')
                                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                                @enderror
                            </div>
                        </tab>
                        <tab name="Parent Page Images">
                            <div class="py-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-4">If this is a parent page, then use this tab to add images.</p>
                                <image-repeater 
                                    :initial-data="{{ json_encode(old('content2', $page->content3 ?? [])) }}"
                                    name-prefix="content3"
                                />
                                @error('content3')
                                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                                @enderror
                            </div>
                        </tab>
                    </tabs>
                </div>
            @endif

                <!-- Section 2 -->
            @if(isset($page) && $page->slug == 'home')
                <div class="mb-4 p-4 border-b border-gray-200">
                    <h2 class="text-md font-semibold mb-2">Why Kashmir online?</h2>
                    <content-repeater 
                        :initial-data="{{ json_encode(old('content2', $page->content2 ?? [])) }}"
                        @update:contents="updateContents"
                        name-prefix="content2"
                    />
                    @error('content2')
                        <span class="text-red-500 text-sm">{{ $message }}</span></br>
                    @enderror
                </div>
            @endif

            <!-- SEO Fields Section -->
            <div id="seo-fields">
                <seo-fields :meta-title="{{ json_encode(old('meta_title', $meta->meta_title ?? '')) }}"
                    :meta-description="{{ json_encode(old('meta_description', $meta->meta_description ?? '')) }}"
                    :keywords="{{ json_encode(old('keywords', $meta->keywords ?? '')) }}">
                </seo-fields>
            </div>

            <div class="mb-4">
                <button type="submit" :disabled="isUploading"
                    class="bg-black text-white p-2 rounded disabled:opacity-50">{{ isset($page) ? 'Update Page' : 'Save Page' }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
