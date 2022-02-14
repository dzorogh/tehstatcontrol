<template>
  <div
    class="group flex flex-row gap-3 items-center cursor-pointer"
    @click="handleSort"
  >
    <div class="">
      <ChevronUpIcon
        class="-mb-1 w-4 h-4 group-hover:scale-150"
        :class="{'fill-teal-300': sorted === 'asc'}"
      />
      <ChevronDownIcon
        class="-mt-1 w-4 h-4 group-hover:scale-150"
        :class="{'fill-teal-300': sorted === 'desc'}"
      />
    </div>
    
    {{ title }}
  </div>
</template>

<script setup lang="ts">
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/solid';
import { Attribute } from '../types/Attribute';
import { Sort } from '../types/Sort';

const props = defineProps<{
  title: string
  sorted: false | 'asc' | 'desc'
}>();

const emit = defineEmits<{
  (e: 'toggleSort', direction: Sort['direction']): void
}>()

function handleSort() {
  emit('toggleSort', props.sorted === 'asc' ? 'desc' : 'asc')
}

</script>

<style scoped>

</style>
