<template>
  <div class="h-full">
    <div
      v-if="loading"
      class="h-full"
    >
      <div class="animate-pulse prose prose-xl">
        <div class="mb-2 h-12 bg-slate-700 rounded" />
        <div class="mb-12 w-1/5 h-12 bg-slate-700 rounded" />
        <div
          v-for="n in 5"
          :key="n"
          class="mb-3 h-6 bg-slate-700 rounded"
        />
        <div class="mb-3 w-1/4 h-6 bg-slate-700 rounded" />
      </div>
    </div>
    
    <div
      v-if="error"
      class="error"
    >
      {{ error }}
    </div>
    
    <article
      v-if="page && !loading"
      class="grid grid-cols-4 gap-12 p-12 h-full bg-zinc-100"
    >
      <div>
        <AppPageTitle
          :title="page.title"
          class="mb-12"
        />
        
        <div v-if="page.image">
          <img
            :src="'/storage/' + page.image"
            alt=""
          >
        </div>
      </div>
      <div class="col-span-3 h-full">
        <div class="columns-3 gap-12 max-w-full h-full prose columns-fill-auto">
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
