<script setup>
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const isLoading = ref(false)
const error = ref('')

const { $supabase } = useNuxtApp()
const router = useRouter()

import axios from 'axios';

async function handleLogin() {

  isLoading.value = true
  error.value = ''

  console.log('submitting')

  try {
    const response = await axios.post(
      'https://nitlrmzkefgmjtyrjicc.supabase.co/auth/v1/token?grant_type=password',
      {
        email: email.value,
        password: password.value
      },
      {
        headers: {
          'Content-Type': 'application/json',
          'apikey': 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im5pdGxybXprZWZnbWp0eXJqaWNjIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDIwMjA5MzQsImV4cCI6MjA1NzU5NjkzNH0.2IBAqF0ZpLbR-v_AXTxCOw5FpOvqdmQyNG8iMolP-rk',
        },
      }
    );

    // La connexion a réussi, la réponse contient les informations de session
    console.log('Connexion réussie:', response.data);
    // Vous pouvez stocker le token et rediriger l'utilisateur ici
    router.push('/listing') // Exemple de redirection
  } catch (err) {
    console.error('Erreur lors de la connexion:', err);
    error.value = err.response?.data?.message || 'Identifiants incorrects ou erreur de serveur.';
  } finally {
    isLoading.value = false;
  }
}

async function handleLogin2() {
  isLoading.value = true
  error.value = ''

  console.log('submitting')

  try {
    const { error: authError } = await $supabase.auth.signInWithPassword({
      email: email.value,
      password: password.value
    })

    if (authError) throw authError
    router.push('/listing')
  } catch (err) {
    error.value = err.message
    // showToast('Sign In Error !',err.message,'i-lucide-info')
  } finally {
    isLoading.value = false
  }
}


// Toast 

const toast = useToast()

function showToast(title, desc, icon) {
  toast.add({
    title: title,
    description: desc,
    icon: icon,
    close: {
      color: 'danger',
      variant: 'outline',
      class: 'rounded-full'
    }
  })
}

</script>

<template >
  <!-- <div class="min-h-screen flex items-center justify-center p-4 bg-[conic-gradient(at_top_left,_var(--tw-gradient-stops))] from-blue-100 via-blue-300 to-red-500"> -->
  <div class="min-h-screen text-center bg-gradient-to-br from-red-50 to-primary-500 flex items-center justify-center p-4">
    <UCard class="w-full max-w-md shadow-xl">
      <template #header>
        <div class="text-center space-y-3">
          
          <UIcon size="50" name="i-heroicons-user" class="w-12 h-12 mx-auto text-primary-500" />
          <h1 class="text-3xl font-bold ">Login</h1>
          <p class="text-gray-500">Access your Dashboard</p>
        </div>
      </template>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <UAlert 
          v-if="error"
          :title="error"
          icon="i-heroicons-exclamation-circle"
          color="red"
          variant="subtle"
        />
        <UFormGroup label="Email" name="email"  required>
          <UInput
            v-model="email"
            type="email"
            placeholder="votre@email.com"
            icon="i-heroicons-envelope"
            size="lg"
            class="w-80"
            autofocus
          />
        </UFormGroup>
        <div class="my-6"></div>
        <UFormGroup label="Mot de passe" name="password" required>
          <UInput
            v-model="password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="••••••••"
            icon="i-heroicons-lock-closed"
            class="w-80"
            size="lg"
          >
            <template #trailing>
              <UButton
                variant="ghost"
                :icon="showPassword ? 'i-heroicons-eye-slash' : 'i-heroicons-eye'"
                color="gray"
                @click="showPassword = !showPassword"
              />
            </template>
          </UInput>
        </UFormGroup>

        <div class="my-8"></div>

        <UButton
          type="submit"
          block
          size="lg"
          color="primary"
          :loading="isLoading"
          label="Log In"
        />

        <!-- <UButton label="Show toast" color="neutral" variant="outline" @click="showToast" /> -->
        <!-- <NuxtLink to="/listing" class="...">Tableau de bord</NuxtLink> -->
      </form>

      <template #footer >
        <div class=" text-center items-center" >
          <UIcon size="10" name="i-heroicons-lock-closed" class="mx-auto text-primary-500 " />
        <span class=" text-center text-sm text-gray-500">
          Secured Login
        </span>
        </div>
        
        
      </template>
    </UCard>
  </div>
</template>