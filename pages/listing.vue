<template>

  <div
    class="min-h-screen min-w-screen  bg-gradient-to-br from-red-50 to-primary-500  items-center justify-center  pt-0">

    <!-- <div v-if="globalLoading" class="min-h-screen min-w-screen  h-full w-full bg-gray-800 opacity-50 place-items-center place-content-center" >
        <Loading />

        <div class="container mx-auto p-4 text-center">
          <p>Loading ... </p>
        </div>

    </div> -->

    <div>
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

          <div class="space-y-6">

            <!-- <div v-if="user" class="flex items-center space-x-4">
            <UAvatar 
              :src="user.user_metadata?.avatar_url" 
              :alt="user.email" 
              size="lg"
            />
            <div>
              <h2 class="text-xl font-semibold">{{ user.email }}</h2>
              <p class="">Connecté depuis {{ new Date(user.last_sign_in_at).toLocaleDateString() }}</p>
            </div>
          </div> -->

            <USeparator label="Process Your Data" />

            <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <UCard 
              v-for="stat in stats" 
              :key="stat.title"
              class="hover:shadow-lg transition-shadow"
            >
              <template #header>
                <div class="flex items-center space-x-2">
                  <UIcon :name="stat.icon" class="w-6 h-6" :class="stat.color" />
                  <span class="font-medium">{{ stat.title }}</span>
                </div>
              </template>
              <p class="text-3xl font-bold">{{ stat.value }}</p>
            </UCard>
          </div> -->
            <div class="container mx-auto px-4 text-center">
              <h1 class="text-2xl font-bold mb-4">Start By Uploading Your Excel File</h1>

              <input type="file" @change="handleFileChange" accept=".xlsx, .xls" class="mb-4 border-b-emerald-300">

              <UButton :disabled="!file" :loading="isImporting" @click="importFile" label="Import" color="primary"
                icon="i-heroicons-arrow-up-tray" />

              <UProgress v-if="isImporting" :value="uploadProgress" max="100" class="mt-4 mb-2" />
              <p v-if="isImporting">Uploading : <span class="font-bold text-amber-600" >{{ uploadProgress }}</span>  %</p>

              <UAlert v-if="importError" :title="importError" color="error" class="mt-2 mb-4" :close="{
                color: 'white',
              }" close-icon="i-heroicons-x-mark" icon="i-heroicons-exclamation-triangle" :ui="{
      icon: 'size-12'
    }"  />
              <UAlert v-if="importSuccess" title="Importation réussie !" color="success" class="mt-2 mb-4" :close="{
                color: 'white',
              }" close-icon="i-heroicons-x-mark" icon="i-heroicons-check-badge" :ui="{
      icon: 'size-12'
    }" />

              <div v-if="importSuccess" class="justify-end flex">
                <UButton v-if="!scrapStarted" label="Start Scrapping" icon="i-heroicons-chevron-double-right"
                  class="justify-center" @click="startScrapping" color="info" />
                <UButton v-if="scrapStarted" label="Check Status" icon="i-heroicons-eye" class=" justify-center"
                  @click="seeStatus" color="warning" />
              </div>
            </div>

          </div>
        </UCard>
      </UContainer>

      <UContainer class="mt-4">
        <UCard :title="title" :ui="{ rounded: 'lg', shadow: 'md' }">


          <div class="text-center">
            <UFormField label="Search" class="">
              <UInput size="lg" v-model="searchTerm" placeholder="Search by Name or Model..."
                icon="i-heroicons-magnifying-glass" class="w-100 justify-center" />
            </UFormField>
          </div>



          <UCollapsible :unmount-on-hide="false" class="flex flex-col gap-2  my-4  place-self-center">
            <div class="text-center cursor-pointer">
              <UIcon size="20" name="i-heroicons-adjustments-horizontal" class="align-sub text-primary-500 " />
              Filters
            </div>

            <template #content>

              <div class="grid  grid-cols-2 gap-4 py-5 text-center ">

                <UFormField label="Color">
                  <USelect v-model="selectedColors" icon="i-heroicons-paint-brush" :items="availableColors" multiple
                    clearable :placeholder="availableColors.length ? 'By color' : 'No Available Color'">
                    <!-- <template #leading="{ modelValue, ui }">
            <UChip
              v-if="modelValue"
              inset
              standalone
              size="3xl"
              :class="ui.itemLeadingChip()"
              :color="getColor(modelValue)"
            />
          </template> -->
                  </USelect>
                </UFormField>

                <UFormField label="Model">
                  <USelect v-model="selectedModels" icon="i-heroicons-swatch" :items="availableModels" multiple
                    clearable :placeholder="availableModels.length ? 'By model' : 'No Available Model'" />

                </UFormField>
              </div>
            </template>
          </UCollapsible>


          <div v-if="loadingCars" class="place-items-center place-content-center my-15">
            <Loading />

            <div class="container mx-auto p-4 text-center">
              <p>Loading Data ... </p>
            </div>
          </div>
          <div v-if="!loadingCars && cars.length == 0" class="text-center my-15">
            <UIcon size="100" name="i-heroicons-circle-stack" class="mx-auto align-sub  text-red-300 " />
            <p class="font-bold"> No Data to Show !</p>
          </div>
          <div v-if="!loadingCars && cars.length != 0" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div v-for="car in paginatedCars" :key="car.id" class="relative">
              <UCard :ui="{ rounded: 'lg', shadow: 'md' }"
                class="shadow-lg hover:shadow-xl transition-shadow duration-300">
                <template #header>

                  <div class="flex items-center justify-between  ">
                    <div class="flex items-center space-x-4">
                      <UAvatar
                        :src="`https://placehold.co/100x100?text=${encodeURIComponent(car.make + ' ' + car.version)}`"
                        :alt="car.name" size="lg" shape="rounded" />
                      <div>
                        <h3 class="text-lg font-semibold">{{ car.make }}</h3>
                        <p class="text-gray-500">{{ car.version }}</p>
                      </div>
                    </div>
                    <UButton label="" icon="i-heroicons-information-circle" @click="openModal(car)" />
                  </div>


                </template>
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                  <img :src="`https://placehold.co/100x100?text=${encodeURIComponent(car.make + ' ' + car.version)}`"
                    alt="Image de la voiture" class="w-full md:w-1/3 h-25 md:h-48  object-cover rounded-lg" />
                  <div class="flex-1">
                    <h2 class="text-xl font-bold">{{ car.make + ' ' + car.version }}</h2>
                    <p class="text-gray-600">{{ car.model }}</p>


                    <p class="text-sm text-gray-500 mt-2">
                      <UIcon size="20" name="i-heroicons-paint-brush" class="mx-auto align-sub  text-primary-500 " />
                      {{ car.color }} •
                      <UIcon size="20" name="i-heroicons-arrow-trending-up"
                        class="mx-auto align-sub  text-primary-500 " />
                      {{ car.mileage }} km
                    </p>


                    <p class="text-sm text-gray-500 mt-2 mb-2">
                      <UIcon size="20" name="i-heroicons-wrench-screwdriver"
                        class="mx-auto align-sub  text-primary-500 " />

                      Fuel Type : {{ car.fuel_type }}
                    </p>
                    <p class="text-sm mt-2 mb-4">
                      <UIcon size="20" name="i-heroicons-calendar-days" class="mx-auto align-sub  text-primary-500 " />

                      Last Update : {{ car.updated_at ?? car.created_at }}
                    </p>
                    <div class="text-center md:text-end ">
                      <UButton label="View Details" icon="i-heroicons-eye" class="w-52 justify-center"
                        @click="openModal(car)" />
                    </div>

                    <!-- Modal For Details -->
                    <UModal v-model:open="isModalOpen" title='Analysis Results'
                      description="We've Searched this car far away !"
                      :ui="{ footer: 'justify-end', rounded: 'lg', shadow: 'lg', overlay: ' opacity-30' }" :close="{
                        color: 'primary',
                        variant: 'outline',
                        class: 'rounded-full'
                      }" close-icon="i-heroicons-x-mark">


                      <template #body>
                        <!-- <Placeholder class="h-48" /> -->
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
                            <UIcon size="20" name="i-heroicons-paint-brush"
                              class="mx-auto align-sub  text-primary-500 " />
                            {{ selectedCar.color }} -•-
                            <UIcon size="20" name="i-heroicons-arrow-trending-up"
                              class="mx-auto align-sub  text-primary-500 " />
                            {{ selectedCar.mileage }} km -•-
                            <UIcon size="20" name="i-heroicons-wrench-screwdriver"
                              class="mx-auto align-sub  text-primary-500 " />
                            {{ selectedCar.fuel_type }} -•-
                          </p>


                          <div class="">

                            <!-- La Centrale -->
                            <UCollapsible :unmount-on-hide="false" class=" my-2   place-self-center">
                              <div class="text-center cursor-pointer mb-3">
                                <UIcon size="20" name="i-heroicons-presentation-chart-bar"
                                  class="align-sub text-warning-500 " />
                                Found <span
                                  class="rounded-4xl border-b-green-500 border-b-4 bg-warning-50 text-warning-500 px-3 mx-2">{{
                                  resLaCentrale }}</span>
                                result(s) from La Centrale
                              </div>
                              <template #content>
                                <div v-if="resLaCentrale.value == '0'" class="text-center py-2">
                                  <UIcon size="20" name="i-heroicons-no-symbol" class="align-sub text-warning-500 " />
                                  <p> No Data Found !</p>
                                </div>
                                <div class="grid grid-cols-1  gap-2">
                                  <div v-for="(voiture, index) in relatedCars">
                                    <UCard v-if="voiture.domain === 'https://www.lacentrale.fr/'" :key="index">
                                      <template #header>
                                        <div class="flex items-center space-x-2">
                                          <UAvatar
                                            :src="`https://placehold.co/100x100?text=${encodeURIComponent(voiture.name)}`"
                                            :alt="voiture.name" size="md" />
                                          <h2 class="text-md font-semibold">{{ voiture.name }} {{ voiture.model }}</h2>
                                        </div>
                                      </template>

                                      <div class="flex space-x-3">
                                        <img
                                          :src="voiture.image.length == 0 ? `https://placehold.co/100x100?text=${encodeURIComponent(voiture.name)}` : voiture.image"
                                          alt="Image de la voiture" class="w-full  h-30 object-cover rounded-lg"
                                          v-link="voiture.link" />
                                        <div class="text-sm text-start self-center w-full">

                                          <ul>
                                            <li>
                                              <UIcon size="20" name="i-heroicons-user-group"
                                                class="mx-auto align-sub  text-rose-500 " />
                                              {{ voiture.deal_type }}
                                            </li>
                                            <li>
                                              <UIcon size="20" name="i-heroicons-arrow-trending-up"
                                                class="mx-auto align-sub  text-rose-500 " />
                                              {{ voiture.mileage }} km
                                            </li>
                                            <li>
                                              <UIcon size="20" name="i-heroicons-currency-dollar"
                                                class="mx-auto align-sub  text-rose-500 " />
                                              {{ voiture.price }} €
                                            </li>
                                            <li>
                                              <UIcon size="20" name="i-heroicons-wrench-screwdriver"
                                                class="mx-auto align-sub  text-rose-500 " />
                                              {{ voiture.fuel_type }} -- {{ voiture.boite_de_vitesse }}
                                            </li>
                                          </ul>


                                        </div>
                                      </div>
                                      <div class="flex justify-between mt-2">
                                        <p>
                                          {{ voiture.car_metadata }}
                                        </p>
                                        <a :href="voiture.link" target="_blank" rel="noopener noreferrer">
                                          <UIcon size="15" name="i-heroicons-link"
                                            class="mx-auto align-sub text-rose-500 " /> Link
                                        </a>
                                      </div>
                                      <template #footer>




                                        <div class=" place-items-center">

                                          <p class="mb-4">Matching ({{ voiture.matching_percentage }}%) : </p>

                                          <ProgressBar :level="voiture.matching_percentage" />

                                          <!-- {{ voiture.matching_percentage }} % -->
                                        </div>

                                      </template>
                                    </UCard>
                                  </div>
                                </div>
                              </template>
                            </UCollapsible>

                            <!-- AutoScout24 -->
                            <UCollapsible :unmount-on-hide="false" class=" my-2   place-self-center">
                              <div class="text-center cursor-pointer mb-3">
                                <UIcon size="20" name="i-heroicons-presentation-chart-bar"
                                  class="align-sub text-blue-500 " />
                                Found <span
                                  class="rounded-4xl border-b-green-500 border-b-4 bg-blue-50 text-blue-500 px-3 mx-2">
                                  {{ resAutoScout }}
                                </span>
                                result(s) from AutoScout24
                              </div>
                              <template #content>
                                <div v-if="resAutoScout.value == '0'" class="text-center py-2">
                                  <UIcon size="20" name="i-heroicons-no-symbol" class="align-sub text-blue-500 " />
                                  <p> No Data Found !</p>
                                </div>
                                <div class="grid grid-cols-1  gap-2">
                                  <div v-for="(voiture, index) in relatedCars">
                                    <UCard v-if="voiture.domain === 'https://www.autoscout24.fr/'" :key="index">
                                      <template #header>
                                        <div class="flex items-center space-x-2">
                                          <UAvatar
                                            :src="`https://placehold.co/100x100?text=${encodeURIComponent(voiture.name)}`"
                                            :alt="voiture.name" size="md" />
                                          <h2 class="text-md font-semibold">{{ voiture.name }} {{ voiture.model }}</h2>
                                        </div>
                                      </template>

                                      <div class="flex space-x-3">
                                        <img
                                          :src="voiture.image.length == 0 ? `https://placehold.co/100x100?text=${encodeURIComponent(voiture.name)}` : voiture.image"
                                          alt="Image de la voiture" class="w-full  h-30 object-cover rounded-lg"
                                          v-link="voiture.link" />
                                        <div class="text-sm text-start self-center w-full">

                                          <ul>
                                            <li>
                                              <UIcon size="20" name="i-heroicons-user-group"
                                                class="mx-auto align-sub  text-rose-500 " />
                                              {{ voiture.deal_type }}
                                            </li>
                                            <li>
                                              <UIcon size="20" name="i-heroicons-arrow-trending-up"
                                                class="mx-auto align-sub  text-rose-500 " />
                                              {{ voiture.mileage }} km
                                            </li>
                                            <li>
                                              <UIcon size="20" name="i-heroicons-currency-dollar"
                                                class="mx-auto align-sub  text-rose-500 " />
                                              {{ voiture.price }} €
                                            </li>
                                            <li>
                                              <UIcon size="20" name="i-heroicons-wrench-screwdriver"
                                                class="mx-auto align-sub  text-rose-500 " />
                                              {{ voiture.fuel_type }} -- {{ voiture.boite_de_vitesse }}
                                            </li>
                                          </ul>


                                        </div>
                                      </div>
                                      <div class="flex justify-between mt-2">
                                        <p>
                                          {{ voiture.car_metadata }}
                                        </p>
                                        <a :href="voiture.link" target="_blank" rel="noopener noreferrer">
                                          <UIcon size="15" name="i-heroicons-link"
                                            class="mx-auto align-sub text-rose-500 " /> Link
                                        </a>
                                      </div>
                                      <template #footer>




                                        <div class=" place-items-center">

                                          <p class="mb-4">Matching ({{ voiture.matching_percentage }}%) : </p>

                                          <ProgressBar :level="voiture.matching_percentage" />

                                          <!-- {{ voiture.matching_percentage }} % -->
                                        </div>

                                      </template>
                                    </UCard>
                                  </div>

                                </div>
                              </template>
                            </UCollapsible>


                          </div>
                        </div>
                      </template>
                      <template #footer>
                        <div class="justify-center">
                          <UButton class="max-w-40 min-w-32 justify-center" label="Great !" color="primary"
                            variant="outline" @click="isModalOpen = false" />
                        </div>
                      </template>
                    </UModal>


                    <!--  -->
                    <!-- Modal For Status -->
                    <UModal v-model:open="isStatusModalOpen" title='Scrapping Status'
                      description="We're processing your data"
                      :ui="{ footer: 'justify-end', rounded: 'lg', shadow: 'lg', overlay: ' opacity-30' }" :close="{
                        color: 'primary',
                        variant: 'outline',
                        class: 'rounded-full'
                      }" close-icon="i-heroicons-x-mark">


                      <template #body>
                        <!-- <Placeholder class="h-48" /> -->
                        <div class="grid grid-cols-1 gap-4">
                          <div v-if="totalToProcess != alreadyProcessed" class="text-center">

                            <!-- <img src="./../assets/imgs/load.gif" alt="Description de l'image GIF"> -->
                            <img src="../assets/imgs/load.gif" alt="" srcset="" class="w-50 place-self-center">

                            <h2 class="text-xl font-semibold">Processing : {{ scrapStatus }}</h2>
                          </div>
                          <div v-else class="text-center">

                            <!-- <img src="./../assets/imgs/load.gif" alt="Description de l'image GIF"> -->
                            <UIcon size="100" name="i-heroicons-check-badge"
                              class="mx-auto align-sub  text-primary-500 " />

                            <h2 class="text-xl font-semibold">Done : {{ scrapStatus }}</h2>
                          </div>

                          <p class="text-sm text-start"> -•-
                            <UIcon size="20" name="i-heroicons-calendar-days"
                              class="mx-auto align-sub  text-primary-500 " />
                            Total to Process : {{ totalToProcess }} -•-
                          </p>
                          <p class="text-sm text-start"> -•-
                            <UIcon size="20" name="i-heroicons-calendar-days"
                              class="mx-auto align-sub  text-primary-500 " />
                            Already Processed : {{ alreadyProcessed }} -•-
                          </p>
                          <p class="text-sm text-start"> -•-
                            <UIcon size="20" name="i-heroicons-calendar-days"
                              class="mx-auto align-sub  text-primary-500 " />
                            Remaining : {{ totalToProcess - alreadyProcessed }} -•-
                          </p>

                        </div>
                      </template>
                      <template #footer>
                        <div class="justify-center">
                          <UButton class="max-w-40 min-w-32 justify-center" label="Close" color="primary"
                            variant="outline" @click="isStatusModalOpen = false" />
                        </div>
                      </template>
                    </UModal>

                  </div>


                </div>

              </UCard>
            </div>
          </div>

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

