<template>
  <AppHeader />
  <AppMenu />
  
  <div class="flex flex-col h-full">
    <div
      class="flex flex-col grow px-4 pt-16 pb-4 lg:pt-32 lg:pr-8 lg:pb-8 lg:pl-28"
      @click="closeMenu()"
    >
      <router-view />
    </div>
    
    <AppFooter />
  </div>
</template>

<script setup lang="ts">
import { useStore } from '../stores/main';

import AppMenu from './AppMenu.vue';
import AppHeader from './AppHeader.vue';
import AppFooter from './AppFooter.vue';

const store = useStore();

store.getGroups();

function closeMenu() {
  store.menuOpen = false;
}

</script>
