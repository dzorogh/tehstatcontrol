<template>
  <template v-if="loading">
    <AppLoading />
  </template>
  
  <template v-else>
    <AppSlides :slides="slides" />
  
    <div class="mt-6 lg:mt-12">
      <AppNews :news="news" />
    </div>
  </template>
</template>

<script setup lang="ts">

import axios from 'axios';
import { reactive, ref } from 'vue';
import AppNews from './AppNews.vue';
import AppSlides from './AppSlides.vue';
import { setTitle } from '../title';
import AppLoading from './AppLoading.vue';

const slides = [
  {
    image: '/images/bannertop1.jpeg',
    text: 'КРУПНЕЙШИЙ В РОССИИ ЦЕНТР ПО ТЕХНИЧЕСКИМ ИССЛЕДОВАНИЯМ И СКВОЗНОЙ АНАЛИТИКЕ',
  },
  {
    image: '/images/bannertop2.jpeg',
    text: 'ДЕТАЛЬНАЯ АНАЛИТИКА СВЫШЕ 100 ТИПОВ ТЕХНИКИ — ВЫБИРАЙТЕ УДОБНЫЙ ВАМ ТАРИФНЫЙ ПАКЕТ И БУДЬТЕ НА РЫНКЕ',
  },
];

const news = ref(null);
const loading = ref(true);

axios.get('/api/news').then((response) => {
  news.value = response.data.data;
  loading.value = false;
});

setTitle('Государственный центр технических исследований', true)

</script>

<style scoped>

</style>
