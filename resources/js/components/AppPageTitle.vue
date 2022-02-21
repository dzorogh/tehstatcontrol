<template>
  <h1 class="overflow-hidden mb-4 text-2xl font-bold text-ellipsis lg:mb-6 lg:text-4xl">
    {{ title }}
  </h1>
</template>

<script setup lang="ts">
defineProps<{
    title: string
}>();
</script>

<style scoped>

</style>
