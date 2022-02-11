<template>
  <div class="flex sticky top-0 z-50 flex-row items-center py-4 px-6 text-zinc-100 bg-zinc-900">
    <div class="-mt-3">
      <router-link to="/">
        <img
          src="/images/logo-tsc.svg"
          class="w-28 h-20"
          alt="ГЦТИ"
        >
      </router-link>
    </div>

    <div class="ml-6 text-sm font-bold">
      <a href="/">
        Автономная некоммерческая организация <br>
        «Государственный центр технических исследований»
      </a>
    </div>

    <div class="ml-auto font-bold">
      <router-link
        active-class="text-teal-300 cursor-default"
        :to="{name: 'page', params: {slug: 'about'}}"
        class="ml-5 hover:text-teal-300"
      >
        Об организации
      </router-link>

      <router-link
        active-class="text-teal-300 cursor-default"
        :to="{name: 'page', params: {slug: 'privacy-policy'}}"
        class="ml-5 hover:text-teal-300"
      >
        Политика конфиденциальности
      </router-link>

      <router-link
        active-class="text-teal-300 cursor-default"
        :to="{name: 'page', params: {slug: 'contacts'}}"
        class="ml-5 hover:text-teal-300"
      >
        Контакты
      </router-link>
    </div>
  </div>
</template>

<script setup lang="ts">

</script>

<style scoped>

</style>
