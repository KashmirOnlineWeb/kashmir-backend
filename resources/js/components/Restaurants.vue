<template>
    <div>
      <div v-for="(info, index) in restaurants" :key="info.id" class="relative mb-4 p-4 border rounded-md flex items-start py-4">
        <div class="absolute -top-2 -right-2 p-1 cursor-pointer" @click.prevent="removeInfo(index)">
          <div class="bg-black rounded-full p-1 shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
        <div class="flex-shrink-0 w-full sm:w-auto">
          <ImageUploader :name="'restaurant-' + index" :initialFile="info.file" @update:file="updateFile(index, $event)" />
        </div>
        <div class="flex-grow pl-4 w-full sm:w-auto">
          <div class="mb-4 flex gap-2">
            <input type="text" v-model="info.name" placeholder="Restaurant Name" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
            <input type="text" v-model="info.location" placeholder="Location (Google Map Link)" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
          </div>
          <div class="mb-4 flex gap-2">
            <input type="text" v-model="info.contact" placeholder="Contact No." class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
            <input type="text" v-model="info.website" placeholder="Website" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
          </div>
          <div class="mb-4 flex gap-2">
            <input type="text" v-model="info.address" placeholder="Address" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
            <select v-model="info.type" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
              <option value="" disabled>Select Restaurant Type</option>
              <option value="Asian restaurant">Asian restaurant</option>
              <option value="Cafe">Cafe</option>
              <option value="Family restaurant">Family restaurant</option>
              <option value="Fast food restaurant">Fast food restaurant</option>
              <option value="French restaurant">French restaurant</option>
              <option value="Kashmiri restaurant">Kashmiri restaurant</option>
              <option value="Mughlai restaurant">Mughlai restaurant</option>
              <option value="Mutton barbecue restaurant">Mutton barbecue restaurant</option>
              <option value="Restourant">Restourant</option>
              <option value="Pizza restaurant">Pizza restaurant</option>
            </select>
          </div>
          <div class="mb-4">
            <textarea v-model="info.description" placeholder="Description" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1"></textarea>
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
  import { ref, onMounted, watch, nextTick } from 'vue';
  import ImageUploader from './ImageUploader.vue';
  
  const props = defineProps({
    initialData: {
      type: Array,
      default: () => []
    }
  });
  
  const emit = defineEmits(['update:restaurantsContent']);
  
  let idCounter = 0; // Counter to generate unique IDs
  
  const restaurants = ref(props.initialData.map(item => ({ ...item, id: idCounter++, type: '' })));
  
  const addInfo = async () => {
    restaurants.value.push({ id: idCounter++, file: null, name: '', location: '', cuisine: '', website: '', address: '', type: '', description: '', error: null });
    updateRestaurantsContent();
    await nextTick();
  };
  
  const removeInfo = async (index) => {
    restaurants.value.splice(index, 1);
    updateRestaurantsContent();
    await nextTick();
  };
  
  const updateFile = (index, file) => {
    restaurants.value[index].file = file;
    updateRestaurantsContent();
  };
  
  const updateRestaurantsContent = () => {
    emit('update:restaurantsContent', JSON.stringify(restaurants.value));
  };
  
  onMounted(() => {
    // Any necessary initialization
  });
  
  watch(restaurants, async () => {
    await nextTick();
    // Any necessary updates
  });
  </script>
  
  <style scoped>
  /* Add any necessary styles here */
  </style>