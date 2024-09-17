<template>
  <div>
    <div v-for="(content, index) in contents" :key="content.id" class="relative mb-4 p-4 border rounded-md flex items-start py-4">
      <div class="absolute -top-2 -right-2 p-1 cursor-pointer" @click.prevent="removeContent(index)">
        <div class="bg-black rounded-full p-1 shadow">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
      <div class="flex-shrink-0 w-full sm:w-auto">
        <ImageUploader v-if="content.showImage" :name="'content-' + index" :initialFile="content.file" @update:file="updateFile(index, $event)" />
        <div v-if="content.showImage" class="mt-2">
          <label class="block text-sm font-medium text-gray-700">Image Position</label>
          <select v-model="content.imagePosition" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            <option value="left">Left</option>
            <option value="right">Right</option>
          </select>
        </div>
        <div class="mt-2">
          <label class="block text-sm font-medium text-gray-700">Show Image</label>
          <input type="checkbox" v-model="content.showImage" class="mt-1">
        </div>
      </div>
      <div class="flex-grow pl-4 w-full sm:w-auto">
        <div class="mb-4">
          <textarea v-model="content.content" :id="'content-' + content.id" class="tinymce"></textarea>
        </div>
        <div v-if="content.error" class="text-red-500 text-sm mt-2">{{ content.error }}</div>
      </div>
    </div>
    <button @click.prevent="addContent" class="bg-black/70 hover:bg-black text-white font-semibold text-sm py-1 px-3 rounded-lg flex items-center">
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

const emit = defineEmits(['update:contents']);

let idCounter = 0; // Counter to generate unique IDs

const contents = ref(props.initialData.map(item => ({ ...item, id: idCounter++ })));

const addContent = async () => {
  contents.value.push({ id: idCounter++, file: null, content: '', imagePosition: 'left', showImage: true, error: null });
  updateContents();
  await nextTick();
  initializeTinyMCEForNew();
};

const removeContent = async (index) => {
  const removedId = contents.value[index].id;
  contents.value.splice(index, 1);
  updateContents();
  await nextTick();
  tinymce.remove(`#content-${removedId}`);
};

const updateFile = (index, file) => {
  contents.value[index].file = file;
  updateContents();
};

const updateContents = () => {
  emit('update:contents', JSON.stringify(contents.value));
};

onMounted(() => {
  if (contents.value.length === 0) {
    addContent();
  } else {
    initializeTinyMCE();
  }
});

watch(contents, async () => {
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
  contents.value.forEach(content => {
    if (!tinymce.get(`content-${content.id}`)) {
      tinymce.init({
        selector: `#content-${content.id}`,
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