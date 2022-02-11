<template>
  <div>
    <div
      v-if="pageLoading"
      class="animate-pulse"
    >
      <div class="prose prose-xl">
        <div class="mb-6 w-full h-14 bg-slate-700 rounded" />
        <div class="mb-3 w-full h-4 bg-slate-700 rounded" />
        <div class="mb-5 w-60 h-4 bg-slate-700 rounded" />
      </div>
      
      <div class="grid grid-flow-col auto-cols-min gap-6 mb-8">
        <div class="w-64 h-12 bg-slate-700 rounded" />
        <div class="w-64 h-12 bg-slate-700 rounded" />
        <div class="w-64 h-12 bg-slate-700 rounded" />
      </div>
      
      <div class="w-full h-64 bg-slate-700 rounded" />
    </div>
    
    <div v-if="!pageLoading">
      <div
        v-if="group"
        class="mb-5"
      >
        <AppPageTitle :title="group.title" />
        
        <div class="prose">
          {{ group.description }}
        </div>
      </div>
      
      <div v-if="list">
        <AppStatsFilters
          :filters="list.filters"
          @update:filters="updateFilters"
        />
        
        <div class="">
          <AppStatsPagination
            class="my-4"
            position="top"
            :current-page="list.meta.current_page"
            :last-page="list.meta.last_page"
            @update:page="updatePage"
          />
          
          <div
            class="overflow-hidden shadow-md sm:rounded-lg"
            :class="{'animate-pulse' : listLoading}"
          >
            <table class="min-w-full">
              <thead>
                <tr class="text-left text-zinc-200 bg-zinc-700">
                  <th
                    class="w-48"
                    :class="[...cellClass]"
                  >
                    Категория
                  </th>
                  <th
                    class="w-48"
                    :class="[...cellClass]"
                  >
                    Бренд
                  </th>
                  <th
                    class="w-auto"
                    :class="[...cellClass]"
                  >
                    Модель
                  </th>
                  <th
                    v-for="(filter, filterName) in sortedColumns"
                    :key="filterName"
                    class="w-40"
                    :class="[...cellClass]"
                  >
                    {{ filter.title }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <AppStatsRow
                  v-for="product in list.data"
                  :key="product.id"
                  class="group bg-zinc-100 even:bg-zinc-200 hover:bg-zinc-300"
                  :product="product"
                  :cell-class="cellClass"
                  :columns="sortedColumns"
                />
              </tbody>
            </table>
          </div>
          
          <AppStatsPagination
            :meta="list.meta"
            class="my-4"
            position="bottom"
            :current-page="list.meta.current_page"
            :last-page="list.meta.last_page"
            @update:page="updatePage"
          />
        </div>
        
        <div class="text-center text-gray-500">
          Моделей найдено: {{ list.meta.total }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// vendor
import { useRoute } from 'vue-router';
import { computed, reactive, ref, watch } from 'vue';
import axios from 'axios';

// app
import { formatDataType } from '../formatters/valueFormatters';

// types
import { Attribute } from '../types/Attribute';

// components
import AppPageTitle from './AppPageTitle.vue';
import AppStatsFilters from './AppStatsFilters.vue';
import AppStatsPagination from './AppStatsPagination.vue';
import AppStatsRow from './AppStatsRow.vue';

const route = useRoute();

interface Params {
  filters: object;
  group_slug: string;
  page: number;
}

interface List {
  data: Array<any>;
  dynamicColumns: Array<any>;
  filters: {
    years: Array<any>
    categories: Array<any>
    attributes: Array<any>
    brands: Array<any>
  };
  requestParams: Params;
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
  };
}

const list = ref<List>();

const params = reactive<Params>({
  filters: {},
  group_slug: route.params.group as string,
  page: 1,
});

const pageLoading = ref(false);
const listLoading = ref(false);

const group = ref<{
  title: string
  description: string
  slug: string
}>(null);

const sortedColumns = computed<Attribute[]>(() => {
  return [...list.value.dynamicColumns];
});

watch(() => route.params.group, (newGroupSlug) => {
  pageLoading.value = true;
  
  params.group_slug = newGroupSlug as string;
  
  axios
    .get(`/api/stats/groups/${newGroupSlug}`)
    .then(({ data }) => {
      group.value = data.data;
      
      getList()
        .finally(() => {
          pageLoading.value = false;
        });
    });
}, {
  immediate: true,
});

function getList() {
  listLoading.value = true;
  
  return axios
    .post('/api/stats/products', {
      page: params.page,
      group_slug: params.group_slug,
      ...params.filters,
    })
    .then(({ data }) => {
      list.value = data;
      params.page = list.value.meta.current_page;
    })
    .finally(() => {
      listLoading.value = false;
    });
}

function updateFilters(filters) {
  params.filters = filters;
  
  getList();
}

function updatePage(page) {
  params.page = page;
  
  getList();
}

const cellClass = [
  'py-4', 'px-6',
];

</script>

<style scoped>

</style>
