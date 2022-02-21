<template>
  <tr class="text-left text-zinc-200 bg-zinc-700">
    <th
      :class="['text-right', 'w-5']"
    />
    <th
      class=" w-48"
      :class="[...cellClass]"
    >
      <AppStatsHeadingTitle
        :sorted="sort.type === 'category' ? sort.direction : false"
        title="Тип техники"
        @toggle-sort="emit('changeSort', {type: 'category', direction: $event})"
      />
    </th>
    <th
      class="w-36"
      :class="[...cellClass]"
    >
      <AppStatsHeadingTitle
        :sorted="sort.type === 'brand' ? sort.direction : false"
        title="Бренд"
        @toggle-sort="emit('changeSort', {type: 'brand', direction: $event})"
      />
    </th>
    <th
      class="w-auto"
      :class="[...cellClass]"
    >
      <AppStatsHeadingTitle
        :sorted="sort.type === 'title' ? sort.direction : false"
        title="Модель"
        @toggle-sort="emit('changeSort', {type: 'title', direction: $event})"
      />
    </th>
    <th
      v-for="(attribute, filterName) in columns"
      :key="filterName"
      class="w-40"
      :class="[...cellClass]"
    >
      <AppStatsHeadingTitle
        :sorted="sort.type === 'attribute' && sort.attributeId === attribute.id ? sort.direction : false"
        :title="attribute.title"
        :description="attribute.description"
        @toggle-sort="emit('changeSort', {type: 'attribute', attributeId: attribute.id, direction: $event})"
      />
    </th>
  </tr>
</template>

<script setup lang="ts">
import { Attribute } from '../types/Attribute';
import AppStatsHeadingTitle from './AppStatsHeadingTitle.vue';
import { Sort } from '../types/Sort';

defineProps<{
  columns: Attribute[];
  sort: Sort;
  cellClass: string[];
}>();

const emit = defineEmits<{
  (e: 'changeSort', sort: Sort): void
}>()

</script>

<style scoped>

</style>
