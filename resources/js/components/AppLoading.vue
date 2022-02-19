<template>
  <div class="flex grow justify-center items-center">
    <div class="inline-block relative">
      <div class="absolute -top-8 left-[2.8rem] w-2 h-10 bg-teal-300 rotate-[35deg]" />
      <svg
        class="w-12 h-12 animate-spin"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="stroke-teal-300"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        />
        <path
          class="fill-teal-200"
          fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
        />
      </svg>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Circl } from '@heroicons/vue/outline';

</script>

<style scoped>

</style>
