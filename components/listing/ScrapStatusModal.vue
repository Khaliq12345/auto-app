<template>
    <UModal
      v-model:open="model"
      title="Scrapping Status"
      description="We're processing your data"
      :ui="{ footer: 'justify-end', rounded: 'lg', shadow: 'lg', overlay: ' opacity-30' }"
      :close="{
        color: 'primary',
        variant: 'outline',
        class: 'rounded-full'
      }"
      close-icon="i-heroicons-x-mark"
    >
      <template #body>
        <div class="grid grid-cols-1 gap-4">
          <div v-if="totalToProcess !== alreadyProcessed" class="text-center">
            <img src="../assets/imgs/load.gif" alt="Loading" class="w-50 place-self-center" />
            <h2 class="text-xl font-semibold">Processing : {{ scrapStatus }}</h2>
          </div>
          <div v-else class="text-center">
            <UIcon size="100" name="i-heroicons-check-badge" class="mx-auto text-primary-500" />
            <h2 class="text-xl font-semibold">Done : {{ scrapStatus }}</h2>
          </div>
          <p class="text-sm text-start">
            -•-
            <UIcon size="20" name="i-heroicons-calendar-days" class="mx-auto align-sub text-primary-500" />
            Total to Process : {{ totalToProcess }} -•-
          </p>
          <p class="text-sm text-start">
            -•-
            <UIcon size="20" name="i-heroicons-calendar-days" class="mx-auto align-sub text-primary-500" />
            Already Processed : {{ alreadyProcessed }} -•-
          </p>
          <p class="text-sm text-start">
            -•-
            <UIcon size="20" name="i-heroicons-calendar-days" class="mx-auto align-sub text-primary-500" />
            Remaining : {{ totalToProcess - alreadyProcessed }} -•-
          </p>
        </div>
      </template>
  
      <template #footer>
        <div class="justify-center">
          <UButton
            class="max-w-40 min-w-32 justify-center"
            label="Close"
            color="primary"
            variant="outline"
            @click="model = false"
          />
        </div>
      </template>
    </UModal>
  </template>
  
  <script setup>
  const props = defineProps({
    modelValue: Boolean,
    totalToProcess: Number,
    alreadyProcessed: Number,
    scrapStatus: String
  })
  
  const emit = defineEmits(['update:modelValue'])
  
  const model = defineModel() // 
  </script>
  