<template>
  <div class="flex items-center justify-between py-2">
    <span class="flex items-center space-x-2 text-gray-600">
      <UIcon :name="icon" size="sm" />
      <span>{{ label }}</span>
    </span>
    <span :class="{'font-semibold': isDifferent, 'text-blue-500': isDifferent}">{{ value }}</span>
    <UChip v-if="isDifferent" size="xs" color="warning" variant="outline" class="ml-2">Différent</UChip>
  </div>
</template>

<script setup>
// import { UChip, UIcon } from '@nuxt/ui';

const { label, value, isDifferent, icon } = defineProps({
  label: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    required: true
  },
  isDifferent: {
    type: Boolean,
    default: false
  },
  icon: {
    type: String,
    required: true
  }
});
</script>