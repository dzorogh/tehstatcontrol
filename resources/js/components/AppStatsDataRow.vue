<template>
  <tr
    class="group bg-zinc-100 even:bg-zinc-200 hover:bg-zinc-300"
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
      :class="[...cellClass]"
    >
      {{ product.title }}
    </td>
    <td
      v-for="attribute in columns"
      :key="attribute.id"
      :class="[...cellClass]"
    >
      <div v-if="attribute.dataType === 'comment'">
        {{ getAttributeValue(product, attribute).toString() }}
      </div>
      
      <div
        v-if="attribute.dataType === 'country'"
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
      
      <div v-else>
        {{ getAttributeValue(product, attribute).format() }}
      </div>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { Attribute } from '../types/Attribute';
import { Product } from '../types/Product';
import { formatDataType } from '../formatters/valueFormatters';
import CountryFlag from 'vue-country-flag-next';

defineProps<{
  product: Product;
  columns: Attribute[];
  cellClass: string[];
  rowNumber: number;
}>();

function getAttributeValue(product: Product, attribute: Attribute) {
  let result = {
    format: null
  };
  
  const value = product.valuesByAttributeId[attribute.id]?.value;
  
  result.format = () => {
    if (value) {
      return formatDataType(attribute.dataType, value);
    } else {
      return '';
    }
  }
  
  result.toString = function () {
    return value;
  }
  
  return result
}

function getCountryName(code) {
  return new Intl.DisplayNames(['ru'], { type: 'region' }).of(code);
}

</script>

<style scoped>

</style>
