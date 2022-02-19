<template>
  <div class="mb-5 bg-zinc-200 rounded">
    <div class="flex flex-col divide-y divide-zinc-300 lg:flex-row lg:divide-0">
      <div
        v-for="(tab, index) in tabs"
        :key="index"
        class="py-2 px-3 text-sm font-bold hover:text-teal-700 cursor-pointer lg:text-base"
        :class="{'text-zinc-500': index !== selected}"
        @click="changeTab(index)"
      >
        {{ tab.attribute.title }}
      </div>
    </div>
    
    <div class="grid grid-cols-1 gap-4 p-3 bg-zinc-100 rounded lg:grid-cols-2 lg:gap-12 lg:p-6">
      <BarChart
        :ref="barChartRef"
        :chart-data="chartData"
        :options="chartOptions"
      />
      <LineChart
        :ref="lineChartRef"
        :chart-data="chartData"
        :options="chartOptions"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core';

import { Chart, ChartData, ChartOptions, registerables } from 'chart.js';
import { BarChart, ExtractComponentData, LineChart } from 'vue-chart-3';
import ChartDataLabels from 'chartjs-plugin-datalabels';
import { formatDataType, formatPercent } from '../formatters/valueFormatters';
import { Attribute } from '../types/Attribute';

interface Emits {
  (e: 'update:selected', index: number): void;
}

const props = defineProps<{
  selected: number,
  tabs: {
    brands: string[],
    values: number[],
    attribute: Attribute
  }[]
}>();

const emit = defineEmits<Emits>();

Chart.register(...registerables);
Chart.register(ChartDataLabels);


const attribute = computed(() => {
  return props.tabs[props.selected].attribute;
});

const chartData = computed<ChartData>(() => ({
  labels: props.tabs[props.selected].brands.slice(0, chartItemsMax.value),
  
  datasets: [
    {
      data: props.tabs[props.selected].values.slice(0, chartItemsMax.value),
      backgroundColor: [
        '#b91c1c',
        '#be123c',
        '#be185d',
        '#a21caf',
        '#7e22ce',
        '#6d28d9',
        '#4338ca',
        '#1d4ed8',
        '#1e40af',
        '#1e3a8a',
        '#1e3a8a',
        '#1e3a8a',
        '#1e3a8a',
      ],
      borderColor: '#52525b',
    },
  ],
}));

const breakpoints = useBreakpoints(breakpointsTailwind);

const chartItemsMax = computed(() => {
  return breakpoints.isGreater('md') ? 10 : 5;
})

const chartOptions = computed<ChartOptions<'line' | 'bar'>>(() => ({
  responsive: true,
  plugins: {
    legend: {
      display: false,
    },
    datalabels: {
      formatter(value) {
        return formatDataType(attribute.value.dataType, value);
      },
      backgroundColor: '#27272a',
      borderRadius: 5,
      color: '#ffffff',
    },
    tooltip: {
      callbacks: {
        label: (context) => {
          const value = context.raw as number || 0;
          
          return formatDataType(attribute.value.dataType, value);
        },
      },
      yAlign: 'bottom',
    },
  },
  elements: {
    point: {
      radius: 6,
      borderWidth: 0,
    },
  },
  scales: {
    y: {
      ticks: {
        callback: function (value: any) {
          return formatDataType(attribute.value.dataType, value);
        },
      },
    },
  },
}));


function changeTab(index) {
  emit('update:selected', index);
}

const lineChartRef = ref<ExtractComponentData<typeof BarChart>>();
const barChartRef = ref<ExtractComponentData<typeof LineChart>>();

</script>

<style scoped>

</style>
