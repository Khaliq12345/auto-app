<template>
    <UCard :ui="{ rounded: 'lg', shadow: 'md' }" class="shadow-lg hover:shadow-xl transition-shadow duration-300">
      <template #header>
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <UAvatar
              :src="`https://placehold.co/100x100?text=${encodeURIComponent(car.make + ' ' + car.model)}`"
              :alt="car.name"
              size="lg"
              shape="rounded"
            />
            <div>
              <h3 class="text-lg font-semibold">{{ car.make }}</h3>
              <p class="text-gray-500">{{ car.model }}</p>
            </div>
          </div>
        </div>
      </template>
  
      <div class="flex flex-col md:flex-row gap-4 mb-2">
        <!-- <img
          :src="`https://placehold.co/100x100?text=${encodeURIComponent(car.make + ' ' + car.model)}`"
          alt="Image de la voiture"
          class="w-full md:w-1/3 h-25 md:h-48 object-cover rounded-lg"
        /> -->
        <div class="flex-1">

          <h2 v-if="car.original_price <= car.external_price "  class="text-xl text-center mb-2 font-bold text-green-500">Original Price is Cheaper</h2>
          <h2 v-else class="text-xl text-center mb-2 font-bold text-red-500">Original Price is more Expensive </h2>
          <p class="text-gray-600">{{ car.model }}</p>
          <p class="text-sm  mt-2">
            <UIcon size="20" name="i-heroicons-paint-brush" class="mx-auto align-sub text-warning-500" />
            • Color : {{ car.color }} 
          </p>
          <p class="text-sm  mt-2 mb-2">
            <UIcon size="20" name="i-heroicons-currency-dollar" class="mx-auto align-sub text-warning-500" />
            • Original Price : <span :class="car.original_price <= car.external_price ? 'text-green-500' : 'text-red-500' "> {{ car.original_price }} </span> 
          </p>
          <p class="text-sm mt-2 mb-4">
            <UIcon size="20" name="i-heroicons-currency-dollar" class="mx-auto align-sub text-warning-500" />
            • External Price :  <span :class="car.external_price <= car.original_price ? 'text-green-500' : 'text-red-500' "> {{ car.external_price }} </span> 
          </p>
          <div class="text-center md:text-end">
            <ULink :to="car['external link']" target="_blank" ><UButton label="Visit" icon="i-heroicons-globe-alt" class="w-52 justify-center"  /></ULink>
            
          </div>
        </div>
      </div>
    </UCard>
  </template>
  
  <script setup>
  defineProps({
    car: {
      type: Object,
      required: true
    }
  })
  
  defineEmits(['view'])
  </script>
  