<template>
  <div
    :class="[...classes.root, ...(position === 'top' ? classes.position.top.root : classes.position.bottom.root)]"
  >
    <div :class="classes.row">
      <div :class="[...classes.arrow.default, ...classes.arrow.previous]">
        <ArrowNarrowLeftIcon :class="[...classes.arrow.icon]" />
        
        <div
          :class="[
            ...classes.arrow.text,
            ...(position === 'top' ? classes.position.top.link : classes.position.bottom.link)
          ]"
          @click="handlePreviousPageClick"
        >
          {{ currentPage === 1 ? 'К последней' : 'Предыдущая' }}
        </div>
      </div>
      
      <div class="flex grow justify-center">
        <!-- First page -->
        <div
          v-if="currentPage > 1"
          :class="[
            ...classes.number.default,
            ...(position === 'top' ? classes.position.top.link : classes.position.bottom.link),
            ...classes.number.inactive
          ]"
          @click="emit('update:page', 1)"
        >
          1
        </div>
        
        <!-- Second pages -->
        <div
          v-if="currentPage > 4 && lastPage > 5"
          :class="[...classes.number.default]"
        >
          ...
        </div>
        
        <!-- Previous pages -->
        <template
          v-if="previousPagesCount > 0"
        >
          <div
            v-for="n in previousPagesCount"
            :key="n"
            :class="[
              ...classes.number.default,
              ...(position === 'top' ? classes.position.top.link : classes.position.bottom.link),
              ...classes.number.inactive
            ]"
            @click="emit('update:page', getPreviousPageNumber(n))"
          >
            {{ getPreviousPageNumber(n) }}
          </div>
        </template>
        
        <!-- Current page -->
        <div
          :class="[
            ...classes.number.default,
            ...(position === 'top' ? classes.position.top.link : classes.position.bottom.link),
            ...classes.number.active
          ]"
        >
          {{ currentPage }}
        </div>
        
        <!-- Next pages -->
        <template
          v-if="currentPage < lastPage"
        >
          <div
            v-for="n in nextPagesCount"
            :key="n"
            :class="[
              ...classes.number.default,
              ...(position === 'top' ? classes.position.top.link : classes.position.bottom.link),
              ...classes.number.inactive
            ]"
            @click="emit('update:page', n + currentPage)"
          >
            {{ n + currentPage }}
          </div>
        </template>
        
        
        <!-- One before last pages  -->
        <div
          v-if="lastPage - currentPage >= 4 && lastPage > 5"
          :class="[...classes.number.default]"
        >
          ...
        </div>
        
        <!-- Last page -->
        <template
          v-if="lastPage - currentPage > 0"
        >
          <div
            :class="[
              ...classes.number.default,
              ...(position === 'top' ? classes.position.top.link : classes.position.bottom.link),
              ...classes.number.inactive
            ]"
            @click="emit('update:page', lastPage)"
          >
            {{ lastPage }}
          </div>
        </template>
      </div>
      
      <div :class="[...classes.arrow.default, ...classes.arrow.next]">
        <ArrowNarrowRightIcon :class="[...classes.arrow.icon]" />
        
        <div
          :class="[...classes.arrow.text, ...(position === 'top' ? classes.position.top.link : classes.position.bottom.link)]"
          @click="handleNextPageClick"
        >
          {{ currentPage === lastPage ? 'К первой' : 'Следующая' }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ArrowNarrowLeftIcon, ArrowNarrowRightIcon } from '@heroicons/vue/solid';
import { computed, reactive } from 'vue';

const props = defineProps<{
  position: 'top' | 'bottom'
  currentPage: number
  lastPage: number
}>();

const emit = defineEmits<{
  (e: 'update:page', page: number): void;
}>();

const nearPages = 2;

const previousPagesCount = computed(() => {
  if (props.currentPage > 2 && props.lastPage - props.currentPage > 2) {
    return Math.min(2, props.currentPage - 2);
  }
  
  if (props.lastPage - props.currentPage <= 2) {
    return Math.min(props.lastPage - 2, 5) - props.lastPage + props.currentPage;
  }
  
  return 0;
});

const nextPagesCount = computed(() => {
  if (props.lastPage - props.currentPage > 1 && props.currentPage > 2) {
    return Math.min(2, props.lastPage - props.currentPage - 1);
  }
  
  if (props.currentPage <= 2) {
    return Math.min(props.lastPage - 1, 5) - props.currentPage;
  }
  
  return 0;
});

function getPreviousPageNumber(n) {
  return props.currentPage - previousPagesCount.value + n - 1;
}

function handleNextPageClick() {
  emit('update:page', props.lastPage > props.currentPage ? props.currentPage + 1 : 1);
}

function handlePreviousPageClick() {
  emit('update:page', props.currentPage > 1 ? props.currentPage - 1 : props.lastPage);
}

const classes = reactive({
  root: ['select-none', 'mx-3'],
  row: ['flex', 'flex-row', 'gap-6', '-mb-px'],
  number: {
    default: ['py-3', 'px-5', 'font-bold'],
    active: ['text-teal-600', 'border-teal-600'],
    inactive: ['cursor-pointer', 'hover:border-teal-600', 'border-transparent'],
  },
  arrow: {
    previous: ['flex-ro'],
    next: ['flex-row-reverse'],
    default: ['group', 'flex', 'flex-row', 'flex-none', 'gap-4', 'items-center', 'cursor-pointer', 'w-50'],
    text: ['py-3', 'border-transparent', 'group-hover:border-teal-600', 'cursor-pointer', 'flex-none'],
    icon: ['flex-none', 'my-3', 'w-5', 'h-5'],
  },
  position: {
    top: {
      link: ['border-b-2'],
      root: ['border-b'],
    },
    bottom: {
      link: ['border-t-2'],
      root: ['border-t'],
    },
  },
});

</script>

<style scoped>

</style>
