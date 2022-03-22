<template>
  <tr
    class="bg-zinc-100 even:bg-zinc-200 hover:bg-zinc-300"
  >
    <td class="py-4 px-2 pl-4">
      <a
        href="#"
        class="inline-flex justify-center items-center w-10 h-10 rounded-lg shadow-sm"
        :class="{'bg-zinc-700 text-white hover:bg-teal-600': !compared, 'bg-white hover:bg-teal-300 text-teal-600 hover:text-white': compared}"
        @click.prevent="toggleCompare"
      >
        <ScaleIcon class="flex-none w-4 h-4" />
      </a>
    </td>
    <td :class="['text-zinc-500 text-sm text-right pl-4']">
      {{ rowNumber }}
    </td>
    <td
      :class="[...cellClass]"
    >
      {{ product.category.title }}
    </td>
    <td
      :class="[...cellClass]"
    >
      {{ product.brand.title }}
    </td>
    <td
      class=""
      :class="[...cellClass]"
    >
      <div
        class="min-w-[15rem] hover:line-clamp-none lg:line-clamp-2"
      >
        {{ product.title }}
      </div>
    </td>
    <td
      v-for="attribute in columns"
      :key="attribute.id"
      :class="[...cellClass]"
    >
      <template v-if="attribute.dataType === 'comment' && getAttributeValue(product, attribute).toString()">
        <AppStatsComment :text="getAttributeValue(product, attribute).toString()" />
      </template>
      
      <div
        v-else-if="attribute.dataType === 'country'"
        class="flex gap-4 items-center"
      >
        <country-flag
          class="block flex-none !my-0"
          :country="getAttributeValue(product, attribute).toString()"
        />
        
        <span>
          {{ getCountryName(getAttributeValue(product, attribute)
            .toString())
          }}
        </span>
      </div>
      
      <template v-else>
        {{
          getAttributeValue(product, attribute)
            .format()
        }}
      </template>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { Attribute } from '../types/Attribute';
import { Product } from '../types/Product';
import { formatDataType } from '../formatters/valueFormatters';
import CountryFlag from 'vue-country-flag-next';
import AppStatsComment from './AppStatsComment.vue';
import { Value } from '../types/Value';
import { ScaleIcon } from '@heroicons/vue/outline';
import { computed, reactive, ref } from 'vue';


const props = defineProps<{
  product: Product;
  columns: Attribute[];
  cellClass: string[];
  rowNumber: number;
  compared?: boolean;
}>();

const emit = defineEmits<{
  (e: 'compare'): void;
}>();

function getProductAttribute(product, attribute): Value | undefined {
  return product.valuesByAttributeId[attribute.id];
}

function getAttributeValue(product: Product, attribute: Attribute) {
  let result = {
    format: null,
  };
  
  const value = getProductAttribute(product, attribute)?.value;
  
  result.format = () => {
    if (value) {
      return formatDataType(attribute.dataType, value);
    } else {
      return '';
    }
  };
  
  result.toString = function () {
    return value;
  };
  
  return result;
}

function getCountryName(code) {
  if (!code) {
    return '';
  }
  
  const codeTrimmed = code.trim();
  
  try {
    return new Intl.DisplayNames(['ru'], { type: 'region' }).of(codeTrimmed);
  } catch (e) {
    console.log(e);
  }
  
  return codeTrimmed;
}

const toggleCompare = () => {
  emit('compare');
}

</script>

<style scoped>

</style>
