<template>
  <div>
    <h2 class="text-md font-semibold mb-2">Slider</h2>
    <p class="text-sm text-gray-600 mb-4">Add and arrange images for the slider. Drag to reorder.</p>
    <draggable v-model="images" class="list-group" item-key="id" @end="updateSliderData">
      <template #item="{ element, index }">
        <div class="relative mb-4 p-4 border rounded-md flex items-start py-4">
          <div class="absolute -top-2 -right-2 p-1 cursor-pointer" @click="removeImage(index)">
            <div class="bg-black rounded-full p-1 shadow">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>
          <div class="flex-shrink-0">
            <div class="relative cursor-pointer" @click="triggerFileInput(index)">
              <input type="file" :id="'image-' + index" @change="onFileChange($event, index)" class="hidden" ref="fileInput">
              <img v-if="element.file" :src="element.file" alt="Preview" class="w-24 h-24 rounded-md border border-gray-200">
              <div v-else class="w-24 h-24 flex items-center justify-center border border-dashed border-gray-300 rounded-md px-2">
                <span class="text-gray-500 text-xs">Click to upload image</span>
              </div>
              <div v-if="element.file" class="absolute top-0 right-0 p-1 bg-white rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M4 3a1 1 0 000 2h12a1 1 0 100-2H4zM3 7a1 1 0 011-1h12a1 1 0 011 1v9a1 1 0 01-1 1H4a1 1 0 01-1-1V7zm2 1v7h10V8H5z" />
                </svg>
              </div>
              <div v-if="element.uploading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75">
                <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
              </div>
            </div>
          </div>
          <div class="flex-grow pl-4">
            <div class="mb-4 flex">
              <input type="text" v-model="element.alt" placeholder="Alt Text" class="mt-1 block w-1/2 rounded-md border-gray-200 shadow-sm py-1 mr-2" @input="updateSliderData">
              <input type="text" v-model="element.title" placeholder="Title" class="mt-1 block w-1/2 rounded-md border-gray-200 shadow-sm py-1" @input="updateSliderData">
            </div>
            <div>
              <textarea v-model="element.description" placeholder="Description" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1" @input="updateSliderData"></textarea>
            </div>
            <div v-if="element.error" class="text-red-500 text-sm mt-2">{{ element.error }}</div>
          </div>
        </div>
      </template>
    </draggable>
    <button @click.prevent="addImage" class="bg-black/70 hover:bg-black text-white font-semibold text-sm py-1 px-3 rounded-lg flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
      Add More
    </button>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import draggable from 'vuedraggable';

const props = defineProps({
  initialData: {
    type: Array,
    default: () => [
      { id: 1, file: 'https://via.placeholder.com/150', alt: 'Image 1', title: 'Title 1', description: 'Description 1' },
      { id: 2, file: 'https://via.placeholder.com/150', alt: 'Image 2', title: 'Title 2', description: 'Description 2' }
    ]
  }
});

const emit = defineEmits(['update:sliderData']);

const images = ref([...props.initialData]);

const addImage = () => {
  images.value.push({ id: Date.now(), file: null, alt: '', title: '', description: '', uploading: false, error: null });
  updateSliderData();
};

const removeImage = (index) => {
  images.value.splice(index, 1);
  updateSliderData();
};

const onFileChange = async (event, index) => {
  const file = event.target.files[0];
  if (file) {
    images.value[index].uploading = true;
    images.value[index].error = null;
    try {
      const formData = new FormData();
      formData.append('file', file);

      const response = await axios.post('/api/upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      const imageUrl = response.data.url;
      images.value[index].file = imageUrl;
      images.value[index].uploading = false;
      updateSliderData();
    } catch (error) {
      images.value[index].uploading = false;
      images.value[index].error = 'Error uploading file: ' + error.message;
    }
  }
};

const triggerFileInput = (index) => {
  const fileInput = document.getElementById('image-' + index);
  fileInput.click();
};

const updateSliderData = () => {
  emit('update:sliderData', JSON.stringify(images.value));
};

// Watch for changes in images and emit the updated data
watch(images, updateSliderData, { deep: true });
</script>

<style scoped>
.list-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
</style>
