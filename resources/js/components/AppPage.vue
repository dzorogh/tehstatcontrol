<template>
  <div class="flex flex-col grow">
    <AppLoading v-if="loading" />
    
    <article
      v-if="page && !loading"
      class="overflow-hidden grid-cols-4 gap-12 h-full bg-zinc-100 lg:grid lg:p-12"
    >
      <div class="p-4 lg:p-0">
        <AppPageTitle
          :title="page.title"
          class="mb-0 lg:mb-12"
        />
      </div>
      
      <div
        v-if="page.image"
        class="lg:mb-12"
      >
        <img
          :src="'/storage/' + page.image"
          alt=""
        >
      </div>
      
      <div class="col-span-3 p-4 h-full lg:p-0">
        <div class="gap-12 max-w-full h-full prose-sm lg:columns-3 lg:prose columns-fill-auto">
          <p v-if="page.excerpt">
            {{ page.excerpt }}
          </p>
          
          <div v-html="page.content" />
        </div>
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
  slug: string
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
