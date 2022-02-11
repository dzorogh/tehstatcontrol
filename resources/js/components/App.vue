<template>
  <AppHeader />

  <div class="flex flex-row flex-1">
    <div class="group flex-none py-12 px-5 w-20 hover:w-60 text-zinc-100 bg-zinc-900 transition-[width]">
      <AppMenu class="sticky top-24" />
    </div>

    <div class="z-10 flex-1 p-12 bg-zinc-300">
      <router-view />
    </div>
  </div>

  <div class="flex flex-row items-center p-6 text-zinc-100 bg-zinc-900">
    <div class="ml-36 font-bold">
      Государственный центр технических исследований <br>
      {{ year }} г. | Лицензия от 10.11.2010 г.
    </div>

    <div class="flex flex-row items-center ml-auto">
      <img
        src="/images/iso.svg"
        alt="ISO"
        class="ml-5 h-24"
      >
      <img
        src="/images/sert.svg"
        alt="РСТ"
        class="ml-5 h-24"
      >
      <img
        src="/images/torg.svg"
        alt="ТПП РФ"
        class="ml-5 h-24"
      >
    </div>
  </div>
</template>

<script setup lang="ts">
import AppMenu from './AppMenu.vue';
import { useStore } from '../store';
import AppHeader from './AppHeader.vue';

const store = useStore();

store.dispatch('getGroups');

const year = new Date().getFullYear();

</script>
