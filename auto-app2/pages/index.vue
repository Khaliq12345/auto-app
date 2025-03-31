 <script setup >

const loading = ref(true);
onMounted(async () => {
    // Simulez un chargement de 10 secondes
    await new Promise(resolve => setTimeout(resolve, 1000));
    loading.value = false;
  });

  import axios from 'axios';
import { ref } from 'vue';

const error = ref(null);
const matieres = ref(null);

const fetchMatieres = async () => {
  loading.value = true;
  error.value = null;
  matieres.value = null;

  try {
    const response = await axios.post(
      'http://localhost/api_outilsco/public/api/matieres/read1',
      {
        sigleetbs: 'CSED',
        annee: '2024-2025',
      },
      {
        headers: {
          'Content-Type': 'application/json',
          'apikey': 'e7062c5b-d95d-4fa5-af31-52cb6e662816',
        },
      }
    );

    matieres.value = response.data;
    console.log('Données récupérées:', response.data);
  } catch (err) {
    error.value = err.message || 'Une erreur est survenue.';
    console.error('Erreur lors de la requête:', err);
  } finally {
    loading.value = false;
  }
};

</script>

<template>
  
  <div v-if="loading" class="absolute top-0 left-0 h-full w-full bg-gradient-to-br from-red-50 to-primary-500 opacity-50 place-items-center place-content-center  z-50" >
        <!-- <UProgressSpinner size="lg" color="primary" /> -->
        <div class="spinner my-3"></div>

        <!-- <USkeleton class="h-12 w-12 rounded-full" />
        <div class="grid gap-2">
          <USkeleton class="h-4 w-[250px]" />
          <USkeleton class="h-4 w-[200px]" />
        </div> -->
  </div>
  <div v-else>
    
    <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Récupération de matières</h1>
    <button
      @click="fetchMatieres"
      :disabled="loading"
      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
    >
      Récupérer les matières
      <span v-if="loading" class="ml-2 animate-spin">🔄</span>
    </button>
    <div v-if="error" class="mt-4 text-red-500">
      Erreur lors de la récupération des matières : {{ error }}
    </div>
    <pre v-if="matieres" class="mt-4">{{ matieres }}</pre>
  </div>
  </div>
  

</template> 
<style> 
.spinner {
  border: 4px solid #f3f3f3; /* Couleur du cercle extérieur */
  border-top: 4px solid #3498db; /* Couleur du segment qui tourne */
  border-radius: 50%;
  width: 30px;
  height: 30px;
  animation: spin 2s linear infinite; /* Animation de rotation */
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

