<template>

  <div
    class="min-h-screen min-w-screen  bg-gradient-to-br from-red-50 to-primary-500  items-center justify-center  pt-0">



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

          <UDivider label="Statistiques" />

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
          <div class="container mx-auto p-4 text-center">
            <h1 class="text-2xl font-bold mb-4">Start By Uploading Your Excel File</h1>

            <input type="file" @change="handleFileChange" accept=".xlsx, .xls" class="mb-4 border-b-emerald-300">

            <UButton :disabled="!file" :loading="isImporting" @click="importFile" label="Import" color="primary"
              icon="i-heroicons-arrow-up-tray" />

            <UProgress v-if="isImporting" :value="uploadProgress" class="mt-4" />

            <UAlert v-if="importError" :title="importError" color="red" class="mt-4" />
            <UAlert v-if="importSuccess" title="Importation réussie !" color="green" class="mt-4" />
          </div>

        </div>
      </UCard>
    </UContainer>


    <UContainer class="mt-4">
      <UCard :title="title" :ui="{ rounded: 'lg', shadow: 'md' }">


        <div class="text-center">
          <UFormGroup label="Recherche" class="">
            <UInput size="lg" v-model="searchTerm" placeholder="Search by Name or Model..."
              icon="i-heroicons-magnifying-glass" class="w-100 justify-center" />
          </UFormGroup>
        </div>


        <!-- <UAccordion class="place-items-center" :items="[
          {
            label: 'Filters',
            icon: 'i-lucide-list-filter-plus'
          }
        ]">
        <template #body="{ item }">
          This is the {{ item.label }} panel.
        </template>
      </UAccordion> -->


        <UCollapsible :unmount-on-hide="false" class="flex flex-col gap-2  my-4  place-self-center">
          <div class="text-center cursor-pointer">
            <UIcon size="20" name="i-heroicons-adjustments-horizontal" class="align-sub text-primary-500 " />
            Filters
          </div>

          <template #content>

            <div class="grid  grid-cols-2 gap-4 py-5 text-center ">

              <UFormGroup label="Couleur">
                <USelect v-model="selectedColors" icon="i-heroicons-paint-brush" :items="availableColors" multiple
                  :clearable :placeholder="availableColors.length ? 'By color' : 'No Available Color'">
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
              </UFormGroup>

              <UFormGroup label="Modèle">
                <USelect v-model="selectedModels" icon="i-heroicons-swatch" :items="availableModels" multiple :clearable
                  :placeholder="availableModels.length ? 'By model' : 'No Available Model'" />

              </UFormGroup>
            </div>
          </template>
        </UCollapsible>


        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
          <div v-for="car in paginatedCars" :key="car.id" class="relative">
            <UCard :ui="{ rounded: 'lg', shadow: 'md' }"
              class="shadow-lg hover:shadow-xl transition-shadow duration-300">
              <template #header>

                <div class="flex items-center justify-between  ">
                  <div class="flex items-center space-x-4">
                    <UAvatar :src="car.image" :alt="car.name" size="lg" shape="rounded" />
                    <div>
                      <h3 class="text-lg font-semibold">{{ car.name }}</h3>
                      <p class="text-gray-500">{{ car.model }}</p>
                    </div>
                  </div>
                  <UButton label="" icon="i-heroicons-information-circle" @click="openModal(car)" />
                </div>


              </template>
              <div class="flex flex-col md:flex-row gap-4 mb-4">
                <img :src="car.image" alt="Image de la voiture" class="w-full md:w-1/3 h-48 object-cover rounded-lg" />
                <div class="flex-1">
                  <h2 class="text-xl font-bold">{{ car.name }}</h2>
                  <p class="text-gray-600">{{ car.model }}</p>


                  <p class="text-sm text-gray-500 mt-2">
                    <UIcon size="20" name="i-heroicons-calendar-days" class="mx-auto align-sub  text-primary-500 " />
                    {{ car.year }} •
                    <UIcon size="20" name="i-heroicons-paint-brush" class="mx-auto align-sub  text-primary-500 " />
                    {{ car.color }} •
                    <UIcon size="20" name="i-heroicons-adjustments-horizontal"
                      class="mx-auto align-sub  text-primary-500 " />


                    {{ car.mileage }} km
                  </p>

                  <p class="text-lg font-semibold mt-2">
                    <UIcon size="20" name="i-heroicons-currency-dollar" class="mx-auto align-sub  text-primary-500 " />

                    {{ car.price }} €
                  </p>
                  <p class="text-sm text-gray-500 mt-2 mb-3">
                    <UIcon size="20" name="i-heroicons-wrench-screwdriver"
                      class="mx-auto align-sub  text-primary-500 " />

                    Transmission : {{ car.transmission }}
                  </p>
                  <div class="text-center md:text-end ">
                    <UButton label="View Details" icon="i-heroicons-eye" class="w-52 justify-center"
                      @click="openModal(car)" />
                  </div>

                  <!-- Modal For Details -->
                  <UModal v-model:open="isModalOpen" title='About The Car'
                    description="We've Searched this car far away !" :ui="{ footer: 'justify-end' }" :close="{
                      color: 'primary',
                      variant: 'outline',
                      class: 'rounded-full'
                    }" close-icon="i-heroicons-x-mark">

                    <template #body>
                      <!-- <Placeholder class="h-48" /> -->
                      <div class="grid grid-cols-1 gap-4">
                        <div>
                          <img :src="selectedCar.image" :alt="selectedCar.name" class="rounded-md w-full" />
                        </div>
                        <div class="text-center">
                          <h3 class="text-lg font-semibold">{{ selectedCar.name }}</h3>
                          <p class="text-gray-500">{{ selectedCar.model }}</p>
                        </div>
                        <div>
                          <p class="text-sm text-gray-700 mb-2">Détails :</p>
                          <ul class="list-disc pl-5 text-sm">
                            <li>Année : {{ selectedCar.year }}</li>
                            <li>Couleur : {{ selectedCar.color }}</li>
                            <li>Kilométrage : {{ selectedCar.mileage }} km</li>
                            <li>Transmission : {{ selectedCar.transmission }}</li>
                            <li v-if="selectedCar.fuel">Carburant : {{ selectedCar.fuel }}</li>
                            <li v-if="selectedCar.engine">Moteur : {{ selectedCar.engine }}</li>
                            <li v-if="selectedCar.doors">Nombre de portes : {{ selectedCar.doors }}</li>
                            <li v-if="selectedCar.seats">Nombre de places : {{ selectedCar.seats }}</li>
                            <li v-if="selectedCar.price">Prix : {{ selectedCar.price }} €</li>
                            <li v-if="selectedCar.description">Description : {{ selectedCar.description }}</li>
                          </ul>
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

                </div>


              </div>

            </UCard>
          </div>
        </div>



        <!-- <UPagination
        v-model="currentPage"
        :page-count="pageCount"
        :total="filteredCars.length"
        :per-page="itemsPerPage"
        :align="center"
        class="mt-6"
      /> -->

        <div class="justify-center">
          <UPagination v-model:page="currentPage" :total="filteredCars.length" :to="to" :sibling-count="1" show-edges
            :per-page="itemsPerPage" :align="center" firstIcon="i-heroicons-chevron-double-left"
            prevIcon="i-heroicons-chevron-left" nextIcon="i-heroicons-chevron-right"
            lastIcon="i-heroicons-chevron-double-right" class="mt-6  place-self-center" />
        </div>


      </UCard>


    </UContainer>

  </div>
