<template>
  <div
    class="flex flex-row gap-3 items-center "
  >
    <div
      class="group flex flex-row gap-3 items-center cursor-pointer"
      @click="handleSort"
    >
      <div class="">
        <ChevronUpIcon
          class="-mb-1 w-4 h-4 group-hover:scale-150"
          :class="{'fill-teal-300': sorted === 'desc'}"
        />
        <ChevronDownIcon
          class="-mt-1 w-4 h-4 group-hover:scale-150"
          :class="{'fill-teal-300': sorted === 'asc'}"
        />
      </div>
      
      <div>
        {{ title }}
      </div>
    </div>
    
    <div v-if="description">
      <div class="group relative cursor-help">
        <QuestionMarkCircleIcon
          class="w-5 h-5 "
        />
        <div
          class="hidden group-hover:block overflow-hidden absolute top-0 right-0 -left-64 z-50 p-5 max-h-64 text-sm text-zinc-100 bg-zinc-800 rounded shadow-lg"
        >
          {{ title }}: <span class="font-normal">{{ lowerCaseFirstLetter(description) }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ChevronDownIcon, ChevronUpIcon, QuestionMarkCircleIcon } from '@heroicons/vue/outline';
import { Attribute } from '../types/Attribute';
import { Sort } from '../types/Sort';

const props = defineProps<{
  title: string
  sorted: false | 'asc' | 'desc'
  description?: Attribute['description']
}>();

const emit = defineEmits<{
  (e: 'toggleSort', direction: Sort['direction']): void
}>();

function handleSort() {
  emit('toggleSort', props.sorted === 'asc' ? 'desc' : 'asc');
}

function lowerCaseFirstLetter(string) {
  return string.charAt(0).toLowerCase() + string.slice(1);
}

</script>

<style scoped>

</style>
