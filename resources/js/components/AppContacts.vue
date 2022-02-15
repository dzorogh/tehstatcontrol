<template>
  <AppPageTitle :title="'Контакты'" />
  
  <div class="mb-4">
    <label class="block mb-2">
      Ваше Имя
    </label>
    <input
      type="text"
      class="block w-96 rounded"
    >
  </div>
  
  <div class="mb-4">
    <label class="block mb-2">
      Ваш E-mail
    </label>
    <input
      type="email"
      class="block w-96 rounded"
    >
  </div>
  
  <div class="mb-4">
    <label class="block mb-2">
      Ваше Имя
    </label>
    <input
      type="text"
      class="block w-96 rounded"
    >
  </div>
  
  <div class="mb-4">
    <label class="block mb-2">
      Ваш Телефон
    </label>
    <input
      type="tel"
      class="block w-96 rounded"
    >
  </div>
  
  <div class="mb-5">
    <label class="block mb-2">
      Ваше Сообщение
    </label>
    <textarea
      class="block w-96 rounded"
    />
  </div>
  
  <label class="flex gap-2 items-center mb-6">
    <input
      type="checkbox"
      class="w-6 h-6 text-teal-400 rounded"
    >
    Согласие с <router-link to="/privacy-policy">политикой конфиденциальности</router-link>
  </label>

  <button
    type="submit"
    class="inline-block py-3 px-6 text-lg font-bold text-zinc-100 bg-teal-500 rounded"
  >
    Отправить
  </button>
</template>

<script setup lang="ts">
import AppPageTitle from './AppPageTitle.vue';
import { setTitle } from '../title';

setTitle('Контакты')

</script>

<style scoped>

</style>