</template>

<script setup>

// 

import axios from 'axios';
const runtimeConfig = useRuntimeConfig();

const supabaseUrl = runtimeConfig.public.supabaseUrl;
const supabaseAnonKey = runtimeConfig.public.supabaseKey;
const router = useRouter();

const handleLogout = async () => {
  const refreshToken = sessionStorage.getItem('supabaseRefreshToken');

  if (refreshToken) {
    try {
      await axios.post(
        supabaseUrl + '/auth/v1/token?grant_type=refresh_token',
        {
          refresh_token: refreshToken,
        },
        {
          headers: {
            'Content-Type': 'application/json',
            'apikey': supabaseAnonKey,
          },
        }
      );
      console.log('Déconnexion réussie côté Supabase.');
    } catch (error) {
      console.error('Erreur lors de la déconnexion Supabase:', error);
      // Gérer l'erreur de déconnexion Supabase si nécessaire
    }
  }

  // Supprimer les informations de session
  sessionStorage.removeItem('supabaseAccessToken');
  sessionStorage.removeItem('supabaseRefreshToken');
  sessionStorage.removeItem('supabaseExpiresAt');
  sessionStorage.removeItem('user');

  router.push('/login'); // Rediriger vers la page de login
};

const user = ref(null);

// Vérification de l'état de connexion au chargement de la page
onMounted(() => {
  const accessToken = sessionStorage.getItem('supabaseAccessToken');
  if (!accessToken) {
    router.push('/login');
  }

  //  user.value = sessionStorage.getItem('user');

});


