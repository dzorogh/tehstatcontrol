<template>
  <div class="flex flex-col grow">
    <AppLoading v-if="loading" />
    
    <article
      v-if="page && !loading"
      class="flex overflow-hidden flex-col p-4 h-full bg-zinc-100 lg:p-12"
    >
      <div class="flex-none lg:mb-12">
        <AppPageTitle
          :title="page.title"
        />
  
        <div
          v-if="page.category"
          class="inline-block px-2 text-white bg-teal-500"
        >
          {{ page.category.title }}
        </div>
      </div>
  
      <div class="grow shrink-0 gap-12 prose-sm lg:columns-2 lg:max-w-full lg:prose 2xl:columns-3 columns-fill-auto">
        <div
          v-if="page.image"
          class=""
        >
          <img
            class="!mt-0"
            :src="'/storage/' + page.image"
            alt=""
          >
        </div>
    
        <p v-if="page.excerpt">
          {{ page.excerpt }}
        </p>
    
        <span v-html="page.content" />
      </div>
    </article>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import AppPageTitle from './AppPageTitle.vue';
import { setTitle } from '../title';
import AppLoading from './AppLoading.vue';
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core';
import { Product } from '../types/Product';

const breakpoints = useBreakpoints(breakpointsTailwind);

const router = useRouter();
const route = useRoute();

const loading = ref(false);

interface Page {
  title: string,
  content: string,
  excerpt: string,
  image: string,
  id: number,
  slug: string,
  category: Product['category']
}

const page = ref<Page>();
const error = ref();

watch(
  () => route.params.slug,
  (newSlug) => {
    if (newSlug) { // when redirecting to route that is NOT PAGE it will be undefined
      loading.value = true;
      axios.get(`/api/page/${newSlug}`)
        .then((response) => {
          if (!response || !response.data || !response.data.data) {
            throw new Error();
          }
          page.value = response?.data?.data;
          
          setTitle(page.value.title);
        })
        .catch(() => {
          router.push({
            name: 'NotFound',
            params: {
              pathMatch: route?.path.substring(1)
                .split('/'),
            },
            query: route?.query,
            hash: route?.hash,
          });
        })
        .finally(() => {
          loading.value = false;
        });
    }
  },
  {
    immediate: true,
  },
);

</script>

<style scoped>
.prose p:first-child {
  margin-top: 0;
}

</style>
