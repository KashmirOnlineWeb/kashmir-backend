<template>
  <div>
    <div class="space-y-3">
      <div v-for="(room, index) in rooms" :key="room.id" class="relative p-3 border border-gray-200 rounded-lg bg-gray-50">
        <!-- Remove button (only show if not the first room) -->
        <div v-if="index > 0" class="absolute -top-1 -right-1 p-1 cursor-pointer" @click.prevent="removeRoom(index)">
          <div class="bg-red-500 rounded-full p-1 shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 text-white" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Left Column - Fields and Amenities -->
          <div>
            <!-- First Row - Three Fields -->
            <div class="grid grid-cols-3 gap-2 mb-3">
              <!-- Room Type -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Room Type *</label>
                <select 
                  v-model="room.room_type" 
                  :name="`${namePrefix}[${index}][room_type]`"
                  required
                  class="w-full rounded border-gray-300 shadow-sm py-2 px-3 text-sm"
                >
                  <option value="">Select</option>
                  <option value="Classic">Classic</option>
                  <option value="Deluxe">Deluxe</option>
                  <option value="Penthouse">Penthouse</option>
                  <option value="Suite">Suite</option>
                </select>
              </div>

              <!-- Room Size -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Room Size *</label>
                <input 
                  type="text" 
                  v-model="room.room_size" 
                  :name="`${namePrefix}[${index}][room_size]`"
                  placeholder="250 sq ft"
                  required
                  class="w-full rounded border-gray-300 shadow-sm py-2 px-3 text-sm"
                >
              </div>

              <!-- Price -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                <input 
                  type="number" 
                  v-model="room.price" 
                  :name="`${namePrefix}[${index}][price]`"
                  placeholder="0.00"
                  step="0.01"
                  min="0"
                  required
                  class="w-full rounded border-gray-300 shadow-sm py-2 px-3 text-sm"
                >
              </div>
            </div>

            <!-- Second Row - Amenities -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-3">Amenities</label>
              <div class="grid grid-cols-4 gap-2">
                <div v-for="amenity in availableAmenities" :key="amenity.value" class="flex items-center p-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                  <input 
                    type="checkbox" 
                    :id="`amenity-${index}-${amenity.value}`"
                    :value="amenity.value"
                    v-model="room.amenities"
                    :name="`${namePrefix}[${index}][amenities][]`"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  >
                  <label :for="`amenity-${index}-${amenity.value}`" class="ml-3 block text-sm text-gray-700 cursor-pointer flex-1">
                    {{ amenity.label }}
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column - Gallery -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Gallery</label>
            <ImageRepeater 
              :initial-data="room.gallery || []"
              :name-prefix="`${namePrefix}[${index}][gallery]`"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Add Room Button -->
    <button 
      @click.prevent="addRoom" 
      class="mt-3 bg-black/70 hover:bg-black text-white font-medium text-sm py-1.5 px-3 rounded-lg flex items-center"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
      Add Room
    </button>

    <!-- Hidden inputs for JSON data and price range -->
    <input type="hidden" :name="namePrefix" :value="jsonData">
    <input type="hidden" name="min_price" :value="minPrice">
    <input type="hidden" name="max_price" :value="maxPrice">
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import ImageRepeater from './ImageRepeater.vue';

const props = defineProps({
  initialData: {
    type: [Array, String],
    default: () => []
  },
  namePrefix: {
    type: String,
    default: 'rooms'
  }
});

const emit = defineEmits(['update:data', 'update:prices']);

const rooms = ref([]);
let idCounter = 0;

// Available amenities
const availableAmenities = [
  { value: 'complimentary_breakfast', label: 'Complimentary Breakfast' },
  { value: 'kitchen', label: 'Kitchen' },
  { value: 'air_conditioner', label: 'Air Conditioner' },
  { value: 'heater', label: 'Heater' },
  { value: 'swimming_pool', label: 'Swimming Pool' },
  { value: 'free_wifi', label: 'Free WiFi' }
];

// Parse JSON data
const parseJSON = (data) => {
  try {
    return JSON.parse(data);
  } catch (error) {
    console.error('JSON parsing error:', error);
    return [];
  }
};

// Initialize rooms data
const initializeRooms = () => {
  const initialData = typeof props.initialData === 'string' 
    ? parseJSON(props.initialData) 
    : props.initialData;

  if (Array.isArray(initialData) && initialData.length > 0) {
    rooms.value = initialData.map(item => ({
      id: idCounter++,
      room_type: item.room_type || '',
      room_size: item.room_size || '',
      price: item.price || '',
      amenities: Array.isArray(item.amenities) ? item.amenities : [],
      gallery: Array.isArray(item.gallery) ? item.gallery : []
    }));
  } else {
    // Add default room
    rooms.value.push({
      id: idCounter++,
      room_type: '',
      room_size: '',
      price: '',
      amenities: [],
      gallery: []
    });
  }
};

// Computed property for JSON data
const jsonData = computed(() => {
  return JSON.stringify(rooms.value.map(room => ({
    room_type: room.room_type,
    room_size: room.room_size,
    price: room.price,
    amenities: room.amenities,
    gallery: room.gallery
  })));
});

// Computed properties for min and max prices
const minPrice = computed(() => {
  const prices = rooms.value
    .map(room => parseFloat(room.price) || 0)
    .filter(price => price > 0);
  
  if (prices.length === 0) return 0;
  return Math.min(...prices);
});

const maxPrice = computed(() => {
  const prices = rooms.value
    .map(room => parseFloat(room.price) || 0)
    .filter(price => price > 0);
  
  if (prices.length === 0) return 0;
  return Math.max(...prices);
});

// Add new room
const addRoom = () => {
  rooms.value.push({
    id: idCounter++,
    room_type: '',
    room_size: '',
    price: '',
    amenities: [],
    gallery: []
  });
};

// Remove room (but not the first one)
const removeRoom = (index) => {
  if (index > 0) {
    rooms.value.splice(index, 1);
  }
};

// Watch for changes and emit updates
watch(jsonData, (newValue) => {
  emit('update:data', newValue);
}, { deep: true });

// Watch for price changes and emit price updates
watch([minPrice, maxPrice], ([newMinPrice, newMaxPrice]) => {
  emit('update:prices', { minPrice: newMinPrice, maxPrice: newMaxPrice });
  
  // Also dispatch a custom event for regular JavaScript
  const event = new CustomEvent('priceRangeUpdated', {
    detail: { minPrice: newMinPrice, maxPrice: newMaxPrice }
  });
  document.dispatchEvent(event);
}, { deep: true, immediate: true });

// Also watch for changes in individual room prices
watch(rooms, () => {
  const prices = rooms.value
    .map(room => parseFloat(room.price) || 0)
    .filter(price => price > 0);
  
  const min = prices.length === 0 ? 0 : Math.min(...prices);
  const max = prices.length === 0 ? 0 : Math.max(...prices);
  
  emit('update:prices', { minPrice: min, maxPrice: max });
  
  // Also dispatch a custom event for regular JavaScript
  const event = new CustomEvent('priceRangeUpdated', {
    detail: { minPrice: min, maxPrice: max }
  });
  document.dispatchEvent(event);
}, { deep: true });

onMounted(() => {
  initializeRooms();
});
</script>

<style scoped>
/* Add any necessary styles here */
</style>
