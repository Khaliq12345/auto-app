<template>
    <UCollapsible :unmount-on-hide="false" class="my-2 place-self-center">
      <div class="text-center cursor-pointer mb-3">
        <UIcon size="20" name="i-heroicons-presentation-chart-bar" :class="`text-${color}-500`" />
        Found 
        <span
          :class="`rounded-4xl border-b-green-500 border-b-4 bg-${color}-50 text-${color}-500 px-3 mx-2`"
        >
          {{ results }}
        </span>
        result(s) from {{ title }}
      </div>
      <template #content>
        <div v-if="results.value == '0'" class="text-center py-2">
          <UIcon size="20" name="i-heroicons-no-symbol" :class="`text-${color}-500`" />
          <p>No Data Found!</p>
        </div>
  
        <div v-else class="grid grid-cols-1 gap-2">
          <div v-for="(voiture, index) in filteredRelatedCars" >
            <ListDetailModel :voiture="voiture" :index="index" />
          </div>
        </div>
      </template>
    </UCollapsible>
  </template>
  
  <script setup>
  import ListDetailModel from './ListDetailModel.vue';
 const props =  defineProps({
    title: String,
    color: String,
    results: Object,
    relatedCars: Array,
    domain: String,
  })
  const filteredRelatedCars = computed(() => {
  return props.relatedCars.filter(voiture => voiture.domain === props.domain);
});
  const handleImageError = (event, name) => {
  const imgElement = event.target ;
  imgElement.src = `https://placehold.co/100x100?text=${encodeURIComponent(name)}`;
};
  </script>
  