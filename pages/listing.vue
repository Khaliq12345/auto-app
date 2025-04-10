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
                <h1 class="text-3xl font-bold ">Welcome Back !</h1>
                <p class="text-gray-500 mt-1">Faster Analysis & Decision Making</p>
              </div>
              <UButton color="gray" variant="ghost" icon="i-heroicons-arrow-left-end-on-rectangle" @click="handleLogout"
                class="text-error-500 cursor-pointer">
                Log Out
              </UButton>
            </div>
          </template>
          <div class="">
            <!-- Scrapping Status -->
            <UAlert :title="'Scraping Status ( ' + scrapStatus + ' )' " :description="'Last Scraping Since : ' + lastScrap" :color="totalToProcess !== alreadyProcessed? 'warning':'success'"
              icon="i-heroicons-presentation-chart-line" variant="subtle"  :actions="[
                {
                  label: 'Total to Process : ' + totalToProcess,
                  class: 'py-2 text-start',
                  variant: 'subtle',
                  color: 'error'
                },
                {
                  label: 'Already Processed : ' + alreadyProcessed,
                  class: 'py-2 text-end',
                  variant: 'subtle',
                  color: 'success'
                }
              ]" />
              <div class="text-end">
                <UButton label="Refresh Status" icon="i-heroicons-arrow-path" class="my-2" @click="seeStatus"
              color="warning" />
              </div>
            
            <USeparator label="Process Your Data" />
            <div class="container mx-auto px-4 my-2 text-center">
              <h1 class="text-2xl font-bold mb-4">Start By Uploading Your Excel File</h1>
              <UButtonGroup class="mb-4 ">
                <UInput color="neutral" type="file" variant="subtle" icon="i-heroicons-document"
                  @change="handleFileChange" accept=".xlsx, .xls" />
                <UButton :disabled="!file" :loading="isImporting" @click="importFile" label="Import" color="primary"
                  icon="i-heroicons-arrow-up-tray" />
              </UButtonGroup>

              <UProgress v-if="isImporting" :value="uploadProgress" max="100" class="mt-4 mb-2" />
              <p v-if="isImporting">Uploading : <span class="font-bold text-amber-600">{{ uploadProgress }}</span> %</p>
              <UAlert v-if="importError" :title="importError" color="error" class="mt-2 mb-4" :close="{
                color: 'white',
              }" close-icon="i-heroicons-x-mark" icon="i-heroicons-exclamation-triangle" :ui="{
                icon: 'size-12'
              }" />
              <UAlert v-if="importSuccess" title="Importation réussie !" color="success" class="mt-2 mb-4" :close="{
                color: 'white',
              }" close-icon="i-heroicons-x-mark" icon="i-heroicons-check-badge" :ui="{
                icon: 'size-12'
              }" />
              <div v-if="importSuccess" class="justify-end flex">
                <UButton v-if="!scrapStarted" label="Start Scrapping" icon="i-heroicons-chevron-double-right"
                  class="justify-center" @click="startScrapping" color="info" />
                <!-- <UButton v-if="scrapStarted" label="Check Status" icon="i-heroicons-eye" class=" justify-center"
                  @click="seeStatus" color="warning" /> -->
              </div>
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
              <div class="grid  grid-cols-2 gap-4 py-5 text-center ">
                <UFormField>
                  <USelect v-model="selectedColors" icon="i-heroicons-paint-brush" :items="availableColors" multiple
                    clearable :placeholder="availableColors.length ? 'By color' : 'No Available Color'">
                  </USelect>
                </UFormField>
                <UFormField>
                  <USelect v-model="selectedModels" icon="i-heroicons-swatch" :items="availableModels" multiple
                    clearable :placeholder="availableModels.length ? 'By model' : 'No Available Model'" />
                </UFormField>
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
              <ListModel :key="car.id" :car="car" @view="openModal" />
            </div>
          </div>
          <!-- Others -->
          <!-- Modal For Details -->
          <CarResultsModal v-model="isModalOpen" :selectedCar="selectedCar" :resLaCentrale="resLaCentrale"
            :resAutoScout="resAutoScout" :relatedCars="relatedCars" />
          <!-- Modal For Status -->
          <!-- <ScrapStatusModal v-model="isStatusModalOpen" :totalToProcess="totalToProcess"
            :alreadyProcessed="alreadyProcessed" :scrapStatus="scrapStatus" /> -->
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
import ListModel from '../components/listing/ListModel.vue';
import ScrapStatusModal from '../components/listing/ScrapStatusModal.vue';
import CarResultsModal from '../components/listing/CarResultsModal.vue';
import { useListingFunctions } from '~/composables/useListingFunctions';
import { UInput } from '#components';
const { loadingCars, globalLoading, isStatusModalOpen, scrapStarted,lastScrap, scrapStatus, totalToProcess, alreadyProcessed, title, isModalOpen, selectedCar, relatedCars, resLaCentrale, resAutoScout, searchTerm, selectedColors, selectedModels, currentPage, itemsPerPage, cars, file, isImporting, uploadProgress, importError, importSuccess, handleLogout, getAllCars, getCarComparisons, startScrapping, seeStatus, availableColors, availableModels, filteredCars, pageCount, paginatedCars, openModal, to, handleFileChange, importFile } = useListingFunctions();
definePageMeta({
  middleware: ["auth"]
})
onMounted(() => {
  getAllCars();
});
</script>
