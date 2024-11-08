<script setup>
import { ref, computed, watch } from 'vue'
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

const initializeChildren = (items) => {
  return items.map(item => ({
    ...item,
    children: item.children ? initializeChildren(item.children) : [] // Recursively initialize children
  }));
}

const menuItems = ref(initializeChildren(props.existingMenu));

const searchQuery = ref('')
const filteredPages = computed(() => {
  return props.pages.filter(page => 
    page.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const showCustomItemForm = ref(false)
const newCustomItem = ref({ name: '', url: '', type: 'custom', children: [] })

const addCustomItem = () => {
  if (!newCustomItem.value.name || !newCustomItem.value.url) {
    return
  }

  const slug = newCustomItem.value.url
  const fullUrl = normalizeUrl(slug)

  menuItems.value.push({
    ...newCustomItem.value,
    id: `custom-${Date.now()}`,
    url: fullUrl,
    slug: slug
  })

  showCustomItemForm.value = false
  newCustomItem.value = { name: '', url: '', type: 'custom', children: [] }
}

const addChildIndex = ref(null)
const newChildItem = ref({ name: '', url: '', type: 'custom', children: [] })

const showAddChildForm = (index) => {
  addChildIndex.value = index
  showPageSelector.value = null // Reset page selector
  childPageSearchQuery.value = '' // Reset search
}

const cancelAddChild = () => {
  addChildIndex.value = null
  newChildItem.value = { name: '', url: '', type: 'custom', children: [] }
}

const addChildToMenu = (indexPath) => {
  if (!newChildItem.value.name && !newChildItem.value.url) {
    return
  }

  const [parentIndex, childIndex] = indexPath.toString().split('-').map(Number)
  const parentItem = childIndex !== undefined 
    ? menuItems.value[parentIndex].children[childIndex]
    : menuItems.value[parentIndex]

  const slug = newChildItem.value.url
  const isCustomChild = newChildItem.value.type === 'custom'
  const fullUrl = isCustomChild 
    ? normalizeUrl(slug) // Use the slug directly for custom items
    : normalizeUrl(parentItem.url + '/' + slug.split('/').pop()) // Use parent URL for non-custom items

  // Ensure children array exists
  if (!parentItem.children) {
    parentItem.children = []
  }

  // Add the new child item
  parentItem.children.push({
    ...newChildItem.value,
    id: `custom-${Date.now()}`,
    url: fullUrl,
    slug: slug
  })

  // Reset states
  cancelAddChild()
}

// Function to update URLs recursively when items are moved
const updateChildrenUrls = (items, parentUrl = '') => {
  items.forEach(item => {
    // Update current item's URL
    item.url = item.type === 'custom' 
      ? normalizeUrl(item.url) // Use the item's URL directly for custom items
      : normalizeUrl(parentUrl + '/' + item.url.split('/').pop()); // Use parent URL for non-custom items
    
    // Update children recursively if they exist
    if (item.children && item.children.length > 0) {
      // For non-custom parents, pass the current item's URL as the new parent URL
      const newParentUrl = item.type === 'custom' ? item.url : parentUrl + '/' + item.url.split('/').pop();
      updateChildrenUrls(item.children, newParentUrl);
    }
  });
}
// Watch for changes in menuItems structure
watch(menuItems, (newItems) => {
  // Update all URLs starting from top level
  updateChildrenUrls(newItems)
}, { deep: true })

const editId = ref(null)
const editItem = ref({ name: '', url: '' })

const showEditForm = (item) => {
  if (item.type !== 'custom') return

  editId.value = item.id
  const prefix = item.url.split('/').slice(0, -1).join('/') + '/'
  const urlWithoutPrefix = item.url.replace(prefix, '')
  editItem.value = { ...item, url: urlWithoutPrefix }
}

const cancelEdit = () => {
  editId.value = null
  editItem.value = { name: '', url: '' }
}

const updateMenuItem = (item) => {
  if (!editItem.value.name || !editItem.value.url) {
    return
  }

  const slug = editItem.value.url
  const fullUrl = normalizeUrl(item.url.split('/').slice(0, -1).join('/') + '/' + slug)

  item.name = editItem.value.name
  item.url = fullUrl
  item.slug = slug

  cancelEdit()
}

const normalizeUrl = (url) => {
  if (!url) return ''
  url = '/' + url.replace(/^\/+/, '')
  return url.replace(/\/+/g, '/')
}

const dragOptions = {
  group: 'menu',
  handle: '.handle',
  animation: 150,
  fallbackOnBody: true,
  swapThreshold: 0.65,
  dragClass: 'drag-item',
  ghostClass: 'ghost-item'
}

const addPageToMenu = (page) => {
  if (!page || !page.slug) {
    console.error("Invalid page data:", page);
    return; // Ensure the page has valid data
  }

  const slug = page.slug.split('/').pop(); // Extract slug from the URL
  const fullUrl = normalizeUrl(page.slug); // Normalize the URL

  menuItems.value.push({
    ...page,
    id: `page-${Date.now()}`,
    url: fullUrl, // Set the normalized URL
    slug: slug, // Set the slug
    type: 'page', // Assuming 'page' is the type for these items
    children: [] // Initialize children as an empty array
  });
}

const removeMenuItem = (items, index) => {
  if (items && index >= 0 && index < items.length) {
    items.splice(index, 1);
  }
}

const newChildCustomItem = ref({ name: '', url: '', type: 'custom', children: [] });

const addCustomChildItem = (parentIndex) => {
  if (!newChildCustomItem.value.name || !newChildCustomItem.value.url) {
    return;
  }

  const slug = newChildCustomItem.value.url;
  const fullUrl = normalizeUrl(slug);

  // Ensure the children array exists
  if (!menuItems.value[parentIndex].children) {
    menuItems.value[parentIndex].children = [];
  }

  // Add the new custom child item
  menuItems.value[parentIndex].children.push({
    ...newChildCustomItem.value,
    id: `custom-${Date.now()}`,
    url: fullUrl,
    slug: slug
  });

  // Reset the newChildCustomItem
  newChildCustomItem.value = { name: '', url: '', type: 'custom', children: [] };
};

const showAddCustomChildForm = ref(null);

const toggleAddCustomChildForm = (index) => {
  showAddCustomChildForm.value = showAddCustomChildForm.value === index ? null : index;
};

// Add new refs for the page selection functionality
const showPageSelector = ref(null) // Track which item is showing the page selector
const childPageSearchQuery = ref('') // Separate search query for child page selection

// Add computed for filtered pages in child selector
const filteredChildPages = computed(() => {
  return props.pages.filter(page => 
    page.name.toLowerCase().includes(childPageSearchQuery.value.toLowerCase())
  )
})

// Add method to handle adding a page as child
const addPageAsChild = (indexPath, page) => {
  if (!page || !page.slug) return

  const [parentIndex, childIndex] = indexPath.toString().split('-').map(Number)
  const parentItem = childIndex !== undefined 
    ? menuItems.value[parentIndex].children[childIndex]
    : menuItems.value[parentIndex]

  const slug = page.slug.split('/').pop()
  const fullUrl = normalizeUrl(parentItem.url + '/' + slug)

  // Ensure children array exists
  if (!parentItem.children) {
    parentItem.children = []
  }

  // Add the page as child
  parentItem.children.push({
    ...page,
    id: `page-${Date.now()}`,
    url: fullUrl,
    slug: slug,
    type: 'page',
    children: []
  })

  // Reset states
  addChildIndex.value = null
  showPageSelector.value = null
  childPageSearchQuery.value = ''
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

        <div class="space-y-2 overflow-y-auto">
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
              <input v-model="newCustomItem.url" type="text"
                     required
                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
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
          <input type="hidden" :name="`data[${index}][slug]`" :value="item.slug">
          <input type="hidden" :name="`data[${index}][type]`" :value="item.type">
          
          <!-- Level 2 -->
          <template v-for="(child, childIndex) in item.children" :key="child.id">
            <input type="hidden" :name="`data[${index}][children][${childIndex}][id]`" :value="child.id">
            <input type="hidden" :name="`data[${index}][children][${childIndex}][name]`" :value="child.name">
            <input type="hidden" :name="`data[${index}][children][${childIndex}][url]`" :value="child.url">
            <input type="hidden" :name="`data[${index}][children][${childIndex}][slug]`" :value="child.slug">
            <input type="hidden" :name="`data[${index}][children][${childIndex}][type]`" :value="child.type">
            
            <!-- Level 3 -->
            <template v-for="(grandChild, grandChildIndex) in child.children" :key="grandChild.id">
              <input type="hidden" :name="`data[${index}][children][${childIndex}][children][${grandChildIndex}][id]`" :value="grandChild.id">
              <input type="hidden" :name="`data[${index}][children][${childIndex}][children][${grandChildIndex}][name]`" :value="grandChild.name">
              <input type="hidden" :name="`data[${index}][children][${childIndex}][children][${grandChildIndex}][url]`" :value="grandChild.url">
              <input type="hidden" :name="`data[${index}][children][${childIndex}][children][${grandChildIndex}][slug]`" :value="grandChild.slug">
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
              <div class="menu-item group dashed-border">
                <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded hover:bg-gray-100 transition-colors">
                  <div class="handle flex items-center space-x-2 flex-1 cursor-move">
                    <span class="text-gray-400 hover:text-gray-600">⋮</span>
                    <span>{{ element.name }}</span>
                    <span class="text-sm text-gray-500">
                      ({{ element.url }}) 
                      <span v-if="element.type === 'custom'">(Custom)</span>
                    </span>
                  </div>
                  <button v-if="element.type === 'custom'" type="button" @click="showEditForm(element)"
                          class="ml-2 text-black hover:text-black/80 opacity-0 group-hover:opacity-100 transition-opacity">
                    Edit
                  </button>
                  <button type="button" @click="showAddChildForm(index)"
                          class="ml-2 text-blue-600 hover:text-blue-800 opacity-0 group-hover:opacity-100 transition-opacity">
                    Add Child
                  </button>
                  <button type="button" @click="removeMenuItem(menuItems, index)"
                          class="ml-auto text-red-600 hover:text-red-800 opacity-0 group-hover:opacity-100 transition-opacity">
                    Remove
                  </button>
                </div>

                <!-- Replace the existing Add Child Form with this -->
                <div v-if="addChildIndex === index" class="mb-4 p-4 bg-gray-50 rounded-lg">
                  <!-- Show options if page selector is not active -->
                  <div v-if="showPageSelector !== index" class="space-y-3">
                    <h4 class="font-medium text-gray-700 mb-3">Add Child Item</h4>
                    <div class="flex space-x-2">
                      <button
                        @click="showPageSelector = index"
                        class="flex-1 py-2 px-4 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-md transition-colors"
                      >
                        Select from Pages
                      </button>
                      <button
                        @click="showPageSelector = null"
                        class="flex-1 py-2 px-4 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-md transition-colors"
                      >
                        Add Custom Link
                      </button>
                    </div>
                  </div>

                  <!-- Page selector -->
                  <div v-if="showPageSelector === index" class="space-y-3">
                    <div class="flex items-center justify-between">
                      <h4 class="font-medium text-gray-700">Select a Page</h4>
                      <button
                        @click="showPageSelector = null"
                        class="text-sm text-gray-600 hover:text-gray-800"
                      >
                        Back
                      </button>
                    </div>
                    
                    <div class="mb-3">
                      <input
                        v-model="childPageSearchQuery"
                        type="text"
                        placeholder="Search pages..."
                        class="w-full px-3 py-2 border rounded-md border-gray-300 shadow-sm focus:outline-none"
                      >
                    </div>

                    <div class="max-h-48 overflow-y-auto space-y-2">
                      <div
                        v-for="page in filteredChildPages"
                        :key="page.id"
                        class="flex items-center justify-between p-2 bg-gray-50 hover:bg-gray-100 rounded cursor-pointer"
                        @click="addPageAsChild(index, page)"
                      >
                        <span>{{ page.name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-600">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                      </div>
                    </div>
                  </div>

                  <!-- Existing custom item form -->
                  <div v-if="addChildIndex === index && showPageSelector !== index" class="space-y-3">
                    <!-- Your existing custom item form content -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Child Name *</label>
                      <input v-model="newChildItem.name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Child URL *</label>
                      <input v-model="newChildItem.url" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div class="flex justify-end space-x-2">
                      <button type="button" @click="cancelAddChild" class="text-sm text-gray-600 hover:text-gray-800">
                        Cancel
                      </button>
                      <button type="button" 
                              @click="addChildToMenu(index)"
                              :disabled="!newChildItem.name && !newChildItem.url"
                              :class="[
                                'text-sm px-3 py-1 rounded transition-colors',
                                (!newChildItem.name && !newChildItem.url)
                                  ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                  : 'bg-green-600 text-white hover:bg-green-500'
                              ]">
                        Add Child
                      </button>
                    </div>
                  </div>
                </div>
                <!-- Edit Form for Parent -->
                <div v-if="editId === element.id" class="mb-4 p-4 bg-gray-50 rounded-lg">
                  <div class="space-y-3">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Edit Name *</label>
                      <input v-model="editItem.name" type="text"
                             required
                             class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Edit URL *</label>
                      <input v-model="editItem.url" type="text"
                             required
                             class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div class="flex justify-end space-x-2">
                      <button type="button" @click="cancelEdit"
                              class="text-sm text-gray-600 hover:text-gray-800">
                        Cancel
                      </button>
                      <button type="button" 
                              @click="updateMenuItem(element)"
                              :disabled="!editItem.name || !editItem.url"
                              :class="[
                                'text-sm px-3 py-1 rounded transition-colors',
                                (!editItem.name || !editItem.url)
                                  ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                  : 'bg-green-600 text-white hover:bg-green-500'
                              ]">
                        Update
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Second Level -->
                <div class="pl-8 mt-2 dashed-border child-level">
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
                            <span class="text-sm text-gray-500">
                              ({{ child.url }}) 
                              <span v-if="child.type === 'custom'">(Custom)</span>
                            </span>
                          </div>
                          <button v-if="child.type === 'custom'" type="button" @click="showEditForm(child)"
                                  class="ml-2 text-black hover:text-black/80 opacity-0 group-hover:opacity-100 transition-opacity">
                            Edit
                          </button>
                          <button type="button" @click="showAddChildForm(index + '-' + childIndex)"
                                  class="ml-2 text-blue-600 hover:text-blue-800 opacity-0 group-hover:opacity-100 transition-opacity">
                            Add Child
                          </button>
                          <button type="button" @click="removeMenuItem(element.children, childIndex)"
                                  class="ml-auto text-red-600 hover:text-red-800 opacity-0 group-hover:opacity-100 transition-opacity">
                            Remove
                          </button>
                        </div>

                        <!-- Add the child form here -->
                        <div v-if="addChildIndex === index + '-' + childIndex" class="mb-4 p-4 bg-gray-50 rounded-lg">
                          <!-- Show options if page selector is not active -->
                          <div v-if="showPageSelector !== index + '-' + childIndex" class="space-y-3">
                            <h4 class="font-medium text-gray-700 mb-3">Add Child Item</h4>
                            <div class="flex space-x-2">
                              <button
                                @click="showPageSelector = index + '-' + childIndex"
                                class="flex-1 py-2 px-4 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-md transition-colors"
                              >
                                Select from Pages
                              </button>
                              <button
                                @click="showPageSelector = null"
                                class="flex-1 py-2 px-4 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-md transition-colors"
                              >
                                Add Custom Link
                              </button>
                            </div>
                          </div>

                          <!-- Page selector -->
                          <div v-if="showPageSelector === index + '-' + childIndex" class="space-y-3">
                            <div class="flex items-center justify-between">
                              <h4 class="font-medium text-gray-700">Select a Page</h4>
                              <button
                                @click="showPageSelector = null"
                                class="text-sm text-gray-600 hover:text-gray-800"
                              >
                                Back
                              </button>
                            </div>
                            
                            <div class="mb-3">
                              <input
                                v-model="childPageSearchQuery"
                                type="text"
                                placeholder="Search pages..."
                                class="w-full px-3 py-2 border rounded-md border-gray-300 shadow-sm focus:outline-none"
                              >
                            </div>

                            <div class="max-h-48 overflow-y-auto space-y-2">
                              <div
                                v-for="page in filteredChildPages"
                                :key="page.id"
                                class="flex items-center justify-between p-2 bg-gray-50 hover:bg-gray-100 rounded cursor-pointer"
                                @click="addPageAsChild(index + '-' + childIndex, page)"
                              >
                                <span>{{ page.name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-600">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                              </div>
                            </div>
                          </div>

                          <!-- Custom item form -->
                          <div v-if="addChildIndex === index + '-' + childIndex && showPageSelector !== index + '-' + childIndex" class="space-y-3">
                            <div>
                              <label class="block text-sm font-medium text-gray-700">Child Name *</label>
                              <input v-model="newChildItem.name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                              <label class="block text-sm font-medium text-gray-700">Child URL *</label>
                              <input v-model="newChildItem.url" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="flex justify-end space-x-2">
                              <button type="button" @click="cancelAddChild" class="text-sm text-gray-600 hover:text-gray-800">
                                Cancel
                              </button>
                              <button type="button" 
                                      @click="addChildToMenu(index + '-' + childIndex)"
                                      :disabled="!newChildItem.name && !newChildItem.url"
                                      :class="[
                                        'text-sm px-3 py-1 rounded transition-colors',
                                        (!newChildItem.name && !newChildItem.url)
                                          ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                          : 'bg-green-600 text-white hover:bg-green-500'
                                      ]">
                                Add Child
                              </button>
                            </div>
                          </div>
                        </div>

                        <!-- Edit Form for Child -->
                        <div v-if="editId === child.id" class="mb-4 p-4 bg-gray-50 rounded-lg">
                          <div class="space-y-3">
                            <div>
                              <label class="block text-sm font-medium text-gray-700">Edit Name *</label>
                              <input v-model="editItem.name" type="text"
                                     required
                                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                              <label class="block text-sm font-medium text-gray-700">Edit URL *</label>
                              <input v-model="editItem.url" type="text"
                                     required
                                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="flex justify-end space-x-2">
                              <button type="button" @click="cancelEdit"
                                      class="text-sm text-gray-600 hover:text-gray-800">
                                Cancel
                              </button>
                              <button type="button" 
                                      @click="updateMenuItem(child)"
                                      :disabled="!editItem.name || !editItem.url"
                                      :class="[
                                        'text-sm px-3 py-1 rounded transition-colors',
                                        (!editItem.name || !editItem.url)
                                          ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                          : 'bg-green-600 text-white hover:bg-green-500'
                                      ]">
                                Update
                              </button>
                            </div>
                          </div>
                        </div>

                        <!-- Third Level -->
                        <div class="pl-8 mt-2 dashed-border child-level">
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
                                    <span class="text-sm text-gray-500">
                                      ({{ grandChild.url }}) 
                                      <span v-if="grandChild.type === 'custom'">(Custom)</span>
                                    </span>
                                  </div>
                                  <button v-if="grandChild.type === 'custom'" type="button" @click="showEditForm(grandChild)"
                                          class="ml-2 text-black hover:text-black/80 opacity-0 group-hover:opacity-100 transition-opacity">
                                    Edit
                                  </button>
                                  <button type="button" @click="removeMenuItem(child.children, grandChildIndex)"
                                          class="ml-auto text-red-600 hover:text-red-800 opacity-0 group-hover:opacity-100 transition-opacity">
                                    Remove
                                  </button>
                                </div>

                                <!-- Edit Form for Grandchild -->
                                <div v-if="editId === grandChild.id" class="mb-4 p-4 bg-gray-50 rounded-lg">
                                  <div class="space-y-3">
                                    <div>
                                      <label class="block text-sm font-medium text-gray-700">Edit Name *</label>
                                      <input v-model="editItem.name" type="text"
                                             required
                                             class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                      <label class="block text-sm font-medium text-gray-700">Edit URL *</label>
                                      <input v-model="editItem.url" type="text"
                                             required
                                             class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                      <button type="button" @click="cancelEdit"
                                              class="text-sm text-gray-600 hover:text-gray-800">
                                        Cancel
                                      </button>
                                      <button type="button" 
                                              @click="updateMenuItem(grandChild)"
                                              :disabled="!editItem.name || !editItem.url"
                                              :class="[
                                                'text-sm px-3 py-1 rounded transition-colors',
                                                (!editItem.name || !editItem.url)
                                                  ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                                  : 'bg-green-600 text-white hover:bg-green-500'
                                              ]">
                                        Update
                                      </button>
                                    </div>
                                  </div>
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

/* Dashed border for drop areas */
.dashed-border {
  border: 2px dashed #cbd5e0;
  border-radius: 0.375rem;
  padding: 0.5rem;
  margin-bottom: 0.5rem;
}

/* Indentation for child levels */
.child-level {
  margin-left: 20px; /* Adjust as needed for visual clarity */
}
</style>