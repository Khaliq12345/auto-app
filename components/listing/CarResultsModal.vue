<template>
    <UModal v-model:open="model" title='Analysis Results' description="We've Searched this car far away !"
            :ui="{ footer: 'justify-end', rounded: 'lg', shadow: 'lg', overlay: ' opacity-30' }" :close="{
              color: 'primary',
              variant: 'outline',
              class: 'rounded-full'
            }" close-icon="i-heroicons-x-mark">
            <template #body>
              <div class="grid grid-cols-1 gap-4">
                <div class="flex items-center space-x-2">
                  <UAvatar
                    :src="`https://placehold.co/100x100?text=${encodeURIComponent(selectedCar.make + ' ' + selectedCar.version)}`"
                    :alt="selectedCar.model" size="lg" />
                  <h2 class="text-xl font-semibold">{{ selectedCar.model }}</h2>
                </div>
                <img
                  :src="`https://placehold.co/100x100?text=${encodeURIComponent(selectedCar.make + ' ' + selectedCar.version)}`"
                  alt="Image de la voiture" class="w-full  h-30 object-cover rounded-lg" />
                <p class="text-sm text-center"> -•-
                  <UIcon size="20" name="i-heroicons-paint-brush" class="mx-auto align-sub  text-primary-500 " />
                  {{ selectedCar.color }} -•-
                  <UIcon size="20" name="i-heroicons-arrow-trending-up" class="mx-auto align-sub  text-primary-500 " />
                  {{ selectedCar.mileage }} km -•-
                  <UIcon size="20" name="i-heroicons-wrench-screwdriver" class="mx-auto align-sub  text-primary-500 " />
                  {{ selectedCar.fuel_type }} -•-
                </p>
                <div class="">
                   <!-- La Centrale -->
                   <SourceCollapsible title="La Centrale" color="warning" :results="resLaCentrale"
                  :relatedCars="relatedCars" domain='https://www.lacentrale.fr/' />
                  <!-- AutoScout24 -->
                  <SourceCollapsible title="AutoScout24" color="blue" :results="resAutoScout"
                    :relatedCars="relatedCars" domain='https://www.autoscout24.fr/' />
                  
                    </div>
              </div>
            </template>
            <template #footer>
              <div class="justify-center">
                <UButton class="max-w-40 min-w-32 justify-center" label="Great !" color="primary" variant="outline"
                  @click="model = false" />
              </div>
            </template>
          </UModal>
  </template>
  
  <script setup>
import SourceCollapsible from '../components/listing/SourceCollapsible.vue';
  const props = defineProps({
    modelValue: Boolean,
    selectedCar: Object,
    resLaCentrale: Object,
    resAutoScout: Object,
    relatedCars: Array,
  })
  
  const emit = defineEmits(['update:modelValue'])
  
  const model = defineModel() // 
  </script>
  