// 

import axios from 'axios';
import Loading from '../components/Loading.vue';
import ProgressBar from '../components/ProgressBar.vue';
const router = useRouter();
const loadingCars = ref(false);
const globalLoading = ref(false);
const isStatusModalOpen = ref(false);
const scrapStarted = ref(false);
const scrapStatus = ref('');
const totalToProcess = ref(0);
const alreadyProcessed = ref(0);
const toast = useToast()

function showToast(title, desc, icon, colore) {
  toast.add({
    title: title,
    description: desc,
    icon: icon,
    closeIcon: 'i-heroicons-x-mark',
    close: {
      color: colore,
      variant: 'outline',
      class: 'rounded-full'
    }
  })
}

const handleLogout = async () => {

  // Supprimer les informations de session

  sessionStorage.removeItem('AccessToken');
  sessionStorage.removeItem('RefreshToken');
  sessionStorage.removeItem('ExpiresAt');

  router.push('/login'); // Rediriger vers la page de login
};

// Vérification de l'état de connexion au chargement de la page
onMounted(() => {
  const accessToken = sessionStorage.getItem('AccessToken');
  if (!accessToken) {
    router.push('/login');
  }
  getAllCars();
});

async function getAllCars() {
  loadingCars.value = true;
  const accessToken = sessionStorage.getItem('AccessToken');
  const refToken = sessionStorage.getItem('RefreshToken');
  try {
    const response = await axios.get("http://157.180.69.73:8000/get_all_cars",
      {
        params: {
          access_token: accessToken,
          refresh_token: refToken,
          // page : 4,
          limit : 1000
        },
        headers: {
          "accept": "application/json",
          "content-type": "application/x-www-form-urlencoded",
        },
      },
    );

    // 
    console.log('Get Cars:', response.data);
    cars.value = response.data.details.data;
    // Stocker l'access token dans la session du navigateur
    sessionStorage.setItem('AccessToken', response.data.session.session.access_token);
    sessionStorage.setItem('RefreshToken', response.data.session.session.refresh_token);
    sessionStorage.setItem('ExpiresAt', response.data.session.session.expires_at);

  } catch (err) {
    console.error('Erreur de requete:', err);
  } finally {
    // 
    loadingCars.value = false;
  }

}

