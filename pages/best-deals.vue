<template>
  <div
    class="min-h-screen min-w-screen  bg-gradient-to-br from-red-50 to-primary-500  items-center justify-center  pt-0">
    <div>
      <!-- Top Card -->
      <UContainer class="">
        <UCard class="overflow-hidden">
          <template #header>
            <div class="flex justify-between items-center">
              <div>
                <h1 class="text-3xl font-bold ">Find Best Deals !</h1>
                <p class="text-gray-500 mt-1">From <ULink :to="domain" target="_blank" class="text-blue-500 font-bold" >{{ domain == 'https://www.autoscout24.fr/' ? 'AutoScout24' : 'La Centrale' }}</ULink> </p>
              </div>
              <div class="text-end">
                <ULink to="/listing"  class="" >
              <UButton color="gray" variant="ghost" icon="i-heroicons-arrow-uturn-left" @click=""
                class=" cursor-pointer">
                Back
              </UButton>
            </ULink>
              <UButton color="gray" variant="ghost" icon="i-heroicons-arrow-left-end-on-rectangle" @click="handleLogout"
                class="text-error-500 cursor-pointer">
                Log Out
              </UButton>
              </div>
             
            </div>
          </template>
          <div class="">

            <USeparator label="Search Results" />
            <div class="container mx-auto px-4 my-2 text-center">
              <h1 class="text-2xl font-bold mb-4">Browse and Compare Prices</h1>
            </div>
          </div>
        </UCard>
      </UContainer>
      <!-- Listing Card -->
      <UContainer class="mt-4">
        <UCard :title="title" :ui="{ rounded: 'lg', shadow: 'md' }">
          <!-- Search -->
          <div class="text-center place-self-center">
            <UFormField class="text-center">
              <UInput size="lg" v-model="searchTerm" placeholder="Search by Name or Model..."
                icon="i-heroicons-magnifying-glass" class="w-100 justify-center" />
            </UFormField>
          </div>
          <!-- Filters -->
          <UCollapsible :unmount-on-hide="false" class="flex flex-col gap-2  my-4  place-self-center">
            <div class="text-center cursor-pointer">
              <UIcon size="20" name="i-heroicons-adjustments-horizontal" class="align-sub text-primary-500 " />
              Filters
            </div>
            <template #content>
              <div class="text-center" >
                <div class="grid  grid-cols-2 gap-4 py-5 text-center ">
                  <div class="">
                    <span class=" text-xs font-bold">Cut Off Price</span>
                <div class="text-center place-self-center mt-2">
                  <UButtonGroup class="mb-4 ">
                    <UInput size="lg" v-model="cutOffPrice" type="number" placeholder="" icon="i-heroicons-currency-dollar"
                      class="w-30 justify-center"/>
                    <UButton :disabled="!cutOffPrice" :loading="loadingCars" @click="reloadPage" label="Reload" color="primary"
                      icon="i-heroicons-arrow-path" />
                  </UButtonGroup>
                </div>
                  </div>
                  <!--  -->
                  <div class="">
                    <span class=" text-xs font-bold">Matching Percent</span>
                <div class="text-center place-self-center mt-2">
                  <UButtonGroup class="mb-4 ">
                    <UInput size="lg" v-model="mPercent" type="number" placeholder="" icon="i-heroicons-percent-badge"
                      class="w-30 justify-center"/>
                    <UButton :disabled="true" label=" % " color="primary" />
                  </UButtonGroup>
                </div>
                  </div>
                </div>
                



                <div class="grid  grid-cols-3 gap-4 py-5 text-center ">
                  <UFormField>
                    <USelect v-model="selectedColors" icon="i-heroicons-paint-brush" :items="availableColors" multiple
                      clearable :placeholder="availableColors.length ? 'By color' : 'No Available Color'">
                    </USelect>
                  </UFormField>
                  <UFormField>
                    <USelect v-model="selectedModels" icon="i-heroicons-swatch" :items="availableModels" multiple
                      clearable :placeholder="availableModels.length ? 'By model' : 'No Available Model'" />
                  </UFormField>
                  <UFormField>
                    <USelect v-model="selectedDeals" icon="i-heroicons-sparkles" :items="availableDeals" multiple
                      clearable :placeholder="availableDeals.length ? 'By Deals' : 'No Available Option'">
                    </USelect>
                  </UFormField>
                </div>

              </div>
            </template>
          </UCollapsible>
          <!-- Main Listing -->
          <div v-if="loadingCars" class="place-items-center place-content-center my-15">
            <Loading />
            <div class="container mx-auto p-4 text-center">
              <p>Loading Data ... </p>
            </div>
          </div>
          <div v-if="(searchTerm && filteredCars.length == 0)" class="text-center my-15">
            <UIcon size="100" name="i-heroicons-exclamation-circle" class="mx-auto align-sub  text-red-300 " />
            <p class="font-bold"> No Matching Results !</p>
          </div>
          <div v-if="(!loadingCars && cars.length == 0)" class="text-center my-15">
            <UIcon size="100" name="i-heroicons-circle-stack" class="mx-auto align-sub  text-red-300 " />
            <p class="font-bold"> No Data to Show !</p>
          </div>
          <div v-if="!loadingCars && cars.length != 0" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div v-for="car in paginatedCars" :key="car.id" class="relative">
              <ListModel :key="car.id" :car="car" :mPercent="mPercent" @view="openModal"/>
            </div>
          </div>
          <!-- Others -->
           <!-- Modal For Details -->
          <CarResultsModal v-model="isModalOpen" :selectedCar="selectedCar" :resLaCentrale="resLaCentrale"
            :resAutoScout="resAutoScout" :relatedCars="relatedCars" />
          <!-- Pagination -->
          <div class="justify-center">
            <UPagination v-model:page="currentPage" :total="filteredCars.length" :to="to" :sibling-count="1" show-edges
              :per-page="itemsPerPage" align="center" firstIcon="i-heroicons-chevron-double-left"
              prevIcon="i-heroicons-chevron-left" nextIcon="i-heroicons-chevron-right"
              lastIcon="i-heroicons-chevron-double-right" class="mt-6  place-self-center" />
          </div>
        </UCard>
      </UContainer>
    </div>
  </div>
</template>

<script setup>
import Loading from '../components/Loading.vue';
import ListModel from '../components/deals/ListModel.vue';
import CarResultsModal from '../components/deals/CarResultsModal.vue';
import { useBDealsFunctions } from '~/composables/useBDealsFunctions';
import { UInput } from '#components';
const {
  mPercent,
  openModal,
  isModalOpen,
  selectedCar,
  resLaCentrale,
  resAutoScout,
  relatedCars,
  domain,
        cutOffPrice,
        reloadPage,
        loadingCars,
        title,
        searchTerm,
        selectedColors,
        selectedModels,
        selectedDeals,
        currentPage,
        itemsPerPage,
        cars,
        handleLogout,
        getAllCars,
        availableColors,
        availableModels,
        availableDeals,
        filteredCars,
        pageCount,
        paginatedCars,
        to,
    } = useBDealsFunctions();
definePageMeta({
  middleware: ["auth"]
})

</script>
