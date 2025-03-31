 <script setup >

const router = useRouter();
const loading = ref(true);
onMounted(async () => {
    // Simulez un chargement de 10 secondes
    await new Promise(resolve => setTimeout(resolve, 1000));
    const accessToken = sessionStorage.getItem('supabaseAccessToken');
    
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
        <!-- 2 -->
        <div class="loader-circle-11">
          <div class="arc "></div>
          <div class="arc"></div>
          <div class="arc"></div>
        </div>

        <div class="container mx-auto p-4 text-center">
          <p>Checking Login State ... </p>
        </div>

        <!-- 3 -->
        <!-- <div class="loader-circle-98"></div> -->
        <!-- <USkeleton class="h-12 w-12 rounded-full" />
        <div class="grid gap-2">
          <USkeleton class="h-4 w-[250px]" />
          <USkeleton class="h-4 w-[200px]" />
        </div> -->
    </div>
  

</template> 
<style> 


/* second loading */

.loader-circle-11 {
    position: relative;
    width: 70px;
    height: 70px;
    transform-style: preserve-3d;
    perspective: 400px;
}
.loader-circle-11 .arc {
    position: absolute;
    content: "";
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border-bottom: 5px solid #f00;
}
.loader-circle-11 .arc:nth-child(1) {
    animation: rotate1 1.15s linear infinite;
}
.loader-circle-11 .arc:nth-child(2) {
    animation: rotate2 1.15s linear infinite;
}
.loader-circle-11 .arc:nth-child(3) {
    animation: rotate3 1.15s linear infinite;
}
.loading .arc:nth-child(1) {
    animation-delay: -0.8s;
}
.loader-circle-11 .arc:nth-child(2) {
    animation-delay: -0.4s;
}
.loader-circle-11 .arc:nth-child(3) {
    animation-delay: 0s;
}
@keyframes rotate1 {
    from {
        transform: rotateX(35deg) rotateY(-45deg) rotateZ(0);
    }
    to {
        transform: rotateX(35deg) rotateY(-45deg) rotateZ(1turn);
    }
}
@keyframes rotate2 {
    from {
        transform: rotateX(50deg) rotateY(10deg) rotateZ(0);
    }
    to {
        transform: rotateX(50deg) rotateY(10deg) rotateZ(1turn);
    }
}
@keyframes rotate3 {
    from {
        transform: rotateX(35deg) rotateY(55deg) rotateZ(0);
    }
    to {
        transform: rotateX(35deg) rotateY(55deg) rotateZ(1turn);
    }
}

/* Third Loading */

.loader-circle-98 {
    height: 50px;
    width: 50px;
    position: relative;
    margin: 0 auto;
    animation: anm-loader-circle-98 1s ease infinite;
}
.loader-circle-98:after {
    height: 50px;
    width: 50px;
}
.loader-circle-98:after,
.loader-circle-98:before {
    content: " ";
    box-sizing: border-box;
    border: 5px solid blue;
    border-left-color: #3369E8;
    border-top-color: #D50F25;
    border-right-color: #009925;
    border-bottom-color: #EEB211;
    border-radius: 50%;
    position: absolute;
    display: block;
}
.loader-circle-98:before {
    left: 50%;
    top: 50%;
    height: 70px;
    width: 70px;
    margin: -35px;
    border-width: 6px;
    border-left-color: rgba(0, 0, 0, 0);
    border-right-color: rgba(0, 0, 0, 0);
    border-top-color: rgba(0, 0, 0, 0);
    border-bottom-color: #bbb;
    opacity: 1;
    animation: anm-loader-circle-98 1s reverse ease infinite;
}
@keyframes anm-loader-circle-98 {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

</style>

