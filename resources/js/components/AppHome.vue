<template>
  <AppSlides :slides="slides" />

  <div class="mt-16">
    <AppNews :news="news" />
  </div>
</template>

<script setup lang="ts">

import axios from 'axios';
import { reactive, ref } from 'vue';
import AppNews from './AppNews.vue';
import AppSlides from './AppSlides.vue';

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

axios.get('/api/news').then((response) => {
  news.value = response.data.data;
});

</script>

<style scoped>

</style>
