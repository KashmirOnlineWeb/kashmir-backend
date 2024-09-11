<template>
  <div :style="{ display: isActive ? 'block' : 'none' }">
    <slot></slot>
  </div>
</template>

<script setup>
import { inject, onMounted, ref, watch } from 'vue';

const tabs = inject('tabs');
const selectTab = inject('selectTab');

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
});

const isActive = ref(false);

onMounted(() => {
  const tab = {
    name: props.name,
    isActive: isActive.value,
  };
  tabs.value.push(tab);
  if (tabs.value.length === 1) {
    isActive.value = true; // Set the first tab as active
    selectTab(tab);
  }
});

watch(() => tabs.value, (newTabs) => {
  const tab = newTabs.find(t => t.name === props.name);
  if (tab) {
    isActive.value = tab.isActive;
  }
}, { deep: true });
</script>