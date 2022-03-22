<template>
  <div class="flex flex-col grow gap-4">
    <AppPageTitle title="Сравнение" />
    
    <AppLoading v-if="loading" />
    
    <div
      v-else
    >
      <div v-if="!products.length">
        Товары не добавлены в сравнение
      </div>
      
      <div
        v-else
        class="sticky top-24"
      >
        <div class="overflow-auto bg-zinc-200 rounded">
          <div class="inline-block">
            <div class="grid sticky top-0 grid-flow-col auto-cols-max py-4 bg-zinc-100">
              <div class="flex flex-col gap-4 px-4 w-80 h-full">
                <label class="flex gap-2 items-center">
                  <input
                    v-model="filterType"
                    type="radio"
                    value="all"
                    class="w-6 h-6 text-teal-400 rounded"
                  >
                  <span>
                    Все характеристики
                  </span>
                </label>
                
                <label class="flex gap-2 items-center">
                  <input
                    v-model="filterType"
                    type="radio"
                    value="difference"
                    class="w-6 h-6 text-teal-400 rounded"
                  >
                  <span>
                    Только различия
                  </span>
                </label>
                
                <a
                  href="#"
                  class="flex gap-1 items-center mt-auto text-zinc-600 hover:text-teal-600"
                  @click.prevent="clearList"
                >
                  <TrashIcon class="w-4 h-4" />
                  Очистить список
                </a>
              </div>
              <div
                v-for="product in products"
                :key="product.id"
                class="w-64"
              >
                <div class="mb-4 text-2xl font-bold text-zinc-400">
                  {{ product.brand.title }}
                </div>
                
                <div class="mb-4 text-lg">
                  {{ product.title }}
                </div>
                
                <a
                  href="#"
                  class="flex gap-1 items-center text-zinc-600 hover:text-teal-600"
                  @click.prevent="removeProduct(product)"
                >
                  <TrashIcon class="w-4 h-4" />
                  Убрать
                </a>
              </div>
            </div>
            
            <div class="grid grid-cols-1 divide-y divide-slate-400">
              <div
                v-for="attribute in filteredAttributes"
                :key="attribute.id"
                class="grid grid-flow-col auto-cols-max items-center"
              >
                <div class="flex gap-4 w-80">
                  <div class="py-3 px-4 w-60">
                    {{ attribute.title }}
                  </div>
                  <div
                    v-if="attribute.byYear"
                    class="flex-none w-20 divide-y divide-slate-400"
                  >
                    <div
                      v-for="year in years"
                      :key="year.id"
                      class="py-3"
                    >
                      {{ year.value }}г.
                    </div>
                  </div>
                </div>
                
                <div
                  v-for="product in products"
                  :key="product.id"
                  class="w-64"
                >
                  <template v-if="!attribute.byYear && product.valuesByAttributeIdAndYearId[attribute.id]">
                    {{ formatDataType(attribute.dataType, getValue(attribute, product)) }}
                  </template>
                  <div
                    v-else-if="attribute.byYear"
                    class="divide-y divide-slate-400"
                  >
                    <div
                      v-for="year in years"
                      :key="year.id"
                      class="py-3"
                    >
                      {{ formatDataType(attribute.dataType, getValueByYear(year, attribute, product)) }}
                    </div>
                  </div>
                  <div v-else>
                    —
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import AppPageTitle from './AppPageTitle.vue';
import { setTitle } from '../title';
import AppLoading from './AppLoading.vue';
import { Product } from '../types/Product';
import { Attribute } from '../types/Attribute';
import { useStore } from '../stores/main';
import { Year } from '../types/Year';
import { formatDataType } from '../formatters/valueFormatters';
import { TrashIcon } from '@heroicons/vue/outline';

setTitle('Сравнение');

const router = useRouter();
const route = useRoute();
const store = useStore();

const filterType = ref<'all' | 'difference'>('all');
const loading = ref(false);
const attributes = ref<Attribute[]>([]);
const products = ref<Product[]>([]);
const years = ref<Year[]>([]);

watch(
  () => route.query.p as string[],
  (productsIds) => {
    console.log(productsIds);
    
    if (!productsIds || !productsIds.length) {
      products.value = [];
      return;
    }
    
    if (!Array.isArray(productsIds)) {
      productsIds = [productsIds];
    }
    
    store.compare = {};
    
    productsIds.forEach((item) => {
      store.toggleCompare(item);
    });
    
    loading.value = true;
    axios
      .get(`/api/products/`, {
        params: {
          ids: productsIds,
        },
      })
      .then((response) => {
        if (!response || !response.data || !response.data.data) {
          throw new Error();
        }
        
        years.value = response.data.years;
        attributes.value = response.data.attributes;
        products.value = response.data.data;
        
        console.log(response);
      })
      .catch(() => {
        router.push({
          name: 'NotFound',
          params: {
            pathMatch: route?.path.substring(1)
              .split('/'),
          },
          query: route?.query,
          hash: route?.hash,
        });
      })
      .finally(() => {
        loading.value = false;
      });
  },
  {
    immediate: true,
  },
);

const removeProduct = (product: Product) => {
  store.toggleCompare(product.id);
  
  router.push({
    name: 'compare',
    query: {
      p: store.compareIds,
    },
  });
};

const clearList = () => {
  store.compare = {};
  
  router.push({
    name: 'compare',
    query: {
      p: store.compareIds,
    },
  });
};

const filteredAttributes = computed(() => {
  return attributes.value.filter((attribute) => {
    let hasValues = false;
    let tempValue = undefined;
    let hasDifference = false;
    
    products.value.forEach((product) => {
      if (attribute.byYear) {
        
        let allYearsValues = [];
        
        years.value.forEach((year) => {
          if (product.valuesByAttributeIdAndYearId[attribute.id + '-' + year.id]) {
            hasValues = true;
            allYearsValues.push(product.valuesByAttributeIdAndYearId[attribute.id + '-' + year.id].value);
            return false;
          }
        });
        
        if (tempValue === undefined) {
          tempValue = allYearsValues.join('-')
        } else {
          if (tempValue !== allYearsValues.join('-')) {
            hasDifference = true;
          }
        }
        
      } else {
        if (product.valuesByAttributeIdAndYearId[attribute.id]) {
          hasValues = true;
          
          if (tempValue === undefined) {
            tempValue = product.valuesByAttributeIdAndYearId[attribute.id].value;
          } else {
            if (tempValue !== product.valuesByAttributeIdAndYearId[attribute.id].value) {
              hasDifference = true;
            }
          }
          
          return false;
        }
      }
    });
    
    // if (attribute.id === 6) {
    //   console.log(attribute, hasDifference, tempValue)
    // }
    
    
    if (filterType.value === 'all') {
      return hasValues;
    }
    
    if (filterType.value === 'difference') {
      return hasValues && hasDifference;
    }
  }).sort((a, b) => {
    return a.title.localeCompare(b.title)
  });
});

const getValue = (attribute, product) => {
  return product.valuesByAttributeIdAndYearId[attribute.id]?.value || '—'
}

const getValueByYear = (year, attribute, product) => {
  return product.valuesByAttributeIdAndYearId[attribute.id + '-' + year.id]?.value || '—'
}

</script>

<style scoped>
.compare-container {
  height: calc(100vh - 6rem)
}

</style>