async function getCarComparisons(id) {
  globalLoading.value = true;
  const result = ref([]);
  const accessToken = sessionStorage.getItem('AccessToken');
  const refToken = sessionStorage.getItem('RefreshToken');
  try {
    const response = await axios.get("http://157.180.69.73:8000/get_car_comparisons",
      {
        params: {
          access_token: accessToken,
          refresh_token: refToken,
          car_id: id
        },
        headers: {
          "accept": "application/json",
          "content-type": "application/x-www-form-urlencoded",
        },
      },
    );

    // 
    // console.log('Get Car Comparisons:', response.data);
    result.value = response.data.details.data;
    // Stocker l'access token dans la session du navigateur
    sessionStorage.setItem('AccessToken', response.data.session.session.access_token);
    sessionStorage.setItem('RefreshToken', response.data.session.session.refresh_token);
    sessionStorage.setItem('ExpiresAt', response.data.session.session.expires_at);

  } catch (err) {
    console.error('Erreur de requete:', err);
  } finally {
    // 
    globalLoading.value = false;
    return result.value;
  }

}

// 
async function startScrapping() {
  scrapStarted.value = false;
  const accessToken = sessionStorage.getItem('AccessToken');
  const refToken = sessionStorage.getItem('RefreshToken');
  try {
    const response = await axios.get("http://157.180.69.73:8000/start_scraping",
      {
        params: {
          access_token: accessToken,
          refresh_token: refToken
        },
        headers: {
          "accept": "application/json",
          "content-type": "application/x-www-form-urlencoded",
        },
      },
    );

    // 
    console.log('Start Scraping :', response.data);
    scrapStarted.value = true;
    showToast('Success', "Successfully Started Scrapping. You can check Status anytime by pressing on the button", 'i-heroicons-check-badge', 'success');
    // Stocker l'access token dans la session du navigateur
    sessionStorage.setItem('AccessToken', response.data.session.session.access_token);
    sessionStorage.setItem('RefreshToken', response.data.session.session.refresh_token);
    sessionStorage.setItem('ExpiresAt', response.data.session.session.expires_at);

  } catch (err) {
    console.error('Erreur de requete:', err);
    showToast('Error !', "Failled to Start Scraping, maybe server error !", 'i-heroicons-exclamation-triangle', 'error');
  } finally {
    // 
  }

}


