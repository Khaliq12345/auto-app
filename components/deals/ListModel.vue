<template>
  <UCard :ui="{ rounded: 'lg', shadow: 'md' }" :class="`text-black shadow-lg hover:shadow-xl transition-shadow duration-300 `"
  :style="`background-color: ${car.card_color== 'red' ? '#f87171' : car.card_color== 'green' ? '#86efac' : 'fde047'}`">
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
        <a :href="car.car_url" target="_blank" rel="noopener noreferrer" class="text-sm ">
                  <UIcon size="15" name="i-heroicons-link" class=" " /> Car Link
                </a>
      </div>
    </template>

    <div class="flex flex-col md:flex-row gap-4 mb-2">

      <div class="flex-1 ">
        <h2 v-if="!bestMatchingCar" class="text-xl text-center mb-2 font-bold text-warning-200">No deals found with
          {{ mPercent }} % matching </h2>
        <h2 v-if="bestMatchingCar && car.price <= bestMatchingCar.price"
          class="text-xl text-center mb-2 font-bold text-green-600">Original Price is Cheaper</h2>
        <h2 v-if="bestMatchingCar && car.price > bestMatchingCar.price" class="text-xl text-center mb-2 font-bold text-red-200">Original Price is more Expensive </h2>
        <h2 class=" font-semibold"> <span class="font-bold">Model :</span> {{ car.model }}</h2>
        <p class=""></p>
        <p class="text-sm  mt-2">
          <UIcon size="20" name="i-heroicons-paint-brush" class="mx-auto align-sub text-warning-500" />
          Color : {{ car.color }}  -- • -- 
          <UIcon size="20" name="i-heroicons-arrow-trending-up" class="mx-auto align-sub text-warning-500" />
          Mileage : {{ car.mileage }} km -- • -- 
          <UIcon size="20" name="i-heroicons-wrench-screwdriver" class="mx-auto align-sub text-primary-500" />
          {{ car.fuel_type }}
        </p>
        <p class="text-sm  mt-2 ">
          <UIcon size="20" name="i-heroicons-currency-dollar" class="mx-auto align-sub text-warning-500" />
          Original Without Tax : <span :class="!bestMatchingCar ? '' : bestMatchingCar && car.price_with_no_tax <= bestMatchingCar.price ? 'text-green-600' : 'text-red-200' "> {{ car.price_with_no_tax.toFixed(2) }} </span>  
        </p>
        <p class="text-sm  mt-2 ">
          <UIcon size="20" name="i-heroicons-currency-dollar" class="mx-auto align-sub text-warning-500" />
          Original With Tax : <span :class="!bestMatchingCar ? '' : bestMatchingCar && car.price_with_tax <= bestMatchingCar.price ? 'text-green-600' : 'text-red-200' "> {{ car.price_with_tax.toFixed(2) }} </span>  
        </p>
        
       


      </div>

    </div>
    <div v-if="bestMatchingCar" class="text-center flex justify-center gap-x-3 w-100 mt-2">
      <span class="text-sm  mb-4">
        <UIcon size="20" name="i-heroicons-sparkles" class="mx-auto align-sub text-warning-500" />
        Best Matching : {{ bestMatchingCar.matching_percentage.toFixed(2) }} %
      </span>
      <a :href="bestMatchingCar.link" target="_blank" rel="noopener noreferrer" class="text-sm ">
                  <UIcon size="15" name="i-heroicons-link" class="text-blue-500 " /> Best Match Link
                </a>
    </div>
    <div v-else class="text-center flex justify-center gap-x-3 w-100">
      <span class="text-sm  mb-4">
        <UIcon size="20" name="i-heroicons-sparkles" class="mx-auto align-sub text-warning-500" />
        No Best Matching !
      </span>
    </div>

    <div class="text-center md:text-end">
      <UButton label="View Details" icon="i-heroicons-eye" class="w-52 justify-center" @click="$emit('view', car)" />
    </div>
  </UCard>

</template>

<script setup>
const props = defineProps({
  car: {
    type: Object,
    required: true
  },
  mPercent: Number,
})

const bestMatchingCar = computed(() => {
  return props.car.comparisons?.find(caro => caro.matching_percentage >= props.mPercent) || null
})

defineEmits(['view'])
</script>