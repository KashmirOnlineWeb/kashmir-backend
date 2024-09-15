<template>
  <div>
    <div v-for="(info, index) in hospitals" :key="info.id" class="relative mb-4 p-4 border rounded-md flex items-start py-4">
      <div class="absolute -top-2 -right-2 p-1 cursor-pointer" @click.prevent="removeInfo(index)">
        <div class="bg-black rounded-full p-1 shadow">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
      <div class="flex-shrink-0 w-full sm:w-auto">
        <ImageUploader :name="'hospital-' + index" :initialFile="info.file" @update:file="updateFile(index, $event)" />
      </div>
      <div class="flex-grow pl-4 w-full sm:w-auto">
        <div class="mb-4 flex gap-2">
          <input type="text" v-model="info.name" placeholder="Name" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
          <input type="text" v-model="info.contact" placeholder="Contact" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
        </div>
        <div class="mb-4 flex gap-2">
          <input type="text" v-model="info.address" placeholder="Address" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
          <input type="text" v-model="info.description" placeholder="Description" class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-200 shadow-sm py-1">
        </div>
        <div class="mb-4">
          <textarea v-model="info.content" :id="'content-' + info.id" class="tinymce"></textarea>
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
import axios from 'axios';
import ImageUploader from './ImageUploader.vue';

const props = defineProps({
  initialData: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['update:hospitalsContent']);

let idCounter = 0; // Counter to generate unique IDs

const hospitals = ref(props.initialData.map(item => ({ ...item, id: idCounter++ })));

const addInfo = async () => {
  hospitals.value.push({ id: idCounter++, file: null, name: '', address: '', contact: '', description: '', content: '', uploading: false, error: null });
  updateHospitalsContent();
  await nextTick();
  initializeTinyMCEForNew();
};

const removeInfo = async (index) => {
  const removedId = hospitals.value[index].id;
  hospitals.value.splice(index, 1);
  updateHospitalsContent();
  await nextTick();
  tinymce.remove(`#content-${removedId}`);
};

const updateFile = (index, file) => {
  hospitals.value[index].file = file;
  updateHospitalsContent();
};

const updateHospitalsContent = () => {
  emit('update:hospitalsContent', JSON.stringify(hospitals.value));
};

onMounted(() => {
  initializeTinyMCE();
});

watch(hospitals, async () => {
  await nextTick();
  initializeTinyMCEForNew();
});

const initializeTinyMCE = () => {
  tinymce.init({
    selector: 'textarea.tinymce',
    plugins: 'advlist autolink lists link image charmap preview anchor pagebreak code',
    toolbar_mode: 'floating',
    toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link code',
    menubar: 'file edit view insert format tools table help',
    height: 300,
    branding: false,
    promotion: false
  });
};

const initializeTinyMCEForNew = () => {
  hospitals.value.forEach(hospital => {
    if (!tinymce.get(`content-${hospital.id}`)) {
      tinymce.init({
        selector: `#content-${hospital.id}`,
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak code',
        toolbar_mode: 'floating',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link code',
        menubar: 'file edit view insert format tools table help',
        height: 300,
        branding: false,
        promotion: false
      });
    }
  });
};
</script>

<style scoped>
/* Add any necessary styles here */
</style>