async function seeStatus() {


  const accessToken = sessionStorage.getItem('AccessToken');
  const refToken = sessionStorage.getItem('RefreshToken');
  try {
    const response = await axios.get("http://157.180.69.73:8000/scrape_status",
      {
        params: {
          access_token: accessToken,
          refresh_token: refToken
        },
        headers: {
          "accept": "application/json",
          "content-type": "application/x-www-form-urlencoded",
        },
      },
    );

    // 
    console.log('Scraping Status :', response.data);
    totalToProcess.value = response.data.details.data[0].total_running;
    alreadyProcessed.value = response.data.details.data[0].total_completed;
    scrapStatus.value = (alreadyProcessed.value * 100 / totalToProcess.value) + ' %';
    // Stocker l'access token dans la session du navigateur
    sessionStorage.setItem('AccessToken', response.data.session.session.access_token);
    sessionStorage.setItem('RefreshToken', response.data.session.session.refresh_token);
    sessionStorage.setItem('ExpiresAt', response.data.session.session.expires_at);

  } catch (err) {
    console.error('Erreur de requete:', err);
  } finally {
    // 
  }

  isStatusModalOpen.value = true;

}


// 

const title = 'Cars Listing';
const isModalOpen = ref(false);
const selectedCar = ref(null);
const relatedCars = ref([]);
const resLaCentrale = ref('');
const resAutoScout = ref('');
const searchTerm = ref('');
const selectedColors = ref([]);
const selectedModels = ref([]);
const currentPage = ref(1);
const itemsPerPage = 10;
const cars = ref([])


