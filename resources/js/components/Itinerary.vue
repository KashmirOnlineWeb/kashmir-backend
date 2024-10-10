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
        <div class="flex-grow w-full sm:w-auto">
          <div class="flex flex-wrap -mx-2">
            <!-- Itinerary Title -->
            <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Itinerary Title</label>
              <input type="text" v-model="content.itineraryTitle" :name="`${namePrefix}[${index}][itineraryTitle]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div>
            <!-- Itinerary Days -->
            <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Itinerary Days</label>
              <input type="text" v-model="content.itineraryDays" :name="`${namePrefix}[${index}][itineraryDays]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div>
            <!-- Transfer -->
            <!-- <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Transfer</label>
              <input type="text" v-model="content.transfer" :name="`${namePrefix}[${index}][transfer]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div> -->
            <!-- Sightseeings -->
            <!-- <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Sightseeings</label>
              <input type="text" v-model="content.sightseeings" :name="`${namePrefix}[${index}][sightseeings]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div> -->
            <!-- Hotel -->
            <!-- <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Hotel</label>
              <input type="text" v-model="content.hotel" :name="`${namePrefix}[${index}][hotel]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div> -->
            <!-- Hotel Details -->
            <!-- <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Hotel Details</label>
              <input type="text" v-model="content.hotelDetails" :name="`${namePrefix}[${index}][hotelDetails]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div> -->
            <!-- Hotel Check-in Time -->
            <!-- <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Hotel Check-in Time</label>
              <input type="text" v-model="content.hotelCheckInTime" :name="`${namePrefix}[${index}][hotelCheckInTime]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div> -->
            <!-- Hotel Star Rating -->
            <!-- <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Hotel Star Rating</label>
              <select v-model="content.hotelStar" :name="`${namePrefix}[${index}][hotelStar]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
              </select>
            </div> -->
            <!-- Car Detail -->
            <!-- <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Car Detail</label>
              <input type="text" v-model="content.carDetail" :name="`${namePrefix}[${index}][carDetail]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div> -->
            <!-- Car Transfer Detail -->
            <!-- <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Car Transfer Detail</label>
              <input type="text" v-model="content.carTransferDetail" :name="`${namePrefix}[${index}][carTransferDetail]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div> -->
            <!-- Car Type -->
            <!-- <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Car Type</label>
              <input type="text" v-model="content.carType" :name="`${namePrefix}[${index}][carType]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div> -->
            <!-- Car Time Duration -->
            <!-- <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Car Time Duration</label>
              <input type="text" v-model="content.carTimeDuration" :name="`${namePrefix}[${index}][carTimeDuration]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div> -->
            <!-- Adventures -->
            <!-- <div class="w-full md:w-1/2 px-2 mb-4">
              <label class="block text-sm font-medium text-gray-700">Adventures</label>
              <input type="text" v-model="content.adventures" :name="`${namePrefix}[${index}][adventures]`" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm py-1">
            </div> -->
          </div>
            <!-- Existing content textarea -->
            <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Itinerary Details</label>
            <textarea v-model="content.content" :id="`${namePrefix}-${content.id}`" :name="`${namePrefix}[${index}][content]`" class="tinymce"></textarea>
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
      type: [Array, String],
      default: () => []
    },
    namePrefix: {
      type: String,
      default: 'content'
    }
  });
  
  const emit = defineEmits(['update:contents']);
  
  let idCounter = 0; // Counter to generate unique IDs
  
  // Parse initial data and apply transformations
  const initialData = typeof props.initialData === 'string' 
    ? JSON.parse(props.initialData).map(item => ({
        ...item,
        //showImage: item.showImage === 'on' ? true : false,
        itineraryTitle: item.itineraryTitle || '',
        itineraryDays: item.itineraryDays || '',
        // transfer: item.transfer || '',
        // hotel: item.hotel || '',
        // sightseeings: item.sightseeings || '',
        // adventures: item.adventures || '',
        // hotelDetails: item.hotelDetails || '',
        // hotelCheckInTime: item.hotelCheckInTime || '',
        // carDetail: item.carDetail || '',
        // carTransferDetail: item.carTransferDetail || '',
        // carType: item.carType || '',
        // carTimeDuration: item.carTimeDuration || '',
        // hotelStar: item.hotelStar || '1', // Default to 1 star
      })) 
    : props.initialData;
  
  const contents = ref(initialData.map(item => ({ ...item, id: idCounter++ })));
  
  const addContent = async () => {
    contents.value.push({ 
      id: idCounter++, 
      //file: null, 
      content: '', 
      error: null,
      itineraryTitle: '',
      itineraryDays: '',
    //   transfer: '',
    //   hotel: '',
    //   sightseeings: '',
    //   adventures: '',
    //   hotelDetails: '',
    //   hotelCheckInTime: '',
    //   carDetail: '',
    //   carTransferDetail: '',
    //   carType: '',
    //   carTimeDuration: '',
    //   hotelStar: '1' // Default to 1 star
    });
    updateContents();
    await nextTick();
    initializeTinyMCEForNew();
  };
  
  const removeContent = async (index) => {
    const removedId = contents.value[index].id;
    contents.value.splice(index, 1);
    updateContents();
    await nextTick();
    tinymce.remove(`#${props.namePrefix}-${removedId}`);
  };
  
  const updateFile = (index, file) => {
    contents.value[index].file = file;
    updateContents();
  };
  
  const updateContents = () => {
    const transformedContents = contents.value.map(item => ({
      ...item,
      //showImage: item.showImage ? 'on' : 'off'
    }));
    emit('update:contents', JSON.stringify(transformedContents));
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
      if (!tinymce.get(`${props.namePrefix}-${content.id}`)) {
        tinymce.init({
          selector: `#${props.namePrefix}-${content.id}`,
          plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
          toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
          toolbar_mode: 'floating',
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