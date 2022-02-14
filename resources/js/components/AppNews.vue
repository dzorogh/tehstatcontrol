<template>
  <h2 class="mb-7 text-2xl font-bold">
    Новости
  </h2>

  <div
    v-if="!news"
    class="grid grid-cols-3 gap-10 animate-pulse"
  >
    <div
      v-for="n in 3"
      :key="n"
      class="block bg-zinc-100 shadow-2xl transition-all hover:scale-105"
    >
      <span class="aspect-video block w-full bg-gray-700" />

      <span class="block p-5">

        <span class="block mb-5 w-4/5 h-5 font-bold bg-gray-700" />
        <span class="block mb-3 w-3/5 h-4 font-bold bg-gray-700" />
        <span class="block w-3/5 h-4 font-bold bg-gray-700" />

        <span class="block mt-4 text-right">
          <span class="inline-block py-2 px-3 w-20 h-10 text-zinc-100 bg-teal-500 rounded" />
        </span>

      </span>
    </div>
  </div>
  <div
    v-if="news"
    class="grid grid-cols-3 gap-10"
  >
    <router-link
      v-for="newsItem in news"
      :key="newsItem.id"
      :to="{ name: 'page', params: { slug: newsItem.slug } }"
      class="flex flex-col bg-zinc-100 shadow-2xl transition-all hover:scale-105"
    >
      <span class="aspect-video block w-full">
        <img :src="newsItem.image">
      </span>

      <span class=" flex flex-col p-5 h-full">

        <span class="block flex-none text-lg font-bold">
          {{ newsItem.title }}
        </span>

        <span class="block flex-1 my-4">
          {{ newsItem.excerpt }}
        </span>

        <span class="block flex-none mt-4 text-right">
          <span class="inline-block py-2 px-3 text-zinc-100 bg-teal-500 rounded">
            Читать
          </span>
        </span>

      </span>
    </router-link>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  news: {
    title: string
    slug: string
    excerpt: string
    id: number
    image?: string
  }[] | null
}>();
</script>

<style scoped>

</style>
