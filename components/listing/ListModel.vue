<template>
  <UCard :ui="{ rounded: 'lg', shadow: 'md' }"
    :class="`text-black shadow-lg hover:shadow-xl transition-shadow duration-300 `"
    :style="`background-color: ${car.card_color == 'red' ? '#f87171' : car.card_color == 'green' ? '#86efac' : '#fde047'}`">
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <UAvatar :src="`https://placehold.co/100x100?text=${encodeURIComponent(car.make + ' ' + car.version)}`"
            :alt="car.name" size="lg" shape="rounded" />
          <div>
            <h3 class="text-lg font-bold">{{ car.make + ' ' + car.version }}</h3>
            <p class="">ID : {{ car.id }}</p>
          </div>
        </div>
        <!-- <UButton label="" icon="i-heroicons-information-circle" @click="$emit('view', car)" /> -->
        <a :href="car.car_url" target="_blank" rel="noopener noreferrer" class="text-sm ">
                  <UIcon size="15" name="i-heroicons-link" class=" " /> Car Link
                </a>
      </div>
    </template>

    <div class="flex flex-col md:flex-row gap-4 mb-2">
      <img
        :src="!isImageUrlSimple(car.image) ? `https://placehold.co/100x100?text=${encodeURIComponent(car.make + ' ' + car.version)}` : car.image"
        alt="Image de la voiture" class="w-full md:w-1/3 h-25 md:h-55 object-cover rounded-lg" />
      <div class="flex-1">
        <h2 class=" font-semibold"> <span class="font-bold">Model :</span> {{ car.model }}</h2>
        <p class=""></p>
        <p class="text-sm  mt-2">
          <UIcon size="20" name="i-heroicons-paint-brush" class="mx-auto align-sub text-primary-800" />
          {{ car.color }} •
          <UIcon size="20" name="i-heroicons-arrow-trending-up" class="mx-auto align-sub text-primary-800" />
          {{ car.mileage }} km •
          <UIcon size="20" name="i-heroicons-wrench-screwdriver" class="mx-auto align-sub text-primary-800" />
          {{ car.fuel_type }}
        </p>
        <p class="text-sm  mt-2">
          <UIcon size="20" name="i-heroicons-currency-dollar" class="mx-auto align-sub text-primary-800" />
          Price Without Tax : {{ car.price_with_no_tax.toFixed(2) }}
        </p>
        <p class="text-sm ">
          <UIcon size="20" name="i-heroicons-currency-dollar" class="mx-auto align-sub text-primary-800" />
          Price With Tax : {{ car.price_with_tax.toFixed(2) }}
        </p>
        <p class="text-sm  mt-2">
          <UIcon size="20" name="i-heroicons-currency-dollar" class="mx-auto align-sub text-primary-800" />
          Average Price : {{ car.average_price.toFixed(2) }}
        </p>
        <p class="text-sm  ">
          <UIcon size="20" name="i-heroicons-currency-dollar" class="mx-auto align-sub text-primary-800" />
          Average Price (Best Match Based) : {{ car.average_price_based_on_best_match.toFixed(2) }}
        </p>
        <p class="text-sm  mt-2 mb-2">
          <UIcon size="20" name="i-heroicons-currency-dollar" class="mx-auto align-sub text-primary-800" />
          Lowest Price : {{ car.lowest_price.toFixed(2) }}
        </p>


      </div>

    </div>
    <div class="flex justify-between gap-x-3 mt-2 mb-4">
      <div class="text-center">

        <p class="text-sm ">
          <UIcon size="20" name="i-heroicons-calendar-days" class="mx-auto align-sub text-primary-800" />
          Last Update : {{ car.updated_at ?? car.created_at }}
        </p>
      </div>
      <!-- <a :href="car.car_url" target="_blank" rel="noopener noreferrer">
        <UIcon size="15" name="i-heroicons-link" class="" /> Link
      </a> -->
    </div>


    <div class="text-center md:text-end">
      <UButton label="View Details" icon="i-heroicons-eye" class="w-52 justify-center bg-warning-500" @click="$emit('view', car)" />
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