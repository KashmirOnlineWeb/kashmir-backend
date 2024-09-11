<template>
    <div>
      <div class="mb-0">
        <button
          v-for="tab in tabs"
          :key="tab.name"
          @click="selectTab(tab)"
          :class="{ 'bg-gray-100 text-gray-900 font-semibold': tab.isActive }"
          class="px-4 py-2 rounded-t-lg"
          type="button"
        >
          {{ tab.name }}
        </button>
      </div>
      <div>
        <slot></slot>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, provide, onMounted } from 'vue';
  
  const tabs = ref([]);
  const selectedTab = ref(null);
  
  const selectTab = (tab) => {
    tabs.value.forEach((t) => (t.isActive = false));
    tab.isActive = true;
    selectedTab.value = tab;
  };
  
  provide('tabs', tabs);
  provide('selectTab', selectTab);
  
  onMounted(() => {
    if (tabs.value.length > 0) {
      selectTab(tabs.value[0]);
    }
  });
  </script>