const availableColors = computed(() => cars.value ? [...new Set(cars.value.map(car => car.color))].filter(Boolean).map(color => ({ label: color, value: color })) : ['Rouge', 'Bleu']);
const availableModels = computed(() => cars.value ? [...new Set(cars.value.map(car => car.make))].filter(Boolean).map(model => ({ label: model, value: model })) : []);
const filteredCars = computed(() => {
  return cars.value.filter(car => {
    const searchMatch = searchTerm.value ?
      car.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      car.model.toLowerCase().includes(searchTerm.value.toLowerCase()) :
      true;

    const colorMatch = selectedColors.value.length ? selectedColors.value.includes(car.color) : true;
    const modelMatch = selectedModels.value.length ? selectedModels.value.includes(car.make) : true;

    return searchMatch && colorMatch && modelMatch;
  });
});
const pageCount = computed(() => Math.ceil(filteredCars.value.length / itemsPerPage));
const paginatedCars = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredCars.value.slice(start, end);
});
const openModal = async (car) => {
  selectedCar.value = car;
  const result = await getCarComparisons(car.id)
  console.log('result ', result);
  // Aller retrouver ls comparaisons
  relatedCars.value = result;
  resLaCentrale.value = computed(() => { return relatedCars.value.filter(vtr => vtr.domain === 'https://www.lacentrale.fr/').length ?? 0 });
  resAutoScout.value = computed(() => { return relatedCars.value.filter(vtr => vtr.domain === 'https://www.autoscout24.fr/').length ?? 0 });
  // 
  isModalOpen.value = true;
  // open = true;
};
function to(page) {
  return {
    query: {
      page
    },
    hash: '#with-links'
  }
}



