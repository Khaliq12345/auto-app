<script setup>
defineProps({
  currentPage: {
    type: Number,
    required: true
  },
  hasNextPage: {
    type: Boolean,
    required: true
  }
})
defineEmits(['prev', 'next'])
</script>

<template>
  <div class="pagination-controls">
    <!-- Previous -->
    <button @click="$emit('prev')" :disabled="currentPage === 1" class="pagination-button">
      Précédent
    </button>
    <!-- Current -->
    <span class="current-page">Page {{ currentPage }}</span>
    <!-- Next -->
    <button @click="$emit('next')" :disabled="!hasNextPage" class="pagination-button">
      Suivant
    </button>
  </div>
</template>

<style scoped>
.pagination-controls {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
  margin-top: 20px;
}
.pagination-button {
  padding: 8px 16px;
  background: #42b983;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.pagination-button:disabled {
  background: #cccccc;
  cursor: not-allowed;
}
.current-page {
  font-weight: bold;
}
</style>