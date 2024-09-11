<template>
    <div class="relative inline-block text-left" ref="dropdown">
        <button @click="toggleMenu" type="button" class="inline-flex justify-center w-full px-2 py-2 bg-white text-sm font-medium text-gray-700" id="menu-button" aria-expanded="true" aria-haspopup="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis h-4 w-4"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
        </button>
        <div v-show="open" class="origin-top-right z-20 absolute right-0 mt-0 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1" role="none">
                <span class="block px-4 py-2 text-sm font-semibold text-gray-700">Actions</span>
                <a :href="editUrl" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-0">Edit</a>
                <form :action="deleteUrl" method="POST" role="menuitem" tabindex="-1" id="menu-item-1">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit" class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Delete</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    editUrl: String,
    deleteUrl: String
});

const open = ref(false);
const dropdown = ref(null);

const toggleMenu = () => {
    open.value = !open.value;
    if (open.value) {
        document.dispatchEvent(new CustomEvent('dropdown-opened', { detail: { id: props.editUrl } }));
    }
};

const closeMenu = () => {
    open.value = false;
};

const handleClickOutside = (event) => {
    if (dropdown.value && !dropdown.value.contains(event.target)) {
        closeMenu();
    }
};

const handleDropdownOpened = (event) => {
    if (event.detail.id !== props.editUrl) {
        closeMenu();
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('dropdown-opened', handleDropdownOpened);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('dropdown-opened', handleDropdownOpened);
});

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>