// Excel File


// import * as XLSX from 'xlsx';

const file = ref(null);
const isImporting = ref(false);
const uploadProgress = ref(0);
const importError = ref(null);
const importSuccess = ref(false);

const handleFileChange = (event) => {
  file.value = event.target.files[0];
};

const importFile = async () => {
  if (!file.value) return;

  isImporting.value = true;
  uploadProgress.value = 0;
  importError.value = null;
  importSuccess.value = false; // 

  const formData = new FormData();
  formData.append('file', file.value);

  try {
    const response = await axios.post('http://157.180.69.73:8000/upload', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      onUploadProgress: (progressEvent) => {
        uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
      },
    });

    importSuccess.value = true;
    console.log('Upload successful:', response.data);
    // Traitez la réponse de votre API ici (par exemple, affichez un message)
  } catch (error) {
    importError.value = 'Error while uploading your file !';
    console.error('Upload error:', error);
    // Gérez l'erreur ici (par exemple, affichez un message d'erreur plus détaillé)
  } finally {
    isImporting.value = false;
    // Réinitialiser la progression après la fin (succès ou échec)
    // setTimeout(() => {
    //   this.uploadProgress = -1;
    // }, 2000);
  }
}

/* try {
  const reader = new FileReader();

  reader.onload = async (e) => {
    const workbook = XLSX.read(e.target.result, { type: 'array' });
    const sheetName = workbook.SheetNames[0];
    const worksheet = workbook.Sheets[sheetName];
    const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 }); // header: 1 pour obtenir un tableau de tableaux

    // Envoi des données ligne par ligne à l'API
    for (let i = 0; i < jsonData.length; i++) {
      const rowData = jsonData[i];
      try {
        const { error } = await useFetch('/api/import-data', { // Remplacez par l'URL de votre API
          method: 'POST',
          body: { row: rowData },
        }).json();

        if (error.value) {
          console.error('Erreur lors de l\'envoi de la ligne:', rowData, error.value);
          importError.value = 'Erreur lors de l\'importation. Veuillez consulter la console.';
          break; // Arrêter l'importation en cas d'erreur
        }

        // Calculer et mettre à jour la progression
        uploadProgress.value = Math.round(((i + 1) / jsonData.length) * 100);
      } catch (apiError) {
        console.error('Erreur lors de l\'appel API:', apiError);
        importError.value = 'Erreur lors de la communication avec le serveur.';
        break;
      }
    }

    if (!importError.value) {
      importSuccess.value = true;
    }

    isImporting.value = false;
  };

  reader.onerror = (error) => {
    console.error('Erreur lors de la lecture du fichier:', error);
    importError.value = 'Error while reading excel file.';
    isImporting.value = false;
  };

  reader.readAsArrayBuffer(file.value);
} catch (error) {
  console.error('Erreur inattendue:', error);
  importError.value = 'Unexpected Error.';
  isImporting.value = false;
} */


</script>

<style scoped>
/* Styles spécifiques à cette page si nécessaire */

.ui-modal-overlay {
  /* Remplacez par la classe que vous avez trouvée */
  background-color: transparent !important;
}
</style>
