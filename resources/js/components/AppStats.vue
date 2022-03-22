<template>
  <div class="flex flex-col grow">
    <AppStatsLoading v-if="pageLoading" />
    <template v-else>
      <div
        v-if="group"
        class="mb-6"
      >
        <AppPageTitle :title="group.title" />
        
        <div class="prose-sm lg:prose">
          {{ group.description }}
        </div>
      </div>
      
      <template v-if="list">
        <AppStatsCharts
          v-if="list.chart && list.chart.length"
          class="overflow-auto"
          :selected="selectedChartTabIndex"
          :tabs="list.chart"
          @update:selected="selectedChartTabIndex = $event"
        />
        
        <!--        <pre>{{ JSON.stringify(list.brandsStatsFormatted, null, 2) }}</pre>-->
        
        <AppStatsFilters
          :is-loading="listLoading"
          :request-filters="list.requestFilters"
          :available-filters="list.availableFilters"
          @update:filters="updateFilters"
        />
        
        <AppStatsPagination
          class="my-4"
          position="top"
          :current-page="list.meta.current_page"
          :last-page="list.meta.last_page"
          @update:page="updatePage"
        />
        
        <div class="overflow-hidden rounded-lg shadow-md">
          <div
            class="overflow-auto "
            :class="{'animate-pulse' : listLoading}"
          >
            <table class="min-w-full">
              <thead>
                <AppStatsHeadingRow
                  :cell-class="cellClass"
                  :columns="sortedColumns"
                  :sort="list.requestSort"
                  @change-sort="updateSort"
                />
              </thead>
              <tbody>
                <AppStatsDataRow
                  v-for="(product, index) in list.data"
                  :key="product.id"
                  :product="product"
                  :cell-class="cellClass"
                  :columns="sortedColumns"
                  :row-number="list.meta.from + index"
                  :compared="store.compare[product.id]"
                  @compare="store.toggleCompare(product.id)"
                />
              </tbody>
            </table>
          </div>
        </div>
        
        <AppStatsPagination
          :meta="list.meta"
          class="my-4"
          position="bottom"
          :current-page="list.meta.current_page"
          :last-page="list.meta.last_page"
          @update:page="updatePage"
        />
        
        <div class="text-center text-gray-500">
          Моделей найдено: {{ list.meta.total }}
        </div>
      </template>
    </template>
  </div>
</template>

<script setup lang="ts">
// vendor
import { useRoute } from 'vue-router';
import { computed, reactive, ref, watch } from 'vue';
import axios, { AxiosError } from 'axios';
// import { useStorage } from '@vueuse/core';

// types
import { Attribute } from '../types/Attribute';
import { Sort } from '../types/Sort';
import { RequestParams } from '../types/RequestParams';
import { List } from '../types/List';

// app
import { setTitle } from '../title';

// components
import AppPageTitle from './AppPageTitle.vue';
import AppStatsFilters from './AppStatsFilters.vue';
import AppStatsPagination from './AppStatsPagination.vue';
import AppStatsDataRow from './AppStatsDataRow.vue';
import AppStatsHeadingRow from './AppStatsHeadingRow.vue';
import AppStatsCharts from './AppStatsCharts.vue';
import AppStatsLoading from './AppLoading.vue';
import { useStore } from '../stores/main';

// use composables
const route = useRoute();
// const compare = useStorage('compare', {});
const store = useStore();

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

const requestParams = reactive<RequestParams>({
  filters: {
    groupSlug: route.params.group as string,
  },
  page: 1,
  sort: {
    type: 'title',
    direction: 'asc',
  },
});

const list = ref<List>();

async function getList() {
  listLoading.value = true;
  
  return axios.post('/api/stats/products', requestParams)
    .then(({ data }) => {
      list.value = data;
      requestParams.page = list.value.meta.current_page;
      requestParams.filters = list.value.requestFilters;
    })
    .catch((error: AxiosError) => {
      alert(JSON.stringify(error.response.data, null, 2));
    })
    .finally(() => {
      listLoading.value = false;
    });
}

function updateFilters(filters) {
  requestParams.filters = filters;
  requestParams.page = 1;
  
  getList();
}

function updatePage(page) {
  requestParams.page = page;
  
  getList();
}

function updateSort(sort: Sort) {
  requestParams.sort = sort;
  requestParams.page = 1;
  
  //console.log('handleSort', sort);
  
  getList();
}

watch(() => route.params.group, async (newGroupSlug) => {
  if (newGroupSlug) {
    pageLoading.value = true;
    
    try {
      const response = await axios.get(`/api/stats/groups/${newGroupSlug}`);
      group.value = response.data.data;
      setTitle(response.data.data.title);
      
      requestParams.filters.groupSlug = newGroupSlug as string;
      await getList();
      
    } catch (error) {
      //console.log(error);
    }
    
    selectedChartTabIndex.value = 0;
    pageLoading.value = false;
  }
}, {
  immediate: true,
});

const selectedChartTabIndex = ref(0);

const cellClass = [
  'py-4', 'px-6',
];



</script>

<style scoped>

</style>
