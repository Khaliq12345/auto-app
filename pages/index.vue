 <script setup >
import Loading from '../components/Loading.vue';
const router = useRouter();
const loading = ref(true);
onMounted(async () => {
    // Simulez un chargement de 10 secondes
    await new Promise(resolve => setTimeout(resolve, 1000));
    const accessToken = sessionStorage.getItem('AccessToken');
    
  if (accessToken) {
    router.push('/listing'); // Rediriger vers listing si connecté
  } else {
    router.push('/login'); // Rediriger vers login si non connecté
  }
    loading.value = false;
  });

</script>

<template>
  
    <div class="absolute top-0 left-0 h-full w-full bg-gradient-to-br from-red-50 to-primary-500 opacity-50 place-items-center place-content-center  z-50" >
        <!-- <UProgressSpinner size="lg" color="primary" /> -->
        <Loading />

        <div class="container mx-auto p-4 text-center">
          <p>Checking Login State ... </p>
        </div>

    </div>
  

</template> 