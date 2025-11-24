<template>
    <div class="relative inline-block text-left" ref="dropdown">
        <button @click="toggleMenu" type="button" class="inline-flex justify-center w-full px-2 py-2 bg-white text-sm font-medium text-gray-700" id="menu-button" aria-expanded="true" aria-haspopup="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis h-4 w-4"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
        </button>
        <div v-show="open" class="origin-top-right z-20 absolute right-0 mt-0 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1" role="none">
                <span class="block px-4 py-2 text-sm font-semibold text-gray-700">Actions</span>
                <a :href="editUrl" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-0">Edit</a>
                <template v-if="deleteUrl">
                    <button @click.prevent="showDeleteConfirmation" type="button" class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-1">Delete</button>
                </template>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div v-if="showConfirm" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-4">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="cancelDelete"></div>
                
                <!-- Modal panel -->
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
                    <div class="px-6 pt-6 pb-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 break-words" id="modal-title">
                                    Delete Item
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 leading-relaxed break-words whitespace-normal">
                                        Are you sure you want to delete this item? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-lg">
                        <form :action="deleteUrl" method="POST" ref="deleteForm">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" :value="csrfToken">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                Delete
                            </button>
                        </form>
                        <button @click="cancelDelete" type="button" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
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
const showConfirm = ref(false);
const dropdown = ref(null);
const deleteForm = ref(null);

const toggleMenu = () => {
    open.value = !open.value;
    if (open.value) {
        document.dispatchEvent(new CustomEvent('dropdown-opened', { detail: { id: props.editUrl } }));
    }
};

const closeMenu = () => {
    open.value = false;
};

const showDeleteConfirmation = () => {
    closeMenu();
    showConfirm.value = true;
};

const cancelDelete = () => {
    showConfirm.value = false;
};

const handleClickOutside = (event) => {
    if (dropdown.value && !dropdown.value.contains(event.target)) {
        closeMenu();
    }
    // Close confirmation modal if clicking on the overlay background
    if (showConfirm.value && event.target.classList.contains('bg-gray-500')) {
        cancelDelete();
    }
};

const handleDropdownOpened = (event) => {
    if (event.detail.id !== props.editUrl) {
        closeMenu();
    }
};

const handleEscapeKey = (event) => {
    if (event.key === 'Escape' && showConfirm.value) {
        cancelDelete();
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('dropdown-opened', handleDropdownOpened);
    document.addEventListener('keydown', handleEscapeKey);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('dropdown-opened', handleDropdownOpened);
    document.removeEventListener('keydown', handleEscapeKey);
});

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>
