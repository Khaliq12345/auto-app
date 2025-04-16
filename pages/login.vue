<template>
  <ClientOnly>
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
          <UAlert v-if="error" :title="error" icon="i-heroicons-exclamation-circle" color="error" variant="subtle" />
          <UFormField label="Email" name="email" required>
            <UInput v-model="email" type="email" placeholder="votre@email.com" icon="i-heroicons-envelope" size="lg"
              class="w-full" autofocus />
          </UFormField>
          <div class="my-6"></div>
          <UFormField label="Mot de passe" name="password" required>
            <UInput v-model="password" :type="showPassword ? 'text' : 'password'" placeholder="••••••••"
              icon="i-heroicons-lock-closed" class="w-full" size="lg">
              <template #trailing>
                <UButton variant="ghost" :icon="showPassword ? 'i-heroicons-eye-slash' : 'i-heroicons-eye'" color="gray"
                  @click="showPassword = !showPassword" />
              </template>
            </UInput>
          </UFormField>
          <div class="my-8"></div>
          <UButton type="submit" block size="lg" color="primary" :loading="isLoading" label="Log In" />
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
  </ClientOnly>
</template>

<script setup>
import { useLoginForm } from '~/composables/useLoginFunctions';
const { email, password, showPassword, isLoading, error, handleLogin } = useLoginForm();
</script>