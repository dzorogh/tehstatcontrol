<template>
  {{ selectedFilters }}
  <div class="flex flex-row gap-4 mb-4">
    <div class="w-64">
      <AppSelect
        v-model="selectedFilters.year"
        :options="filters.years"
        title="Год"
        option-label="value"
        option-value="id"
        :classes="selectClasses"
      />
    </div>
    <div class="w-64">
      <AppSelect
        v-model="selectedFilters.category"
        :options="filters.categories"
        title="Тип техники"
        option-label="title"
        option-value="id"
        :classes="selectClasses"
      />
    </div>
    <div class="w-64">
      <AppSelect
        v-model="selectedFilters.brands"
        :options="filters.brands"
        :multiple="true"
        title="Бренды"
        option-label="title"
        option-value="id"
        :classes="selectClasses"
      />
    </div>
  </div>
  <div
    v-if="sortedFilterAttributes.length"
    class="flex flex-row flex-wrap gap-4"
  >
    <div
      v-for="attribute in sortedFilterAttributes"
      :key="attribute.id"
    >
      <AppSelect
        :model-value="getAttributeFilter(attribute.id)"
        :options="attribute.values"
        :title="attribute.title"
        
        :option-label="getAttributeOptionLabel.bind(null, attribute)"
        option-value="value"
        :multiple="true"
        :classes="selectClasses"
        
        @update:model-value="setAttributeFilter($event, attribute.id)"
      />
    </div>
  </div>
  <div
    v-if="hasFilters"
    class="mt-4"
  >
    <button class="inline-flex items-center py-1 px-3 text-zinc-100 bg-teal-600 hover:bg-teal-500 rounded cursor-pointer select-none">
      <TrashIcon class="mr-2 w-4 h-4" />
      Сбросить фильтры
    </button>
  </div>
</template>

<script setup lang="ts">
import { TrashIcon } from '@heroicons/vue/solid';
import { computed, reactive, ref, watch } from 'vue';
import { AppSelect, defaultClasses, Classes } from './AppSelect';
import { Attribute } from '../types/Attribute';
import { Filters } from '../types/Filters';
import { Value } from '../types/Value';
import { formatDataType } from '../formatters/valueFormatters';

interface Props {
  filters: Filters;
}

interface Emits {
  (e: 'update:filters', filters: Filters): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const selectClasses: Classes = defaultClasses;

const sortedFilterAttributes = computed(() => {
  return [...props.filters.attributes].sort((a, b) => {
    return a.groupId - b.groupId || a.order - b.order || a.title.localeCompare(b.title);
  });
});

const emptyFilters = {
  brands: [],
  year: null,
  attributes: [],
  category: null,
};

const selectedFilters = reactive(emptyFilters);

function getAttributeFilter(attributeId) {
  let result = [];
  
  selectedFilters.attributes.forEach(item => {
    if (item.id && item.id === attributeId) {
      result = item.value;
    }
  });
  
  return result;
}

function setAttributeFilter(values, attributeId) {
  let exists = false;
  
  console.log(values, attributeId);
  
  selectedFilters.attributes.forEach(item => {
    if (item.id === attributeId) {
      item.value = values;
      exists = true;
    }
  });
  
  if (!exists) {
    selectedFilters.attributes.push({
      id: attributeId,
      value: values,
    });
  }
}

watch(selectedFilters, filters => {
  applyFilters(filters);
});

function applyFilters(filters) {
  emit('update:filters', filters);
}

function getAttributeOptionLabel(attribute: Attribute, option: Value) {
  return formatDataType(attribute.dataType, option.value);
}

const hasFilters = computed(() => {
  return true;
});

</script>

<style scoped>

</style>
