<template>
  <div>
    <div class="grid grid-cols-3 gap-4">
      <div v-for="(item, index) in images" :key="item.id" class="relative mb-4 p-4 border rounded-md flex items-start py-4">
        <div class="absolute -top-2 -right-2 p-1 cursor-pointer" @click.prevent="removeImage(index)">
          <div class="bg-black rounded-full p-1 shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
        
        <div class="flex w-full gap-4">
          <!-- Left Column - Image -->
          <div class="w-1/3">
            <ImageUploader 
              :name="`${namePrefix}[${index}][file]`" 
              :initialFile="item.file" 
              @update:file="updateFile(index, $event)" 
            />
          </div>

          <!-- Right Column - Text Fields -->
          <div class="w-2/3">
            <!-- Title -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Title</label>
              <input 
                required 
                type="text" 
                v-model="item.title" 
                :name="`${namePrefix}[${index}][title]`" 
                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1"
              >
            </div>
            <!-- URL -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">URL / Path</label>
              <input 
                required 
                type="text" 
                v-model="item.url" 
                :name="`${namePrefix}[${index}][url]`" 
                placeholder="/path/to/page"
                readonly
                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1 bg-gray-100"
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <button @click.prevent="addImage" class="bg-black/70 hover:bg-black text-white font-semibold text-sm py-1 px-3 rounded-lg flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
      {{ images.length === 0 ? 'Add' : 'Add More' }}
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import ImageUploader from './ImageUploader.vue';

const props = defineProps({
  initialData: {
    type: [Array, String],
    default: () => []
  },
  namePrefix: {
    type: String,
    default: 'images'
  }
});

const images = ref([]); // Initialize as an empty array
let idCounter = 0; // Counter to generate unique IDs

const parseJSON = (data) => {
  try {
    return JSON.parse(data);
  } catch (error) {
    console.error('JSON parsing error:', error);
    return []; // Return an empty array on error
  }
};

// Parse initial data
const initialData = typeof props.initialData === 'string' 
  ? parseJSON(props.initialData) 
  : props.initialData;

if (Array.isArray(initialData) && initialData.length > 0) {
  images.value = initialData.map(item => ({
    id: idCounter++,
    title: item.title || '',
    file: item.file || null,
    url: item.url || ''
  }));
} else {
  // No default entry - start with empty array
  images.value = [];
}

const addImage = () => {
  images.value.push({ id: idCounter++, title: '', file: null, url: '' });
};

const removeImage = (index) => {
  images.value.splice(index, 1);
};

const updateFile = (index, file) => {
  images.value[index].file = file;
};

onMounted(() => {
  // Any additional setup if needed
});
</script>

<style scoped>
/* Add any necessary styles here */
</style>
