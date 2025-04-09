<template>
    <UContainer class="">
        <UCard class="overflow-hidden">
          <template #header>
            <div class="flex justify-between items-center">
              <div>
                <h1 class="text-3xl font-bold ">Welcome Back !</h1>
                <p class="text-gray-500 mt-1">Faster Analysis & Decision Making</p>
              </div>
              <UButton color="neutral" variant="ghost" icon="i-heroicons-arrow-left-end-on-rectangle" @click="emit('handleLogout')"
                class="text-error-500 cursor-pointer">
                Log Out
              </UButton>
            </div>
          </template>
          <div class="space-y-6">
            <USeparator label="Process Your Data" />
            <div class="container mx-auto px-4 text-center">
              <h1 class="text-2xl font-bold mb-4">Start By Uploading Your Excel File</h1>
              <UInput type="file" @change="emit('handleFileChange')" accept=".xlsx, .xls" class="mb-4 border-b-emerald-300" />
              <UButton :disabled="!file" :loading="isImporting" @click="emit('importFile')" label="Import" color="primary"
                icon="i-heroicons-arrow-up-tray" />
              <UProgress v-if="isImporting" :value="uploadProgress" class="mt-4 mb-2" />
              <p v-if="isImporting">Uploading : <span class="font-bold text-amber-600">{{ uploadProgress }}</span> %</p>
              <UAlert v-if="importError" :title="importError" color="error" class="mt-2 mb-4" :close="{
                
              }" close-icon="i-heroicons-x-mark" icon="i-heroicons-exclamation-triangle" :ui="{
                icon: 'size-12'
              }" />
              <UAlert v-if="importSuccess" title="Importation réussie !" color="success" class="mt-2 mb-4" :close="{
                
              }" close-icon="i-heroicons-x-mark" icon="i-heroicons-check-badge" :ui="{
                icon: 'size-12'
              }" />
              <div v-if="importSuccess" class="justify-end flex">
                <UButton v-if="!scrapStarted" label="Start Scrapping" icon="i-heroicons-chevron-double-right"
                  class="justify-center" @click="emit('startScrapping')" color="info" />
                <UButton v-if="scrapStarted" label="Check Status" icon="i-heroicons-eye" class=" justify-center"
                  @click="emit('seeStatus')" color="warning" />
              </div>
            </div>
          </div>
        </UCard>
      </UContainer>
</template>
<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  uploadTitle: {
    type: String,
    default: 'Start By Uploading Your Excel File',
  },
  importButtonLabel: {
    type: String,
    default: 'Import',
  },
  startScrappingButtonLabel: {
    type: String,
    default: 'Start Scrapping',
  },
  importSuccessMessage: {
    type: String,
    default: 'Importation réussie !',
  },
  file: {
    type: Object as () => File | null,
    required: true,
  },
  isImporting: {
    type: Boolean,
    default: false,
  },
  uploadProgress: {
    type: Number,
    default: 0,
  },
  importError: {
    type: String,
    default: '',
  },
  importSuccess: {
    type: Boolean,
    default: false,
  },
  scrapStarted: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['handleFileChange', 'handleLogout', 'importFile', 'startScrapping', 'seeStatus']);
</script>
<!-- <script>
export default {
  props: {
  },
  computed: {
  },
};
</script> -->