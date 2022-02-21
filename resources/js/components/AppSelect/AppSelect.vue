<template>
  <div
    v-click-outside="closeDropdown"
    :class="classes.root"
  >
    <div
      :class="classes.label"
      @click="toggleDropdown()"
    >
      <div :class="classes.labelTitle">
        <span>
          {{ title }}
        </span>
        
        <span
          v-if="props.modelValue && props.modelValue.length > 0 && props.multiple"
          :class="classes.labelCounter"
        >
          <span
            :class="classes.labelCounterNumber"
          >
            {{ modelValue.length }}
          </span>
        </span>
        
        <span
          v-if="props.modelValue && !props.multiple"
          :class="classes.labelCounterIcon"
        />
      </div>
      
      <ChevronDownIcon
        v-if="!isLoading"
        :class="[
          ...classes.labelChevronIcon,
          ...conditionalClasses(isDropdownOpen, classes.labelChevronIconOpen),
        ]"
      />
      
      <svg
        v-if="isLoading"
        :class="[...classes.labelLoadingIcon]"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        />
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
        />
      </svg>
    </div>
    
    <div
      v-if="isDropdownOpen"
      :class="classes.dropdown"
    >
      <div :class="classes.options">
        <div
          v-for="option in props.options"
          :key="getOptionValue(option)"
          :class="classes.option"
          @click="toggleSelectOption(option)"
        >
          <div :class="classes.optionLabel">
            {{ getOptionLabel(option) }}
          </div>
          
          <CheckIcon
            v-if="isOptionActive(option)"
            :class="classes.optionIcon"
          />
          <div
            v-if="!isOptionActive(option)"
            :class="classes.optionIcon"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, reactive, ref, watch } from 'vue';
import { ChevronDownIcon, CheckCircleIcon, CheckIcon } from '@heroicons/vue/outline';

// @ts-ignore
import { directive as vClickOutside } from 'click-outside-vue3';

import { Classes } from './Classes';

const props = defineProps<{
  multiple?: boolean;
  options: any[];
  title: string;
  modelValue: any;
  optionLabel: ((option) => string) | string;
  optionValue: string;
  classes?: Classes;
  isLoading?: boolean
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', modelValue: any): void;
  (e: 'change', modelValue: any | null): void;
}>();

function conditionalClasses(condition: boolean, classes: string[]): string[] {
  return condition ? classes : [];
}

const isDropdownOpen = ref(false);

function openDropdown() {
  if (props.isLoading) return false;
  
  isDropdownOpen.value = true;
}

function closeDropdown() {
  isDropdownOpen.value = false;
}

function toggleDropdown() {
  isDropdownOpen.value ? closeDropdown() : openDropdown();
}

function getOptionLabel(option): string {
  if (typeof props.optionLabel === 'string') {
    return option[props.optionLabel];
  }
  
  if (typeof props.optionLabel === 'function') {
    return props.optionLabel(option);
  }
}

function getOptionValue(option): string | number {
  return option[props.optionValue];
}

const sortedOptions = computed(() => {
  return [];
});

function isOptionActive(option) {
  const selected = props.modelValue;
  const optionValue = getOptionValue(option);
  
  if (!props.multiple) {
    return selected === optionValue;
  } else {
    if (Array.isArray(selected)) {
      return selected.indexOf(optionValue) > -1;
    } else {
      return false;
    }
  }
}

function emitUpdate(value?) {
  emit('update:modelValue', value);
  emit('change', value);
}

function toggleSelectOption(option) {
  // closeDropdown()
  let selected = props.modelValue;
  const optionValue = getOptionValue(option);
  
  if (!props.multiple) {
    // if single option result
    
    if (optionValue !== selected) {
      emitUpdate(optionValue);
    } else {
      emitUpdate();
    }
  } else {
    // if multiply options result
    if (!Array.isArray(selected)) {
      selected = [];
    }
    
    const selectedIndex = selected.indexOf(optionValue);
    
    if (selectedIndex > -1) {
      // remove existing item
      selected.splice(selectedIndex, 1);
    } else {
      // add new item
      selected.push(optionValue);
    }
    
    emitUpdate(selected);
  }
}

watch(() => props.isLoading, (isLoading) => {
  if (isLoading) {
    closeDropdown();
  }
});

</script>

<style scoped>

</style>
