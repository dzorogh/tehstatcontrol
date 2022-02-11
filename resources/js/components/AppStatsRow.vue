<template>
  <tr
    class="group bg-zinc-100 even:bg-zinc-200 hover:bg-zinc-300"
  >
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
        {{ getAttributeValue(product, attribute) }}
      </div>
      
      <div v-else>
        {{ getAttributeValue(product, attribute) }}
      </div>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { Attribute } from '../types/Attribute';
import { Product } from '../types/Product';
import { formatDataType } from '../formatters/valueFormatters';

defineProps<{
  product: Product;
  columns: Attribute[];
  cellClass: string[];
}>();


function getAttributeValue(product: Product, attribute: Attribute) {
  const value = product.valuesByAttributeId[attribute.id]?.value;
  
  if (value) {
    return formatDataType(attribute.dataType, value);
  } else {
    return null;
  }
}

</script>

<style scoped>

</style>
