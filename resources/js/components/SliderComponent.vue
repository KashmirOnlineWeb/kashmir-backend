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
            <ImageUploader :name="`${namePrefix}[${index}][file]`" :initialFile="element.file" @update:file="updateFile(index, $event)" @upload:start="handleUploadStart" @upload:end="handleUploadEnd" />
          </div>
          <div class="flex-grow pl-4">
            <div class="mb-4 flex">
              <input type="text" v-model="element.alt" :name="`${namePrefix}[${index}][alt]`" placeholder="Alt Text" class="mt-1 block w-1/2 rounded-md border-gray-200 shadow-sm py-1 mr-2" @input="updateSliderData">
              <input type="text" v-model="element.title" :name="`${namePrefix}[${index}][title]`" placeholder="Title" class="mt-1 block w-1/2 rounded-md border-gray-200 shadow-sm py-1" @input="updateSliderData">
            </div>
            <div>
              <textarea v-model="element.description" :name="`${namePrefix}[${index}][description]`" placeholder="Description" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1" @input="updateSliderData"></textarea>
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
import { ref, watch, nextTick } from 'vue';
import { useSliderStore } from '../stores/sliderStore';
import draggable from 'vuedraggable';
import ImageUploader from './ImageUploader.vue';

const props = defineProps({
  initialData: {
    type: [Array, String],
    default: () => []
  },
  namePrefix: {
    type: String,
    default: 'slider'
  }
});

const sliderStore = useSliderStore();
const emit = defineEmits(['update:slider-data']);

// Parse initial data if it's a string
let initialData;
try {
  initialData = typeof props.initialData === 'string' ? JSON.parse(props.initialData) : props.initialData;
} catch (e) {
  console.error('Failed to parse initialData:', e);
  initialData = [];
}

const images = ref([...initialData]);

const addImage = async () => {
  images.value.push({ id: Date.now(), file: null, alt: '', title: '', description: '', uploading: false, error: null });
  updateSliderData();
  await nextTick();
};

const removeImage = async (index) => {
  images.value.splice(index, 1);
  updateSliderData();
  await nextTick();
};

const updateFile = (index, file) => {
  images.value[index].file = file;
  updateSliderData();
};

const updateSliderData = () => {
  emit('update:slider-data', images.value);
};

const handleUploadStart = () => {
  sliderStore.setUploadingStatus(true);
};

const handleUploadEnd = () => {
  sliderStore.setUploadingStatus(false);
};

// Watch for changes in images and update the store
watch(images, updateSliderData, { deep: true });
</script>

<style scoped>
.list-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
</style>