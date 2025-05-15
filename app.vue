<script setup lang="ts">
import ColorModeButton from './components/ColorModeButton.vue';
</script>

<template>
  <ClientOnly>
    <UApp>
    <NuxtPage />
    <!-- <div class="text-center py-2 z-10">
      <ColorModeButton />
    </div> -->
  </UApp>
  </ClientOnly>
</template>
