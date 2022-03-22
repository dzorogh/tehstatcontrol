<template>
  <div
    class="fixed top-10 bottom-0 z-20 py-6 max-w-[90vw] text-zinc-100 bg-zinc-900 transition-[width] lg:top-24"
    :class="{'w-80 lg:w-80': menuOpen, 'w-12 lg:w-20 w-0': !menuOpen}"
  >
    <div class="px-3 h-full lg:px-5">
      <div class="flex flex-col gap-6 h-full lg:gap-8">
        <router-link
          v-for="item in store.groups"
          :key="item.slug"
          :to="'/stats/' + item.slug"
          class="group flex relative flex-row items-center hover:text-teal-300"
          :active-class="'text-teal-300 cursor-default'"
          @click="closeMenuWithTimeout"
        >
          <span
            v-if="menuOpen || lg"
            class="flex-none w-6 lg:w-10"
          >
            <component :is="icons[item.icon]" />
          </span>
          
          <span
            v-if="menuOpen"
            class="ml-4 whitespace-nowrap"
          >
            {{ item.title }}
          </span>
          
          <AppTooltip
            :title="item.title"
          />
        </router-link>
  
        <router-link
          :to="{name: 'compare', query: {p: store.compareIds}}"
          class="group flex relative flex-row items-center hover:text-teal-300"
          :active-class="'text-teal-300 cursor-default'"
          @click="closeMenuWithTimeout"
        >
          <span
            v-if="menuOpen || lg"
            class="flex-none w-6 lg:w-10"
          >
            <ScaleIcon />
          </span>
    
          <span
            v-if="menuOpen"
            class="ml-4 whitespace-nowrap"
          >
            Сравнение
          </span>
    
          <AppTooltip
            title="Сравнение"
          />
          
          <span
            v-if="store.compareIds.length && (menuOpen || lg)"
            class="flex overflow-hidden -top-2 -right-2 justify-center items-center ml-4 w-5 h-5 text-sm text-white bg-teal-400 rounded-full lg:absolute"
          >
            {{ store.compareIds.length }}
          </span>
        </router-link>
        
        <div
          v-if="debouncedMenuOpen && menuOpen"
          class="flex flex-col flex-none gap-6 mt-6 ml-10 font-bold lg:hidden"
        >
          <router-link
            active-class="text-teal-300 cursor-default"
            :to="{name: 'page', params: {slug: 'about'}}"
            class="block hover:text-teal-300"
            @click="closeMenuWithTimeout"
          >
            Об организации
          </router-link>
          
          <router-link
            active-class="text-teal-300 cursor-default"
            :to="{name: 'page', params: {slug: 'privacy-policy'}}"
            class="block hover:text-teal-300"
            @click="closeMenuWithTimeout"
          >
            Политика конфиденциальности
          </router-link>
          
          <router-link
            active-class="text-teal-300 cursor-default"
            :to="{name: 'contacts'}"
            class="block hover:text-teal-300"
            @click="closeMenuWithTimeout"
          >
            Контакты
          </router-link>
        </div>
        
        <span
          class="group flex relative flex-row items-center mt-auto hover:text-teal-300 cursor-pointer lg:mt-8"
          @click="logout"
        >
          <span
            v-if="menuOpen || lg"
            class="flex-none w-6 lg:w-10 "
          >
            <LogoutIcon />
          </span>
          
          <span
            v-if="menuOpen"
            class="ml-5"
          >
            Выйти
          </span>
          
          <AppTooltip title="Выйти" />
        
        </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import {
  InformationCircleIcon, PresentationChartLineIcon, CogIcon, ThumbUpIcon, StarIcon, LogoutIcon,
  ScaleIcon
} from '@heroicons/vue/outline';
import {
  computed, reactive, ref, RenderFunction,
} from 'vue';
import axios from 'axios';
import router from '../router';
import { useStore } from '../stores/main';
import { storeToRefs } from 'pinia';
import { breakpointsTailwind, useBreakpoints, useDebounce, useStorage } from '@vueuse/core';
import AppTooltip from './AppTooltip.vue';

const store = useStore();
const menuOpen = computed(() => store.menuOpen);
const debouncedMenuOpen = useDebounce(menuOpen, 200);
const breakpoints = useBreakpoints(breakpointsTailwind);
const lg = breakpoints.greater('lg');


const icons: { [key: string]: RenderFunction } = {
  InformationCircleIcon,
  PresentationChartLineIcon,
  CogIcon,
  ThumbUpIcon,
  StarIcon,
};

function logout() {
  closeMenu();
  
  try {
    axios.get('/api/logout');
  } catch (error) {
    // do nothingcheck-auth
  }
  
  router.push({
    path: '/login',
  });
}

function handleMouseEnter() {
  openMenu();
}

function handleMouseLeave() {
  closeMenu();
}

function openMenu() {
  //console.log('openMenu', menuOpen)
  store.menuOpen = true;
}

function closeMenu() {
  //console.log('closeMenu', menuOpen)
  store.menuOpen = false;
}

let timeout = null;

function closeMenuWithTimeout() {
  clearTimeout(timeout);
  
  timeout = setTimeout(() => {
    closeMenu();
  }, 300);
}

</script>

<style scoped>

</style>