// 


// 




console.log(user);
const title = 'Cars Listing';
const isModalOpen = ref(false);
const selectedCar = ref(null);
const searchTerm = ref('');
const selectedColors = ref([]);
const selectedModels = ref([]);
const currentPage = ref(1);
const itemsPerPage = 4;

// const { isMobile } = useDisplay();

const cars = ref([])
// onMounted( () => {
cars.value = [
  { color2: 'neutral', id: 1, name: 'Peugeot 308', model: 'Berline', year: 2021, color: 'Bleu', mileage: 20000, transmission: 'Automatique', fuel: 'Essence', engine: '1.2 PureTech', doors: 5, seats: 5, price: 22000, image: 'https://placehold.co/600x400/AAAAAA/FFFFFF/?text=Peugeot+308', description: 'Superbe berline avec toutes options.' },
  { color2: 'neutral', id: 2, name: 'Renault Clio', model: 'Citadine', year: 2020, color: 'Rouge', mileage: 35000, transmission: 'Manuelle', fuel: 'Essence', engine: '1.0 TCe', doors: 5, seats: 5, price: 16000, image: 'https://placehold.co/600x400/B0E0E6/000000/?text=Renault+Clio', description: 'Idéale pour la ville, économique et fiable.' },
  { color2: 'neutral', id: 3, name: 'BMW X5', model: 'SUV', year: 2022, color: 'Noir', mileage: 15000, transmission: 'Automatique', fuel: 'Diesel', engine: '3.0d', doors: 5, seats: 7, price: 65000, image: 'https://placehold.co/600x400/808080/FFFFFF/?text=BMW+X5', description: 'SUV de luxe, spacieux et puissant.' },
  { color2: 'primary', id: 4, name: 'Citroën C3', model: 'Citadine', year: 2019, color: 'Blanc', mileage: 42000, transmission: 'Manuelle', fuel: 'Essence', engine: '1.2 PureTech', doors: 5, seats: 5, price: 12500, image: 'https://placehold.co/600x400/F0F8FF/000000/?text=Citroen+C3', description: 'Confortable et pratique pour la vie quotidienne.' },
  { color2: 'neutral', id: 5, name: 'Mercedes-Benz Classe A', model: 'Berline', year: 2023, color: 'Gris', mileage: 8000, transmission: 'Automatique', fuel: 'Essence', engine: '1.3 Turbo', doors: 5, seats: 5, price: 38000, image: 'https://placehold.co/600x400/D3D3D3/000000/?text=Mercedes+A', description: 'Berline premium, élégante et technologique.' },
  { color2: 'neutral', id: 6, name: 'Volkswagen Golf', model: 'Berline', year: 2020, color: 'Bleu', mileage: 28000, transmission: 'Manuelle', fuel: 'Essence', engine: '1.5 TSI', doors: 5, seats: 5, price: 24000, image: 'https://placehold.co/600x400/ADD8E6/000000/?text=Volkswagen+Golf', description: 'La référence des berlines compactes.' },
  { color2: 'neutral', id: 7, name: 'Nissan Qashqai', model: 'SUV', year: 2021, color: 'Noir', mileage: 22000, transmission: 'Automatique', fuel: 'Essence', engine: '1.3 DIG-T', doors: 5, seats: 5, price: 27000, image: 'https://placehold.co/600x400/000000/FFFFFF/?text=Nissan+Qashqai', description: 'SUV familial, confortable et bien équipé.' },
  { color2: 'neutral', id: 8, name: 'Fiat 500', model: 'Citadine', year: 2018, color: 'Rouge', mileage: 50000, transmission: 'Manuelle', fuel: 'Essence', engine: '1.2', doors: 3, seats: 4, price: 11000, image: 'https://placehold.co/600x400/FF0000/FFFFFF/?text=Fiat+500', description: 'Citadine iconique, parfaite pour la ville.' },
  { color2: 'neutral', id: 9, name: 'Audi A3', model: 'Berline', year: 2022, color: 'Gris', mileage: 12000, transmission: 'Automatique', fuel: 'Essence', engine: '1.5 TFSI', doors: 5, seats: 5, price: 35000, image: 'https://placehold.co/600x400/808080/FFFFFF/?text=Audi+A3', description: 'Berline compacte premium, dynamique et élégante.' },
  { color2: 'neutral', id: 10, name: 'Opel Corsa', model: 'Citadine', year: 2020, color: 'Blanc', mileage: 38000, transmission: 'Manuelle', fuel: 'Essence', engine: '1.2', doors: 5, seats: 5, price: 13000, image: 'https://placehold.co/600x400/FFFFFF/000000/?text=Opel+Corsa', description: 'Citadine polyvalente et économique.' },
  { color2: 'neutral', id: 11, name: 'Toyota RAV4', model: 'SUV', year: 2023, color: 'Bleu', mileage: 5000, transmission: 'Automatique', fuel: 'Hybride', engine: '2.5 Hybrid', doors: 5, seats: 5, price: 42000, image: 'https://placehold.co/600x400/0000FF/FFFFFF/?text=Toyota+RAV4', description: 'SUV hybride, fiable et spacieux.' },
  { color2: 'neutral', id: 12, name: 'Hyundai Tucson', model: 'SUV', year: 2021, color: 'Noir', mileage: 18000, transmission: 'Automatique', fuel: 'Essence', engine: '1.6 T-GDI', doors: 5, seats: 5, price: 29000, image: 'https://placehold.co/600x400/2F4F4F/FFFFFF/?text=Hyundai+Tucson', description: 'SUV moderne avec un design audacieux.' },
  { color2: 'neutral', id: 13, name: 'Skoda Octavia', model: 'Berline', year: 2019, color: 'Gris', mileage: 45000, transmission: 'Manuelle', fuel: 'Essence', engine: '1.5 TSI', doors: 5, seats: 5, price: 21000, image: 'https://placehold.co/600x400/A9A9A9/000000/?text=Skoda+Octavia', description: 'Berline familiale, spacieuse et pratique.' },
  { color2: 'neutral', id: 14, name: 'Mazda CX-5', model: 'SUV', year: 2020, color: 'Rouge', mileage: 32000, transmission: 'Automatique', fuel: 'Essence', engine: '2.0 Skyactiv-G', doors: 5, seats: 5, price: 31000, image: 'https://placehold.co/600x400/DC143C/FFFFFF/?text=Mazda+CX-5', description: 'SUV élégant avec une conduite agréable.' },
  { color2: 'neutral', id: 15, name: 'Kia Sportage', model: 'SUV', year: 2022, color: 'Blanc', mileage: 10000, transmission: 'Automatique', fuel: 'Hybride', engine: '1.6 T-GDI Hybrid', doors: 5, seats: 5, price: 36000, image: 'https://placehold.co/600x400/FAEBD7/000000/?text=Kia+Sportage', description: 'SUV hybride, moderne et bien équipé.' },
  { color2: 'neutral', id: 16, name: 'Ford Focus', model: 'Berline', year: 2018, color: 'Bleu', mileage: 60000, transmission: 'Manuelle', fuel: 'Essence', engine: '1.0 EcoBoost', doors: 5, seats: 5, price: 15000, image: 'https://placehold.co/600x400/6495ED/FFFFFF/?text=Ford+Focus', description: 'Berline compacte, agréable à conduire.' },
  { color2: 'neutral', id: 17, name: 'Suzuki Swift', model: 'Citadine', year: 2021, color: 'Gris', mileage: 19000, transmission: 'Manuelle', fuel: 'Essence', engine: '1.2 Dualjet', doors: 5, seats: 5, price: 14000, image: 'https://placehold.co/600x400/778899/FFFFFF/?text=Suzuki+Swift', description: 'Citadine légère et économique.' },
  { color2: 'neutral', id: 18, name: 'Dacia Duster', model: 'SUV', year: 2019, color: 'Brun', mileage: 55000, transmission: 'Manuelle', fuel: 'Essence', engine: '1.6', doors: 5, seats: 5, price: 17000, image: 'https://placehold.co/600x400/A0522D/FFFFFF/?text=Dacia+Duster', description: 'SUV abordable et robuste.' },
  { color2: 'neutral', id: 19, name: 'Peugeot 208', model: 'Citadine', year: 2022, color: 'Jaune', mileage: 7000, transmission: 'Automatique', fuel: 'Essence', engine: '1.2 PureTech', doors: 5, seats: 5, price: 20000, image: 'https://placehold.co/600x400/FFFF00/000000/?text=Peugeot+208', description: 'Citadine moderne et dynamique.' },
  { color2: 'neutral', id: 20, name: 'Renault Mégane', model: 'Berline', year: 2020, color: 'Noir', mileage: 30000, transmission: 'Manuelle', fuel: 'Diesel', engine: '1.5 dCi', doors: 5, seats: 5, price: 19000, image: 'https://placehold.co/600x400/4682B4/FFFFFF/?text=Renault+Megane', description: 'Berline familiale confortable et économique.' },
];
// })


