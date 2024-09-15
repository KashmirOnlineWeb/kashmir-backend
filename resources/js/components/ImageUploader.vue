<template>
  <div class="relative cursor-pointer w-24" @click="triggerFileInput">
    <input type="file" :id="inputId" @change="onFileChange" class="hidden" ref="fileInput">
    <img v-if="file" :src="file.startsWith('http') ? file : '/' + file" alt="Preview" class="w-24 h-24 rounded-md border border-gray-200">
    <div v-else class="w-24 h-24 flex items-center justify-center border border-dashed border-gray-300 rounded-md px-2">
      <span class="text-gray-500 text-xs">Click to upload image</span>
    </div>
    <div v-if="file" class="absolute top-0 right-0 p-1 bg-white border border-gray-500 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-black" viewBox="0 0 20 20" fill="currentColor" @click.stop="removeImage">
        >
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
    </div>
    <div v-if="uploading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75">
      <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
      </svg>
    </div>
    <input type="hidden" :name="name" :value="file">
    <div v-if="errorMessage" class="absolute bottom-0 left-0 p-1 bg-red-500 text-white text-xs rounded-md">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  name: {
    type: String,
    required: true
  },
  initialFile: {
    type: String,
    default: null
  }
});

const emit = defineEmits(['update:file', 'upload:start', 'upload:end']);

const file = ref(props.initialFile);
const uploading = ref(false);
const inputId = `image-${props.name}`;
const errorMessage = ref(null);

const onFileChange = async (event) => {
  const selectedFile = event.target.files[0];
  if (selectedFile) {
    if (selectedFile.size > 5 * 1024 * 1024) { // Check if file size is greater than 5 MB
      errorMessage.value = 'File size exceeds 5 MB';
      return;
    }
    emit('update:file', selectedFile);
    emit('upload:start');
    uploading.value = true;
    errorMessage.value = null; // Reset error message
    try {
      const formData = new FormData();
      formData.append('image', selectedFile);

      const response = await axios.post('/api/image', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      const imageUrl = response.data.path;
      file.value = imageUrl;
      emit('update:file', imageUrl);
      emit('upload:end');
    } catch (error) {
      console.error('Error uploading file:', error);
      errorMessage.value = error.response?.data?.message || 'An error occurred during upload';
    } finally {
      uploading.value = false;
    }
  }
};

const triggerFileInput = () => {
  const fileInput = document.getElementById(inputId);
  fileInput.click();
};

const removeImage = () => {
  file.value = null;
  emit('update:file', null);
};

watch(file, (newFile) => {
  if (newFile) {
    emit('update:file', newFile);
  }
});
</script>

<style scoped>
/* Add any necessary styles here */
</style>