<template>
  <div>
    <AppStatsLoading v-if="pageLoading" />
    
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
      
      <div v-if="list && list.data.length">
        <AppStatsCharts
          v-if="list.chart"
          :selected="selectedChartTabIndex"
          :tabs="list.chart"
          @update:selected="selectedChartTabIndex = $event"
        />
        
        <!--        <pre>{{ JSON.stringify(list.brandsStatsFormatted, null, 2) }}</pre>-->
        
        <AppStatsFilters
          :filters="list.filters"
          @update:filters="updateFilters"
        />
        
        <div>
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
                <AppStatsHeadingRow
                  :cell-class="cellClass"
                  :columns="sortedColumns"
                  :sort="list.sort"
                  @change-sort="handleSort"
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
import { LineChart, BarChart, ExtractComponentData } from 'vue-chart-3';
import { Chart, ChartData, ChartOptions, registerables } from 'chart.js';
import ChartDataLabels from 'chartjs-plugin-datalabels';

// app
import { formatDataType, formatPercent } from '../formatters/valueFormatters';

// types
import { Attribute } from '../types/Attribute';

// components
import AppPageTitle from './AppPageTitle.vue';
import AppStatsFilters from './AppStatsFilters.vue';
import AppStatsPagination from './AppStatsPagination.vue';
import AppStatsDataRow from './AppStatsDataRow.vue';
import AppStatsHeadingRow from './AppStatsHeadingRow.vue';
import AppStatsCharts from './AppStatsCharts.vue';
import { Sort } from '../types/Sort';
import { Filters } from '../types/Filters';
import { Meta } from '../types/Meta';
import AppStatsLoading from './AppStatsLoading.vue';
import { useStore } from '../store';
import { setTitle } from '../title';

const route = useRoute();
const store = useStore();

interface Params {
  filters: object;
  group_slug: string;
  page: number;
  sort: Sort
}

interface List {
  data: Array<any>;
  dynamicColumns: Array<any>;
  filters: Filters;
  requestParams: Params;
  meta: Meta;
  chart: {
    attribute: Attribute
    brands: string[]
    values: number[]
  }[];
  sort: Sort
}

const list = ref<List>();

const params = reactive<Params>({
  filters: {},
  group_slug: route.params.group as string,
  page: 1,
  sort: {
    type: 'title',
    direction: 'asc'
  }
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
  if (newGroupSlug) {
    pageLoading.value = true;
  
    params.group_slug = newGroupSlug as string;
  
    axios
      .get(`/api/stats/groups/${newGroupSlug}`)
      .then(({ data }) => {
        group.value = data.data;
        
        setTitle(data.data.title);
        
        getList()
          .finally(() => {
            selectedChartTabIndex.value = 0;
            pageLoading.value = false;
          });
      });
  }
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
      sort: params.sort
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
  params.page = 1;
  
  getList();
}

function updatePage(page) {
  params.page = page;
  
  getList();
}

const cellClass = [
  'py-4', 'px-6',
];

const selectedChartTabIndex = ref(0);

function handleSort(sort: Sort) {
  params.sort = sort;
  
  console.log('handleSort', sort)
  
  getList();
}

</script>

<style scoped>

</style>
