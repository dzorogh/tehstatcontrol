<template>
  <h1 class="mb-6 text-5xl font-bold">
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
