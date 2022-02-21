<template>
  <div class="flex justify-center items-center h-full bg-zinc-800">
    <div class="flex flex-col justify-center items-center">
      <router-link
        to="/"
        class="flex-none mb-8"
      >
        <img
          src="/images/logo-tsc.svg"
          class="w-64"
          alt="ГЦТИ"
        >
      </router-link>
  
      <a
        :href="'https://crm.globaldrive.ru/my.globaldrive.ru.php?return=' + redirectTo"
        class="block flex-none py-3 px-6 text-lg font-bold text-zinc-100 bg-cyan-600 hover:bg-cyan-500 rounded"
      >
        Войти с помощью Битрикс24
      </a>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { setTitle } from '../title';

const redirectTo = computed(() => {
  return location.protocol + '//' + location.host + '/bitrix-login';
});

setTitle('Авторизация');

</script>

<style scoped>

</style>
