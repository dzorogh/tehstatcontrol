<template>
  <tr
    class="bg-zinc-100 even:bg-zinc-200 hover:bg-zinc-300"
  >
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
          {{ getCountryName(getAttributeValue(product, attribute).toString()) }}
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

defineProps<{
  product: Product;
  columns: Attribute[];
  cellClass: string[];
  rowNumber: number;
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
  
  return new Intl.DisplayNames(['ru'], { type: 'region' }).of(code);
}

</script>

<style scoped>

</style>
