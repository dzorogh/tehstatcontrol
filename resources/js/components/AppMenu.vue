<template>
  <div class="grid gap-8">
    <router-link
      v-for="item in menu"
      :key="item.slug"
      :to="'/stats/' + item.slug"
      class="group flex flex-row items-center hover:text-teal-300"
      :active-class="'text-teal-300 cursor-default'"
    >
      <span class="flex-none w-10">
        <component :is="icons[item.icon]" />
      </span>

      <span class="invisible group-hover:visible ml-5 opacity-0 group-hover:opacity-100 transition-opacity">
        {{ item.title }}
      </span>
    </router-link>
  </div>
</template>

<script setup lang="ts">
import {
  InformationCircleIcon, PresentationChartLineIcon, CogIcon, ThumbUpIcon, StarIcon,
}                   from '@heroicons/vue/solid';
import {
  computed, reactive, ref, RenderFunction,
}                   from 'vue';
import { useStore } from '../store';

const store = useStore();

const icons: { [key: string]: RenderFunction } = {
  InformationCircleIcon,
  PresentationChartLineIcon,
  CogIcon,
  ThumbUpIcon,
  StarIcon,
};

const menu = computed(() => store.state.stats.groups);

/* const menu = [
 {
 url: '/stats/info',
 title: 'Общая информация',
 icon: InformationCircleIcon
 },
 {
 url: '/stats/pricing',
 title: 'Ценообразование',
 icon: PresentationChartLineIcon
 },
 {
 url: '/stats/service',
 title: 'Сервисная статистика',
 icon: CogIcon
 },
 {
 url: '/stats/advantages',
 title: 'Преимущества и недостатки',
 icon: ThumbUpIcon
 },
 {
 url: '/stats/rating',
 title: 'Рейтинги',
 icon: StarIcon
 }
 ] */
</script>

<style scoped>

</style>
