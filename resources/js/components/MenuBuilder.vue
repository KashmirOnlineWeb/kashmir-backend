<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import draggable from 'vuedraggable'

const props = defineProps({
  pages: {
    type: Array,
    required: true
  },
  existingMenu: {
    type: Array,
    default: () => []
  }
})

const menuItems = ref(props.existingMenu)
const showCustomItemForm = ref(false)
const currentParentUrls = ref([]) // Store parent URLs for the current form

const newCustomItem = ref({
  name: '',
  url: '',
  type: 'custom',
  children: []
})

const searchQuery = ref('')

const filteredPages = computed(() => {
  if (!searchQuery.value) return props.pages
  const query = searchQuery.value.toLowerCase()
  return props.pages.filter(page => 
    page.name.toLowerCase().includes(query)
  )
})

// Function to normalize URL (ensure it starts with / and remove double slashes)
const normalizeUrl = (url) => {
  if (!url) return ''
  url = '/' + url.replace(/^\/+/, '')
  return url.replace(/\/+/g, '/')
}

// Function to get full URL path based on parent URLs
const getFullUrlPath = (parentUrls, currentUrl) => {
  const baseUrl = parentUrls.join('')
  return normalizeUrl(baseUrl + '/' + currentUrl)
}

const addCustomItem = () => {
  // Add validation check
  if (!newCustomItem.value.name || !newCustomItem.value.url) {
    return
  }
  
  // Normalize the URL with parent prefixes
  const fullUrl = getFullUrlPath(currentParentUrls.value, newCustomItem.value.url)
  
  menuItems.value.push({
    ...newCustomItem.value,
    id: `custom-${Date.now()}`,
    url: fullUrl
  })
  
  newCustomItem.value = { name: '', url: '', type: 'custom', children: [] }
  showCustomItemForm.value = false
  currentParentUrls.value = []
}

const addPageToMenu = (page) => {
  menuItems.value.push({
    id: page.id,
    name: page.name,
    url: normalizeUrl(page.slug),
    type: 'page',
    children: []
  })
}

// Show custom item form with parent context
const showCustomItemFormWithContext = (parentUrls = []) => {
  currentParentUrls.value = parentUrls
  showCustomItemForm.value = true
}

const getUrlPrefix = computed(() => {
  return currentParentUrls.value.join('')
})

const removeMenuItem = (items, index) => {
  items.splice(index, 1)
}

// Function to update URLs recursively when items are moved
const updateChildrenUrls = (items, parentUrl = '') => {
  items.forEach(item => {
    // Update current item's URL
    item.url = normalizeUrl(parentUrl + '/' + item.url.split('/').pop())
    
    // Update children recursively if they exist
    if (item.children && item.children.length > 0) {
      updateChildrenUrls(item.children, item.url)
    }
  })
}

// Watch for changes in menuItems structure
watch(menuItems, (newItems) => {
  // Update all URLs starting from top level
  updateChildrenUrls(newItems)
}, { deep: true })

// Add these options for draggable
const dragOptions = {
  group: 'menu',
  handle: '.handle',
  animation: 150,
  fallbackOnBody: true,
  swapThreshold: 0.65,
  dragClass: 'drag-item',
  ghostClass: 'ghost-item'
}
</script>

