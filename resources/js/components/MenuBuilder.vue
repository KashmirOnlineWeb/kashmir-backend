<script setup>
import { ref, onMounted } from 'vue'
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
const newCustomItem = ref({
  name: '',
  url: '',
  type: 'custom',
  children: []
})

const addCustomItem = () => {
  menuItems.value.push({
    ...newCustomItem.value,
    id: `custom-${Date.now()}`
  })
  newCustomItem.value = { name: '', url: '', type: 'custom', children: [] }
  showCustomItemForm.value = false
}

const addPageToMenu = (page) => {
  menuItems.value.push({
    id: page.id,
    name: page.name,
    url: page.slug,
    type: 'page',
    children: []
  })
}

const removeMenuItem = (items, index) => {
  items.splice(index, 1)
}

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
        <div class="space-y-2">
          <div v-for="page in pages" :key="page.id"
               class="flex items-center justify-between p-2 bg-gray-50 rounded">
            <span>{{ page.name }}</span>
            <button type="button" @click="addPageToMenu(page)"
                    class="text-sm text-blue-600 hover:text-blue-800">
              Add to Menu
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
              <label class="block text-sm font-medium text-gray-700">Name</label>
              <input v-model="newCustomItem.name" type="text"
                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">URL</label>
              <input v-model="newCustomItem.url" type="text"
                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="flex justify-end space-x-2">
              <button type="button" @click="showCustomItemForm = false"
                      class="text-sm text-gray-600 hover:text-gray-800">
                Cancel
              </button>
              <button type="button" @click="addCustomItem"
                      class="text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                Add Item
              </button>
            </div>
          </div>
        </div>

        <!-- Hidden input to store menu structure -->
        <input type="hidden" name="menu_items" :value="JSON.stringify(menuItems)">

        <!-- Nested Draggable Menu Items -->
        <div class="menu-items">
          <draggable
            v-model="menuItems"
            item-key="id"
            class="space-y-2 min-h-[50px]"
            v-bind="dragOptions"
          >
            <template #item="{ element, index }">
              <div class="menu-item group">
                <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded hover:bg-gray-100 transition-colors">
                  <div class="flex items-center space-x-2 flex-1">
                    <span class="handle cursor-move text-gray-400 hover:text-gray-600">⋮</span>
                    <span>{{ element.name }}</span>
                    <span class="text-sm text-gray-500">({{ element.type }})</span>
                  </div>
                  <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button type="button" @click="removeMenuItem(menuItems, index)"
                            class="text-red-600 hover:text-red-800">
                      Remove
                    </button>
                  </div>
                </div>

                <!-- Nested Children -->
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
                          <div class="flex items-center space-x-2 flex-1">
                            <span class="handle cursor-move text-gray-400 hover:text-gray-600">⋮</span>
                            <span>{{ child.name }}</span>
                            <span class="text-sm text-gray-500">({{ child.type }})</span>
                          </div>
                          <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button type="button" @click="removeMenuItem(element.children, childIndex)"
                                    class="text-red-600 hover:text-red-800">
                              Remove
                            </button>
                          </div>
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
                                  <div class="flex items-center space-x-2 flex-1">
                                    <span class="handle cursor-move text-gray-400 hover:text-gray-600">⋮</span>
                                    <span>{{ grandChild.name }}</span>
                                    <span class="text-sm text-gray-500">({{ grandChild.type }})</span>
                                  </div>
                                  <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" @click="removeMenuItem(child.children, grandChildIndex)"
                                            class="text-red-600 hover:text-red-800">
                                      Remove
                                    </button>
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
                  class="w-full bg-black text-white py-2 rounded-md hover:bg-black/90">
            Save Menu
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