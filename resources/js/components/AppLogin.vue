<template>
  <div class="flex justify-center items-center h-full bg-zinc-800">
    <div class="flex flex-col justify-center items-center">
      <div class="mb-8">
        <img
          src="/images/logo-tsc.svg"
          class="w-64"
          alt="ГЦТИ"
        >
      </div>
      
      <form
        method="GET"
        action="https://crm.globaldrive.ru/my.globaldrive.ru.php"
      >
        <input
          type="hidden"
          name="return"
          :value="redirectTo"
        >
        <button
          type="submit"
          class="inline-block py-3 px-6 text-lg font-bold text-zinc-100 bg-cyan-600 hover:bg-cyan-500 rounded"
        >
          Войти с помощью Битрикс24
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { setTitle } from '../title';

const redirectTo = computed(() => {
  return location.protocol + '//' + location.host + '/bitrix-login';
});

setTitle('Авторизация');
</script>

<style scoped>

</style>
