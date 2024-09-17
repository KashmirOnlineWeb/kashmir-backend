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
                                    class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @error('slug')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Tabs for Content and Highlights -->
            <div class="mb-4 p-4 border-b border-gray-200">
                <h2 class="text-md font-semibold mb-2">Content</h2>
                <content-repeater 
                    :initialData="{{ json_encode(old('repeater_content', $shoppingplace->repeater_content ?? [])) }}"
                    @update:contents="updateContents"
                    name-prefix="repeater_content"
                />
                @error('contents')
                    <span class="text-red-500 text-sm">{{ $message }}</span></br>
                @enderror
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
                    class="bg-black text-white p-2 rounded disabled:opacity-50">{{ isset($page) ? 'Update Page' : 'Save Page' }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
