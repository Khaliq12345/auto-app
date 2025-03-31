<script setup>
const { locale, locales } = useI18n()
const switchLocalePath = useSwitchLocalePath()

const availableLocales = computed(() => {
  return locales.value.filter(l => l.code !== locale.value)
})
</script>

<template>
  <UDropdown :items="availableLocales" :popper="{ placement: 'bottom-start' }">
    <UButton
      color="gray"
      variant="ghost"
      :label="$i18n.locales.find(l => l.code === locale)?.name"
      trailing-icon="i-heroicons-chevron-down-20-solid"
    />

    <template #item="{ item }">
      <NuxtLink :to="switchLocalePath(item.code)" class="flex items-center gap-2">
        <span>{{ item.name }}</span>
      </NuxtLink>
    </template>
  </UDropdown>
</template>