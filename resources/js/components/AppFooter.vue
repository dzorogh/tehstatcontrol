<template>
  <div class="flex flex-wrap flex-none gap-6 items-center p-6 text-zinc-100 bg-zinc-900 lg:flex-row lg:flex-nowrap lg:pl-36 flex-column">
    <div class="px-6 text-xs font-bold lg:shrink lg:px-0 lg:text-base">
      Государственный центр технических исследований
      {{ year }} г. | Лицензия от 10.11.2010 г.
    </div>
    
    <div class="flex grow gap-6 justify-center items-center lg:flex-row lg:flex-none lg:ml-auto">
      <img
        src="/images/iso.svg"
        alt="ISO"
        class="h-12 lg:h-24"
      >
      <img
        src="/images/sert.svg"
        alt="РСТ"
        class="h-12 lg:h-24"
      >
      <img
        src="/images/torg.svg"
        alt="ТПП РФ"
        class="h-12 lg:h-24"
      >
    </div>
  </div>
</template>

<script setup lang="ts">
const year = new Date().getFullYear();

</script>

<style scoped>

</style>
