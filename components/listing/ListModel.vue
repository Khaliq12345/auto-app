<template>
    <UCard :ui="{ rounded: 'lg', shadow: 'md' }" class="shadow-lg hover:shadow-xl transition-shadow duration-300">
      <template #header>
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <UAvatar
              :src="`https://placehold.co/100x100?text=${encodeURIComponent(car.make + ' ' + car.version)}`"
              :alt="car.name"
              size="lg"
              shape="rounded"
            />
            <div>
              <h3 class="text-lg font-semibold">{{ car.make }}</h3>
              <p class="text-gray-500">{{ car.version }}</p>
            </div>
          </div>
          <UButton label="" icon="i-heroicons-information-circle" @click="$emit('view', car)" />
        </div>
      </template>
  
      <div class="flex flex-col md:flex-row gap-4 mb-4">
        <img
          :src="`https://placehold.co/100x100?text=${encodeURIComponent(car.make + ' ' + car.version)}`"
          alt="Image de la voiture"
          class="w-full md:w-1/3 h-25 md:h-48 object-cover rounded-lg"
        />
        <div class="flex-1">
          <h2 class="text-xl font-bold">{{ car.make + ' ' + car.version }}</h2>
          <p class="text-gray-600">{{ car.model }}</p>
          <p class="text-sm text-gray-500 mt-2">
            <UIcon size="20" name="i-heroicons-paint-brush" class="mx-auto align-sub text-primary-500" />
            {{ car.color }} •
            <UIcon size="20" name="i-heroicons-arrow-trending-up" class="mx-auto align-sub text-primary-500" />
            {{ car.mileage }} km
          </p>
          <p class="text-sm text-gray-500 mt-2 mb-2">
            <UIcon size="20" name="i-heroicons-wrench-screwdriver" class="mx-auto align-sub text-primary-500" />
            Fuel Type : {{ car.fuel_type }}
          </p>
          <p class="text-sm mt-2 mb-4">
            <UIcon size="20" name="i-heroicons-calendar-days" class="mx-auto align-sub text-primary-500" />
            Last Update : {{ car.updated_at ?? car.created_at }}
          </p>
          <div class="text-center md:text-end">
            <UButton label="View Details" icon="i-heroicons-eye" class="w-52 justify-center" @click="$emit('view', car)" />
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
  