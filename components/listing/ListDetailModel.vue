<template>
    <UCard :key="index">
              <template #header>
                <div class="flex items-center space-x-2">
                  <UAvatar
                    :src="`https://placehold.co/100x100?text=${encodeURIComponent(voiture.name)}`"
                    :alt="voiture.name"
                    size="md"
                  />
                  <h2 class="text-md font-semibold">{{ voiture.name }} {{ voiture.model }}</h2>
                </div>
              </template>
  
              <div class="flex space-x-3">
                <!-- :src="!isImageUrlSimple(voiture.image[0]) || voiture.image.length == 0 ? `https://placehold.co/100x100?text=${encodeURIComponent(voiture.name)}` : voiture.image[0]" -->
                <img
                :src="!isImageUrlSimple(voiture.image) ? `https://placehold.co/100x100?text=${encodeURIComponent(voiture.name)}` : voiture.image"
                  alt="Car image"
                  class="w-full h-30 object-cover rounded-lg"
                />
                  <!-- @error="handleImageError($event, voiture.name)" -->
                <div class="text-sm text-start self-center w-full">
                  <ul>
                    <li>
                      <UIcon size="20" name="i-heroicons-user-group" class="text-rose-500 mx-auto align-sub " />
                      {{ voiture.deal_type }}
                    </li>
                    <li>
                      <UIcon size="20" name="i-heroicons-arrow-trending-up" class="text-rose-500 mx-auto align-sub " />
                      {{ voiture.mileage }} km
                    </li>
                    <li>
                      <UIcon size="20" name="i-heroicons-currency-dollar" class="text-rose-500 mx-auto align-sub " />
                      {{ voiture.price }} €
                    </li>
                    <li>
                      <UIcon size="20" name="i-heroicons-wrench-screwdriver" class="text-rose-500 mx-auto align-sub " />
                      {{ voiture.fuel_type }} -- {{ voiture.boite_de_vitesse }}
                    </li>
                    <li>
                      <UIcon size="20" name="i-heroicons-calendar-days" class="text-rose-500 mx-auto align-sub " />
                      Updated At : {{ voiture.updated_at }}
                    </li>
                  </ul>
                </div>
              </div>
  
              <div class=" mt-2">
                <p class="my-2 text-justify break-words whitespace-pre-wrap">{{ voiture.car_metadata.replace(/\n/g, ' ') }}</p>
                <a :href="voiture.link" target="_blank" rel="noopener noreferrer">
                  <UIcon size="15" name="i-heroicons-link" class="text-rose-500" /> Link
                </a>
              </div>

             
  
              <template #footer>
                <div class="place-items-center">
                  <p class="mb-4">Matching ({{ voiture.matching_percentage }}%) :</p>
                  <ProgressBar :level="voiture.matching_percentage" />
                   <div class="text-center mt-4">
                <p>Reason : {{ voiture.matching_percentage_reason }}</p>
              </div>
                </div>
              </template>
            </UCard>
</template>
  
<script setup>
  defineProps({
    voiture: {
      type: Object,
      required: true
    },
    index: {
      type: [String, Number],
      required: true
    }
  })
</script>
  