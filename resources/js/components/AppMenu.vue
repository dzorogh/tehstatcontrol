<template>
  <div
    class="group fixed top-16 bottom-0 z-20 py-6 px-3 w-12 max-w-[90vw] text-zinc-100 bg-zinc-900 transition-[width] lg:top-24 lg:px-5"
    :class="{'w-80 lg:w-80': menuOpen, 'lg:w-20 w-12': !menuOpen}"
    @mousemove="handleMouseEnter"
    @mouseleave="handleMouseLeave"
  >
    {{ menuOpen }}
    <div class="flex flex-col gap-6 h-full lg:gap-8">
      <router-link
        v-for="item in store.groups"
        :key="item.slug"
        :to="'/stats/' + item.slug"
        class="flex flex-row items-center hover:text-teal-300"
        :active-class="'text-teal-300 cursor-default'"
        @click="closeMenuWithTimeout"
      >
        <span class="flex-none w-6 lg:w-10">
          <component :is="icons[item.icon]" />
        </span>
        
        <span
          v-if="menuOpen"
          class="ml-4 whitespace-nowrap"
        >
          {{ item.title }}
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
        >
          Об организации
        </router-link>
    
        <router-link
          active-class="text-teal-300 cursor-default"
          :to="{name: 'page', params: {slug: 'privacy-policy'}}"
          class="block hover:text-teal-300"
        >
          Политика конфиденциальности
        </router-link>
    
        <router-link
          active-class="text-teal-300 cursor-default"
          :to="{name: 'contacts'}"
          class="block hover:text-teal-300"
        >
          Контакты
        </router-link>
      </div>
      
      <span
        class="flex flex-row items-center mt-auto hover:text-teal-300 cursor-pointer lg:mt-8"
        @click="logout"
      >
        <span class="flex-none w-6 lg:w-10 ">
          <LogoutIcon />
        </span>
        
        <span
          v-if="menuOpen"
          class="ml-5"
        >
          Выйти
        </span>
      </span>
    </div>
  </div>
</template>

<script setup lang="ts">
import {
  InformationCircleIcon, PresentationChartLineIcon, CogIcon, ThumbUpIcon, StarIcon, LogoutIcon,
} from '@heroicons/vue/outline';
import {
  computed, reactive, ref, RenderFunction,
} from 'vue';
import axios from 'axios';
import router from '../router';
import { useStore } from '../stores/main';
import { storeToRefs } from 'pinia';
import { useDebounce } from '@vueuse/core'


const store = useStore();
const menuOpen = computed(() => store.menuOpen);
const debouncedMenuOpen = useDebounce(menuOpen, 200)

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
  console.log('openMenu', menuOpen)
  store.menuOpen = true;
}

function closeMenu() {
  console.log('closeMenu', menuOpen)
  store.menuOpen = false;
}

let timeout = null;

function closeMenuWithTimeout() {
  clearTimeout(timeout);
  
  timeout = setTimeout(() => {
    closeMenu();
  }, 300)
}

</script>

<style scoped>

</style>
