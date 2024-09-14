<template>
  <div>
    <div v-for="(info, index) in pharmacies" :key="index" class="relative mb-4 p-4 border rounded-md flex items-start py-4">
      <div class="absolute -top-2 -right-2 p-1 cursor-pointer" @click.prevent="removeInfo(index)">
        <div class="bg-black rounded-full p-1 shadow">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
      <div class="flex-shrink-0 w-full sm:w-auto">
        <ImageUploader :name="'pharmacy-' + index" :initialFile="info.file" @update:file="updateFile(index, $event)" />
      </div>
      <div class="flex-grow pl-4 w-full sm:w-auto">
        <div class="mb-4 flex gap-2">
          <input type="text" v-model="info.name" placeholder="Name" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
          <input type="text" v-model="info.contact" placeholder="Contact" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
        </div>
        <div class="mb-4 flex gap-2">
          <input type="text" v-model="info.working_hours" placeholder="Working Hours" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
          <input type="text" v-model="info.google_maps_link" placeholder="Google Maps Link" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
        </div>
        <div class="mb-4">
          <input type="text" v-model="info.location" placeholder="Location" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
        </div>
        <div v-if="info.error" class="text-red-500 text-sm mt-2">{{ info.error }}</div>
      </div>
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
import ImageUploader from './ImageUploader.vue';

const props = defineProps({
  initialData: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['update:pharmaciesContent']);

const pharmacies = ref([...props.initialData]);

const addInfo = () => {
  pharmacies.value.push({ file: null, name: '', location: '', contact: '', working_hours: '', google_maps_link: '', uploading: false, error: null });
  updatePharmaciesContent();
};

const removeInfo = (index) => {
  pharmacies.value.splice(index, 1);
  updatePharmaciesContent();
};

const updateFile = (index, file) => {
  pharmacies.value[index].file = file;
  updatePharmaciesContent();
};

const updatePharmaciesContent = () => {
  emit('update:pharmaciesContent', JSON.stringify(pharmacies.value));
};
</script>

<style scoped>
/* Add any necessary styles here */
</style>