const availableColors = computed(() => cars.value ? [...new Set(cars.value.map(car => car.color))].filter(Boolean).map(color => ({ label: color, value: color })) : ['Rouge', 'Bleu']);
const availableModels = computed(() => cars.value ? [...new Set(cars.value.map(car => car.model))].filter(Boolean).map(model => ({ label: model, value: model })) : []);

// function getColor(value) {
//   // return 'neutral'
//   return cars.value.find(item => item.id === value)?.color2
// }
const filteredCars = computed(() => {
  return cars.value.filter(car => {
    const searchMatch = searchTerm.value ?
      car.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      car.model.toLowerCase().includes(searchTerm.value.toLowerCase()) :
      true;

    const colorMatch = selectedColors.value.length ? selectedColors.value.includes(car.color) : true;
    const modelMatch = selectedModels.value.length ? selectedModels.value.includes(car.model) : true;

    return searchMatch && colorMatch && modelMatch;
  });
});

const pageCount = computed(() => Math.ceil(filteredCars.value.length / itemsPerPage));

const paginatedCars = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredCars.value.slice(start, end);
});

const openModal = (car) => {
  selectedCar.value = car;
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


import * as XLSX from 'xlsx';

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
  importSuccess.value = false;

  try {
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
      importError.value = 'Erreur lors de la lecture du fichier Excel.';
      isImporting.value = false;
    };

    reader.readAsArrayBuffer(file.value);
  } catch (error) {
    console.error('Erreur inattendue:', error);
    importError.value = 'Une erreur inattendue s\'est produite.';
    isImporting.value = false;
  }
};

</script>

<style scoped>
/* Styles spécifiques à cette page si nécessaire */
</style>
