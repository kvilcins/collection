<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    movies: Array,
    query: String
});

const addToWatchlist = (movieId) => {
    router.post(route('user-movies.update', movieId), { status: 'watchlist' }, { preserveScroll: true });
};
</script>

<template>
    <Head :title="`Search: ${query}`" />
    <MainLayout>
        <div class="p-8 min-h-screen">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold text-white mb-8">
                    Search results for "<span class="text-yellow-500">{{ query }}</span>"
                </h1>

                <div v-if="movies.length" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    <div v-for="movie in movies" :key="movie.id"
                         class="group relative bg-gray-900 rounded-xl overflow-hidden shadow-lg border border-gray-800 hover:border-yellow-500/50 transition">

                        <div class="aspect-[2/3]">
                            <img :src="movie.poster_url" class="w-full h-full object-cover">
                        </div>

                        <div class="absolute inset-0 bg-black/80 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center gap-3">
                            <button @click="addToWatchlist(movie.id)" class="bg-yellow-500 text-black p-3 rounded-full hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                            <Link :href="route('movies.show', movie.id)" class="text-white text-xs font-bold border border-white px-4 py-2 rounded hover:bg-white hover:text-black transition">DETAILS</Link>
                        </div>

                        <div class="p-3">
                            <h3 class="text-white font-bold text-sm truncate">{{ movie.title }}</h3>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-20 text-gray-500">
                    <p class="text-xl">No movies found matching "{{ query }}"</p>
                    <Link href="/" class="text-yellow-500 mt-4 inline-block hover:underline">Back to Home</Link>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
