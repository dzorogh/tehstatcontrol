<template>
  <div class="flex flex-wrap gap-4 items-baseline mb-7 sm:flex-nowrap">
    <h2 class="shrink-0 text-xl font-bold lg:text-2xl">
      Независимые рейтинги
    </h2>
    
    <div
      class="overflow-hidden relative grow"
    >
      <div
        ref="tagContainer"
        class="flex overflow-hidden gap-x-6 gap-y-2"
      >
        <a
          href="#"
          :class="[...tagClasses(null)]"
          @click.prevent="selectCategory(null)"
        >
          Показать все
        </a>
        
        <a
          v-for="category in categories"
          :key="category.id"
          :class="[...tagClasses(category.id)]"
          href="#"
          @click.prevent="selectCategory(category.id)"
        >
          {{ category.title }}
        </a>
      </div>
      
      <a
        v-if="!tagContainerScroll.arrivedState.right && isOverflowing"
        href="#"
        class="absolute -top-1 right-0 py-1 pl-10 text-zinc-400 hover:text-zinc-600 bg-gradient-to-r from-transparent via-white to-white"
        @mouseenter.prevent="startScrollRight"
        @mouseleave.prevent="stopScrollRight"
        @mousedown.prevent="startScrollRight"
        @mouseup.prevent="stopScrollRight"
        @click.prevent
      >
        <ArrowCircleRightIcon class="w-7 h-7 " />
      </a>
  
      <a
        v-if="!tagContainerScroll.arrivedState.left && isOverflowing"
        href="#"
        class="absolute -top-1 left-0 py-1 pr-10 text-zinc-400 hover:text-zinc-600 bg-gradient-to-l from-transparent via-white to-white "
        @mouseenter.prevent="startScrollLeft"
        @mouseleave.prevent="stopScrollLeft"
        @mousedown.prevent="startScrollLeft"
        @mouseup.prevent="stopScrollLeft"
        @click.prevent
      >
        <ArrowCircleLeftIcon class="w-7 h-7 " />
      </a>
    </div>
  </div>
  
  <div
    v-if="filteredNews"
    class="grid gap-10 sm:grid-cols-2 md:grid-cols-3"
  >
    <router-link
      v-for="newsItem in filteredNews"
      :key="newsItem.id"
      :to="{ name: 'page', params: { slug: newsItem.slug } }"
      class="flex flex-col bg-zinc-100 shadow-2xl transition-all hover:scale-105"
    >
      <span class="aspect-video block relative w-full">
        <span
          v-if="newsItem.category"
          class="absolute top-3 right-3 z-10 px-2 text-sm bg-teal-100"
        >
          {{ newsItem.category.title }}
        </span>
        
        <img :src="'/storage/' + newsItem.image">
      </span>
      
      <span class=" flex flex-col p-5 h-full">
        
        <span class="block flex-none text-lg font-bold">
          {{ newsItem.title }}
        </span>
        
        <span class="hidden flex-1 my-4 lg:block">
          {{ newsItem.excerpt }}
        </span>
        
        <span class="block flex-none pt-4 mt-auto text-right">
          <span class="block py-2 px-3 font-bold text-center text-zinc-100 bg-teal-500 rounded shadow lg:inline-block">
            Читать
          </span>
        </span>
      
      </span>
    </router-link>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { default as axios } from 'axios';
import { Product } from '../types/Product';
import { ArrowCircleRightIcon, ArrowCircleLeftIcon } from '@heroicons/vue/outline';
import { useElementSize, useRafFn, useScroll, useWindowSize } from '@vueuse/core';
import { Pausable } from '@vueuse/shared';

const props = defineProps<{
  news: {
    title: string
    slug: string
    excerpt: string
    id: number
    image?: string
    category?: Product['category']
  }[] | null
}>();

const categoryId = ref<number | null>(null);
const categories = ref<Product['category'][]>();

const scrollRight = ref<Pausable>();
const scrollLeft = ref<Pausable>();

onMounted(async () => {
  categories.value = (await axios.get('/api/news-types')).data.data;
  
  scrollRight.value = useRafFn(() => {
    tagContainer.value.scrollTo({
      left: tagContainerScroll.x.value + 4,
    });
  }, {
    immediate: false,
  });
  
  scrollLeft.value = useRafFn(() => {
    tagContainer.value.scrollTo({
      left: tagContainerScroll.x.value - 4,
    });
  }, {
    immediate: false,
  });
});

const filteredNews = computed(() => {
  if (!categoryId.value) {
    return props.news;
  }
  
  return props.news.filter((item) => {
    return item.category.id === categoryId.value;
  });
});

const tagClasses = (itemId) => {
  let activeClasses = [];
  
  if (categoryId.value === itemId) {
    activeClasses = ['bg-teal-500', 'text-white'];
  } else {
    activeClasses = ['text-zinc-400'];
  }
  
  return [
    'font-bold',
    'px-2',
    'whitespace-nowrap',
    'py-0.5',
    'rounded',
    ...activeClasses,
  ];
};

const selectCategory = (value) => {
  categoryId.value = value;
};

const tagContainer = ref<HTMLElement | null>(null);
const tagContainerScroll = useScroll(tagContainer);

const startScrollRight = () => {
  if (tagContainer.value) {
    scrollLeft.value.pause();
    scrollRight.value.resume();
  }
};

const stopScrollRight = () => {
  if (tagContainer.value) {
    scrollRight.value.pause();
    scrollLeft.value.pause();
  }
};

const startScrollLeft = () => {
  if (tagContainer.value) {
    scrollRight.value.pause();
    scrollLeft.value.resume();
  }
};

const stopScrollLeft = () => {
  if (tagContainer.value) {
    scrollLeft.value.pause();
    scrollRight.value.pause();
  }
};

const tagContainerSize = useElementSize(tagContainer)

const isOverflowing = computed(() => {
  if (tagContainer.value) {
    return tagContainerSize.width.value < tagContainer.value.scrollWidth;
  }
  
  return false;
})


</script>
