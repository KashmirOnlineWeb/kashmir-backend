<template>
  <div>
    <div v-for="(info, index) in pharmacies" :key="index" class="mb-4 p-4 border rounded-md">
      <div class="relative mb-4">
        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
        <input type="file" :id="'image-' + index" @change="onFileChange($event, index)" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
        <img v-if="info.file" :src="info.file" alt="Preview" class="w-24 h-24 rounded-md border border-gray-200 mt-2">
      </div>
      <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" v-model="info.name" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
      </div>
      <div class="mb-4">
        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
        <input type="text" v-model="info.location" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
      </div>
      <div class="mb-4">
        <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
        <input type="text" v-model="info.contact" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
      </div>
      <div class="mb-4">
        <label for="working_hours" class="block text-sm font-medium text-gray-700">Working Hours</label>
        <input type="text" v-model="info.working_hours" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
      </div>
      <div class="mb-4">
        <label for="google_maps_link" class="block text-sm font-medium text-gray-700">Google Maps Link</label>
        <input type="text" v-model="info.google_maps_link" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
      </div>
      <button @click.prevent="removeInfo(index)" class="bg-red-500 text-white p-2 rounded">Remove</button>
    </div>
    <button @click.prevent="addInfo" class="bg-black/70 hover:bg-black text-white font-semibold text-sm py-1 px-3 rounded-lg flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
      Add More
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
  initialData: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['update:pharmaciesContent']);

const pharmacies = ref([...props.initialData]);

const addInfo = () => {
  pharmacies.value.push({ file: null, name: '', location: '', contact: '', working_hours: '', google_maps_link: '' });
  updatePharmaciesContent();
};

const removeInfo = (index) => {
  pharmacies.value.splice(index, 1);
  updatePharmaciesContent();
};

const onFileChange = async (event, index) => {
  const file = event.target.files[0];
  if (file) {
    try {
      const formData = new FormData();
      formData.append('file', file);

      const response = await axios.post('/api/upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      const imageUrl = response.data.url;
      pharmacies.value[index].file = imageUrl;
      updatePharmaciesContent();
    } catch (error) {
      console.error('Error uploading file:', error);
    }
  }
};

const updatePharmaciesContent = () => {
  emit('update:pharmaciesContent', JSON.stringify(pharmacies.value));
};
</script>

<style scoped>
/* Add any necessary styles here */
</style>