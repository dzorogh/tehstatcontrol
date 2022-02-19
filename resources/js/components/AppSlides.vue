<template>
  <div class="relative">
    <div class="aspect-[1470/443] bg-gray-700">
      <div
        v-for="(slide, index) in slides"
        :key="index"
        :class="getSlideClass(index)"
        class="absolute inset-0 z-0 transition-opacity duration-1000"
      >
        <img
          :src="slide.image"
          alt="bg"
          class="w-full"
        >

        <div class="flex absolute inset-0 content-center items-center py-2 px-4 text-xs font-bold text-center text-white lg:py-12 lg:px-48 lg:text-3xl">
          <div>
            {{ slide.text }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="inline-flex mt-2">
    <a
      v-for="(slide, index) in slides"
      :key="index"
      href="#"
      :class="getDotsClass(index)"
      class="block p-2 mr-2 bg-teal-500 rounded-full hover:ring-2 hover:ring-cyan-300"
      @click.prevent="changeSlide(index)"
    />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{
  slides: {
    image: string,
    text: string
  }[]
}>();

const activeSlideClasses   = ['opacity-0'];
const inactiveSlideClasses = ['z-10', 'opacity-100'];

const activeSlideIndex = ref(0);
const lastSlideIndex   = props.slides.length - 1;

function getSlideClass(slideIndex: any) {
  if (slideIndex === activeSlideIndex.value) {
    return activeSlideClasses;
  }
  return inactiveSlideClasses;
}

const interval = setInterval(() => {
  if (activeSlideIndex.value < lastSlideIndex) {
    activeSlideIndex.value++;
  } else {
    activeSlideIndex.value = 0;
  }
}, 5000);

function changeSlide(index: any) {
  activeSlideIndex.value = index;
  clearInterval(interval);
}

function getDotsClass(index: any) {
  if (index === activeSlideIndex.value) {
    return 'bg-teal-300';
  }
  return 'bg-gray-400';
}

</script>

<style scoped>

</style>
