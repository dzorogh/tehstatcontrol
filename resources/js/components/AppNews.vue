<template>
  <h2 class="mb-7 text-xl font-bold lg:text-2xl">
    Новости
  </h2>

  <div
    v-if="news"
    class="grid gap-10 sm:grid-cols-2 md:grid-cols-3"
  >
    <router-link
      v-for="newsItem in news"
      :key="newsItem.id"
      :to="{ name: 'page', params: { slug: newsItem.slug } }"
      class="flex flex-col bg-zinc-100 shadow-2xl transition-all hover:scale-105"
    >
      <span class="aspect-video block w-full">
        <img :src="'/storage/' + newsItem.image">
      </span>

      <span class=" flex flex-col p-5 h-full">

        <span class="block flex-none text-lg font-bold">
          {{ newsItem.title }}
        </span>

        <span class="hidden flex-1 my-4 lg:block">
          {{ newsItem.excerpt }}
        </span>

        <span class="block flex-none pt-4 mt-auto text-right">
          <span class="block py-2 px-3 text-center text-zinc-100 bg-teal-500 rounded lg:inline-block">
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
