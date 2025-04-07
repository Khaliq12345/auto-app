<template>
 <div
    class="min-h-screen text-center bg-gradient-to-br from-red-50 to-primary-500 flex items-center justify-center p-4">
    <UCard class="w-full max-w-md shadow-xl">
      <template #header>
        <div class="text-center space-y-3">
          <UIcon size="50" name="i-heroicons-user" class="w-12 h-12 mx-auto text-primary-500" />
          <h1 class="text-3xl font-bold ">Login</h1>
          <p class="text-gray-500">Access your Dashboard</p>
        </div>
      </template>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <UAlert v-if="error" :title="error" icon="i-heroicons-exclamation-circle" color="red" variant="subtle" />
        <UFormField label="Email" name="email" required>
          <UInput v-model="email" type="email" placeholder="votre@email.com" icon="i-heroicons-envelope" size="lg"
            class="w-80" autofocus />
        </UFormField>
        <div class="my-6"></div>
        <UFormField label="Mot de passe" name="password" required>
          <UInput v-model="password" :type="showPassword ? 'text' : 'password'" placeholder="••••••••"
            icon="i-heroicons-lock-closed" class="w-80" size="lg">
            <template #trailing>
              <UButton variant="ghost" :icon="showPassword ? 'i-heroicons-eye-slash' : 'i-heroicons-eye'" color="gray"
                @click="showPassword = !showPassword" />
            </template>
          </UInput>
        </UFormField>

        <div class="my-8"></div>

        <UButton type="submit" block size="lg" color="primary" :loading="isLoading" label="Log In" />

        <!-- <UButton label="Show toast" color="neutral" variant="outline" @click="showToast" /> -->
        <!-- <NuxtLink to="/listing" class="...">Tableau de bord</NuxtLink> -->
      </form>

      <template #footer>
        <div class=" text-center items-center">
          <UIcon size="10" name="i-heroicons-lock-closed" class="mx-auto text-primary-500 " />
          <span class=" text-center text-sm text-gray-500">
            Secured Login
          </span>
        </div>
      </template>
    </UCard>
  </div>
</template>
<script setup>
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const isLoading = ref(false)
const error = ref('')
const router = useRouter()

import axios from 'axios';

async function handleLogin() {

  isLoading.value = true
  error.value = ''
  try {
    const response = await axios.post("http://157.180.69.73:8000/login", 
      "",
      {
        params: {
          email: email.value,
          password: password.value
            },

        headers: {
          "accept": "application/json",
    "content-type": "application/x-www-form-urlencoded",
        },
      },
    );

    // La connexion a réussi, la réponse contient les informations de session
    // console.log('Connexion réussie:', response.data);
    showToast('Success',"Successfully Logged In !", 'i-heroicons-check-badge', 'success');
    // Stocker l'access token dans la session du navigateur
    sessionStorage.setItem('AccessToken', response.data.details.session.access_token);
    sessionStorage.setItem('RefreshToken', response.data.details.session.refresh_token);
    sessionStorage.setItem('ExpiresAt', response.data.details.session.expires_at);
    // sessionStorage.setItem('user', response.data.user);
    // 
    router.push('/listing') // 
  } catch (err) {
    console.error('Erreur lors de la connexion:', err);
    error.value = err.response?.data?.message || 'Invalid Credentials or server error !';
    showToast('Error !',"Failled to LogIn, check informations and try again", 'i-heroicons-exclamation-triangle', 'error');
  } finally {
    isLoading.value = false;
  }
}


// Toast 

const toast = useToast()

function showToast(title, desc, icon, color) {
  toast.add({
    title: title,
    description: desc,
    icon: icon,
    closeIcon : 'i-heroicons-x-mark',
    close: {
      color: error,
      variant: 'outline',
      class: 'rounded-full'
    }
  })
}
</script>