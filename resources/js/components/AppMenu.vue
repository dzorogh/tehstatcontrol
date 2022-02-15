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
    
    <span
      class="group flex flex-row items-center mt-6 hover:text-teal-300 cursor-pointer"
      @click="logout"
    >
      <span class="flex-none w-10 ">
        <LogoutIcon />
      </span>
      
      <span
        class="invisible group-hover:visible ml-5 opacity-0 group-hover:opacity-100 transition-opacity"
      >
        Выйти
      </span>
    </span>
  </div>
</template>

<script setup lang="ts">
import {
  InformationCircleIcon, PresentationChartLineIcon, CogIcon, ThumbUpIcon, StarIcon, LogoutIcon,
} from '@heroicons/vue/solid';
import {
  computed, reactive, ref, RenderFunction,
} from 'vue';
import { useStore } from '../store';
import axios from 'axios';
import router from '../router';

const store = useStore();

const icons: { [key: string]: RenderFunction } = {
  InformationCircleIcon,
  PresentationChartLineIcon,
  CogIcon,
  ThumbUpIcon,
  StarIcon,
};

const menu = computed(() => store.state.stats.groups);

function logout() {
  try {
    axios.get('/api/logout');
  } catch (error) {
    // do nothingcheck-auth
  }
  
  router.push({
    path: '/login',
  });
}

</script>

<style scoped>

</style>