<template>
  <div class="p-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- Available Pages -->
      <div class="border rounded-lg p-4">
        <h3 class="font-semibold mb-3">Available Pages</h3>
        
        <!-- Sleeker search input -->
        <div class="mb-3">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search pages..."
            class="w-full px-3 py-0.5 border rounded-md border-gray-300 shadow-sm focus:outline-none"
          >
        </div>

        <div class="space-y-2 max-h-[400px] overflow-y-auto">
          <div v-for="page in filteredPages" :key="page.id"
               class="flex items-center justify-between p-2 bg-gray-50 rounded">
            <span>{{ page.name }}</span>
            <button type="button" @click="addPageToMenu(page)"
                    class="p-1 text-gray-600 hover:text-gray-800 rounded-full hover:bg-gray-200 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Menu Structure -->
      <div class="md:col-span-2 border rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-semibold">Menu Structure</h3>
          <button type="button" @click="showCustomItemForm = true"
                  class="text-sm bg-gray-100 px-3 py-1 rounded hover:bg-gray-200">
            Add Custom Link
          </button>
        </div>

        <!-- Custom Item Form -->
        <div v-if="showCustomItemForm" class="mb-4 p-4 bg-gray-50 rounded-lg">
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-gray-700">Name *</label>
              <input v-model="newCustomItem.name" type="text"
                     required
                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">URL *</label>
              <!-- Show parent URL prefix -->
              <div v-if="getUrlPrefix" class="mt-1 text-sm text-gray-500">
                Parent URL prefix: {{ getUrlPrefix }}
              </div>
              <div class="mt-1 relative">
                <span v-if="getUrlPrefix" class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                  {{ getUrlPrefix }}
                </span>
                <input v-model="newCustomItem.url" type="text"
                       required
                       :class="['mt-1 block w-full rounded-md border-gray-300 shadow-sm', 
                               getUrlPrefix ? 'pl-[calc(1.5rem+_' + getUrlPrefix.length + 'ch)]' : '']"
                       :placeholder="getUrlPrefix ? 'Enter path' : 'Enter full URL'">
              </div>
            </div>
            <div class="flex justify-end space-x-2">
              <button type="button" @click="showCustomItemForm = false"
                      class="text-sm text-gray-600 hover:text-gray-800">
                Cancel
              </button>
              <button type="button" 
                      @click="addCustomItem"
                      :disabled="!newCustomItem.name || !newCustomItem.url"
                      :class="[
                        'text-sm px-3 py-1 rounded transition-colors',
                        (!newCustomItem.name || !newCustomItem.url)
                          ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                          : 'bg-black text-white hover:bg-black/90'
                      ]">
                Add Item
              </button>
            </div>
          </div>
        </div>

        <!-- Replace the single hidden input with a template that creates multiple inputs -->
        <template v-for="(item, index) in menuItems" :key="item.id">
          <input type="hidden" :name="`data[${index}][id]`" :value="item.id">
          <input type="hidden" :name="`data[${index}][name]`" :value="item.name">
          <input type="hidden" :name="`data[${index}][url]`" :value="item.url">
          <input type="hidden" :name="`data[${index}][type]`" :value="item.type">
          
          <!-- Level 2 -->
          <template v-for="(child, childIndex) in item.children" :key="child.id">
            <input type="hidden" :name="`data[${index}][children][${childIndex}][id]`" :value="child.id">
            <input type="hidden" :name="`data[${index}][children][${childIndex}][name]`" :value="child.name">
            <input type="hidden" :name="`data[${index}][children][${childIndex}][url]`" :value="child.url">
            <input type="hidden" :name="`data[${index}][children][${childIndex}][type]`" :value="child.type">
            
            <!-- Level 3 -->
            <template v-for="(grandChild, grandChildIndex) in child.children" :key="grandChild.id">
              <input type="hidden" :name="`data[${index}][children][${childIndex}][children][${grandChildIndex}][id]`" :value="grandChild.id">
              <input type="hidden" :name="`data[${index}][children][${childIndex}][children][${grandChildIndex}][name]`" :value="grandChild.name">
              <input type="hidden" :name="`data[${index}][children][${childIndex}][children][${grandChildIndex}][url]`" :value="grandChild.url">
              <input type="hidden" :name="`data[${index}][children][${childIndex}][children][${grandChildIndex}][type]`" :value="grandChild.type">
            </template>
          </template>
        </template>

        <!-- Nested Draggable Menu Items -->
        <div class="menu-items">
          <draggable
            v-model="menuItems"
            item-key="id"
            class="space-y-2 min-h-[50px]"
            v-bind="dragOptions"
          >
            <!-- Add empty state message -->
            <template #header v-if="menuItems.length === 0">
              <div class="text-center py-8 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mx-auto mb-2 text-gray-400">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <p class="text-sm mb-1">No menu items yet</p>
                <p class="text-xs">Click the plus icon next to any page to add it to the menu</p>
              </div>
            </template>

            <template #item="{ element, index }">
              <div class="menu-item group">
                <!-- First Level -->
                <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded hover:bg-gray-100 transition-colors">
                  <div class="handle flex items-center space-x-2 flex-1 cursor-move">
                    <span class="text-gray-400 hover:text-gray-600">⋮</span>
                    <span>{{ element.name }}</span>
                    <span class="text-sm text-gray-500">({{ element.url }})</span>
                  </div>
                  <button type="button" @click="removeMenuItem(menuItems, index)"
                          class="ml-auto text-red-600 hover:text-red-800 opacity-0 group-hover:opacity-100 transition-opacity">
                    Remove
                  </button>
                </div>

                <!-- Second Level -->
                <div class="pl-8 mt-2">
                  <draggable
                    v-model="element.children"
                    item-key="id"
                    class="space-y-2 min-h-[10px]"
                    v-bind="dragOptions"
                  >
                    <template #item="{ element: child, index: childIndex }">
                      <div class="menu-item group">
                        <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded hover:bg-gray-100 transition-colors">
                          <div class="handle flex items-center space-x-2 flex-1 cursor-move">
                            <span class="text-gray-400 hover:text-gray-600">⋮</span>
                            <span>{{ child.name }}</span>
                            <span class="text-sm text-gray-500">({{ child.url }})</span>
                          </div>
                          <button type="button" @click="removeMenuItem(element.children, childIndex)"
                                  class="ml-auto text-red-600 hover:text-red-800 opacity-0 group-hover:opacity-100 transition-opacity">
                            Remove
                          </button>
                        </div>

                        <!-- Third Level -->
                        <div class="pl-8 mt-2">
                          <draggable
                            v-model="child.children"
                            item-key="id"
                            class="space-y-2 min-h-[10px]"
                            v-bind="dragOptions"
                          >
                            <template #item="{ element: grandChild, index: grandChildIndex }">
                              <div class="menu-item group">
                                <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded hover:bg-gray-100 transition-colors">
                                  <div class="handle flex items-center space-x-2 flex-1 cursor-move">
                                    <span class="text-gray-400 hover:text-gray-600">⋮</span>
                                    <span>{{ grandChild.name }}</span>
                                    <span class="text-sm text-gray-500">({{ grandChild.url }})</span>
                                  </div>
                                  <button type="button" @click="removeMenuItem(child.children, grandChildIndex)"
                                          class="ml-auto text-red-600 hover:text-red-800 opacity-0 group-hover:opacity-100 transition-opacity">
                                    Remove
                                  </button>
                                </div>
                              </div>
                            </template>
                          </draggable>
                        </div>
                      </div>
                    </template>
                  </draggable>
                </div>
              </div>
            </template>
          </draggable>
        </div>

        <!-- Submit Button -->
        <div class="mt-4">
          <button type="submit"
                  :disabled="menuItems.length === 0"
                  :class="[
                    'w-full py-2 rounded-md transition-colors',
                    menuItems.length === 0 
                      ? 'bg-gray-300 text-gray-500 cursor-not-allowed' 
                      : 'bg-black text-white hover:bg-black/90'
                  ]">
            {{ menuItems.length === 0 ? 'Add items to save menu' : 'Save Menu' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.menu-item {
  margin-bottom: 0.5rem;
}

.drag-item {
  opacity: 0.5;
}

.ghost-item {
  opacity: 0.5;
  background: #edf2f7;
}

/* Add visual cues for drag targets */
.draggable-container {
  position: relative;
}

.draggable-container::before {
  content: '';
  position: absolute;
  left: 0;
  right: 0;
  height: 2px;
  background: transparent;
  transition: background-color 0.2s;
}

.draggable-container.is-dragging-over::before {
  background: #4299e1;
}

/* Add min-height to drop zones to make them visible */
.min-h-[10px] {
  min-height: 10px;
}

.min-h-[50px] {
  min-height: 50px;
}
</style>