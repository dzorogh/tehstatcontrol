<template>
  <div
    role="tooltip"
    class="hidden absolute left-20 invisible group-hover:visible z-10 justify-center items-center px-6 h-10 text-white whitespace-nowrap bg-zinc-900 rounded shadow transition-opacity duration-300 lg:flex tooltip"
  >
    <div class="inline-block overflow-hidden absolute inset-y-0 top-2 right-full w-4">
      <div class="h-10 bg-zinc-900 -rotate-45 origin-top-right" />
    </div>
    
    {{ title }}
  </div>
</template>

<script setup lang="ts">

const props = defineProps<{
  title: string | number | null
}>();
</script>

<style scoped>

</style>
