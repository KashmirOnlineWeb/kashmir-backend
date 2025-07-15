<x-app-layout>
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold md:text-xl leading-none tracking-tight">
                    {{ isset($user) ? 'Edit User' : 'Create User' }}
                </h1>
            </div>
        </div>
        <form action="{{ isset($user) ? route('user.update', $user->id) : route('user.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @if (isset($user))
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
                        <p class="text-sm text-gray-600 mb-4">Provide the basic details of the user.</p>
                        
                        <div class="mb-4">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name"
                                value="{{ old('first_name', $user->first_name ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('first_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name"
                                value="{{ old('last_name', $user->last_name ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('last_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="text" name="email" id="email"
                                value="{{ old('email', $user->email ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile</label>
                            <input type="text" name="mobile" id="mobile"
                                value="{{ old('mobile', $user->mobile ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('mobile')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="dob" class="block text-sm font-medium text-gray-700">DOB</label>
                            <input type="text" name="dob" id="dob"
                                value="{{ old('dob', $user->dob ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                            @error('dob')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Select role</label>
                            <select name="role" id="role"
                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{(isset($user) && $user->hasRole($role->name)) == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(!isset($user))
                            <div class="mt-4">
                                <x-input-label for="password" :value="__('Password')" />

                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                                    autocomplete="new-password" />

                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="mt-4">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        @endif    

                    </div>
                </div>

                <div class="w-full md:w-1/2 px-2 mb-4">
                    <div class="p-4 border-gray-200 rounded-md">
                        <h2 class="text-md font-semibold mb-2">Profile Image</h2>
                        <p class="text-sm text-gray-600 mb-4">To show in cards.</p>
                        <div class="mb-4">
                            <image-uploader name="image" id="image"
                                v-bind:initial-file="'{{ old('image', $user->profile_image ?? '') }}'"
                                class="mt-1 block rounded-md border-gray-200 shadow-sm py-1"></image-uploader>
                        </div>
                    </div>
                </div>
            </div>

            
           

            

            <div class="mb-4">
                <button type="submit"
                    class="bg-black text-white p-2 rounded">{{ isset($user) ? 'Update User' : 'Register User' }}</button>
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
