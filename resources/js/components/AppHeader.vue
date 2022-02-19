<template>
  <div class="flex fixed inset-x-0 top-0 z-30 flex-row items-center py-2 px-3 h-16 text-zinc-100 bg-zinc-900 lg:py-4 lg:px-6 lg:h-24">
    <div class="flex-none lg:-mt-3 lg:w-auto">
      <router-link to="/">
        <img
          src="/images/logo-tsc.svg"
          class="w-14 h-10 lg:w-28 lg:h-20"
          alt="ГЦТИ"
        >
      </router-link>
    </div>

    <div class="hidden ml-6 text-sm font-bold lg:block">
      <a href="/">
        Автономная некоммерческая организация <br>
        «Государственный центр технических исследований»
      </a>
    </div>

    <div class="hidden flex-none ml-auto font-bold lg:block">
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
        :to="{name: 'contacts'}"
        class="ml-5 hover:text-teal-300"
      >
        Контакты
      </router-link>
    </div>
    
    <div class="p-2 ml-auto lg:hidden">
      {{ store.menuOpen }}
      <a
        href=""
        @click.prevent="toggleMenu"
      >
        <MenuIcon
          class="w-8 h-8"
          :class="{'text-teal-300': store.menuOpen}"
        />
      </a>
    </div>
  </div>
</template>

<script setup lang="ts">
import { MenuIcon } from '@heroicons/vue/outline';
import { useStore } from '../stores/main';
import { storeToRefs } from 'pinia';
import { computed } from 'vue';

const store = useStore();
const menuOpen = computed(() => {
  return store.menuOpen;
})

function toggleMenu() {
  store.menuOpen = !menuOpen.value
}

</script>

<style scoped>

</style>
