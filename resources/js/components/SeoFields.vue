<template>
  <div class="mb-4 p-4 border-b border-gray-200">
    <h2 class="text-md font-semibold mb-2">SEO Fields</h2>
    <div v-if="open" class="mt-4">
      <div class="mb-4">
        <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
        <input type="text" v-model="metaTitle" id="meta_title" name="meta_title"
          class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
      </div>
      <div class="mb-4">
        <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
        <textarea v-model="metaDescription" id="meta_description" name="meta_description"
          class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1"></textarea>
      </div>
      <div class="mb-4">
        <label for="keywords" class="block text-sm font-medium text-gray-700">Keywords</label>
        <input type="text" v-model="keywords" id="keywords" name="keywords"
          class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch } from 'vue';

const props = defineProps({
  metaTitle: {
    type: String,
    default: ''
  },
  metaDescription: {
    type: String,
    default: ''
  },
  keywords: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:meta-title', 'update:meta-description', 'update:keywords']);

const open = ref(true);
const metaTitle = ref(props.metaTitle);
const metaDescription = ref(props.metaDescription);
const keywords = ref(props.keywords);

watch(metaTitle, (newValue) => emit('update:meta-title', newValue));
watch(metaDescription, (newValue) => emit('update:meta-description', newValue));
watch(keywords, (newValue) => emit('update:keywords', newValue));

const toggleOpen = () => {
  open.value = !open.value;
};
</script>

<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>
