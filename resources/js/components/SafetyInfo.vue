<template>
    <div>
    <div class="grid grid-cols-2 gap-4">
      <div v-for="(info, index) in safetyInfos" :key="info.id" class="relative mb-4 p-4 border rounded-md flex items-start py-4">
        <div class="absolute -top-2 -right-2 p-1 cursor-pointer" @click.prevent="removeInfo(index)">
            <div class="bg-black rounded-full p-1 shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
        <div class="flex-grow pl-4 w-full sm:w-auto">
          <!-- Title -->
          <div class="w-full mb-4">
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input required type="text" v-model="info.title" :name="`${namePrefix}[${index}][title]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
          </div>
          <!-- Number -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Number</label>
            <input required type="number" v-model="info.number" :name="`${namePrefix}[${index}][number]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
          </div>
        </div>
      </div>
      
    </div>
    <button @click.prevent="addInfo" class="bg-black/70 hover:bg-black text-white font-semibold text-sm py-1 px-3 rounded-lg flex items-center">
        Add More
      </button>
</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    initialData: {
        type: [Array, String],
        default: () => []
    },
    namePrefix: { // {{ edit_1 }}
        type: String,
        default: 'safetyInfo' // Default name prefix
    }
});

const safetyInfos = ref([]); // Initialize as an empty array
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
    safetyInfos.value = initialData.map(item => ({
        id: idCounter++,
        title: item.title || '',
        number: item.number || null
    }));
} else {
    safetyInfos.value.push({ id: idCounter++, title: '', number: null }); // Default entry
}

const addInfo = () => {
    safetyInfos.value.push({ id: idCounter++, title: '', number: null });
};

const removeInfo = (index) => {
    safetyInfos.value.splice(index, 1);
};

onMounted(() => {
    // Any additional setup if needed
});
</script>

<style scoped>
/* Add any necessary styles here */
</style>
