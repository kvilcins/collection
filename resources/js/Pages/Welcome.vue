<script setup>
import { Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
    movies: Array
});

const addToWatchlist = (movieId) => {
    router.post(route('user-movies.update', movieId), {
        status: 'watchlist'
    }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <MainLayout>
        <div class="p-8">
            <h1 class="text-4xl font-bold mb-12 text-center text-white">Explore Movies</h1>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                <div v-for="movie in movies" :key="movie.id"
                     class="group relative bg-gray-900 rounded-xl overflow-hidden shadow-2xl border border-gray-800 transition-all duration-300 hover:scale-105 hover:border-yellow-500/50">

                    <div v-if="movie.rating > 0"
                         class="absolute top-3 right-3 bg-yellow-500 text-black font-black px-2 py-1 rounded-md z-30 shadow-lg text-sm">
                        ★ {{ movie.rating }}
                    </div>

                    <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20 flex flex-col items-center justify-center gap-3">
                        <button
                            @click="addToWatchlist(movie.id)"
                            class="bg-yellow-500 hover:bg-yellow-400 text-black p-3 rounded-full transition-transform active:scale-90"
                            title="Add to Watchlist"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>

                        <Link
                            :href="route('movies.show', movie.id)"
                            class="text-white text-sm font-bold bg-white/10 hover:bg-white/20 backdrop-blur-md py-2 px-4 rounded-lg transition"
                        >
                            Details
                        </Link>
                    </div>

                    <div class="aspect-[2/3] overflow-hidden">
                        <img :src="movie.poster_url" :alt="movie.title" class="w-full h-full object-cover grayscale-[0.3] group-hover:grayscale-0 transition-all">
                    </div>

                    <div class="p-4 bg-gradient-to-b from-gray-900 to-black">
                        <h2 class="text-white font-bold truncate">{{ movie.title }}</h2>
                        <div class="flex gap-1 mt-2 overflow-hidden">
                            <span v-for="genre in movie.genres.slice(0, 2)" :key="genre"
                                  class="text-[10px] text-gray-400 uppercase tracking-widest border border-gray-800 px-1.5 py-0.5 rounded">
                                {{ genre }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="movies.length === 0" class="text-center text-gray-500 mt-20">
                No movies in the catalog yet.
            </div>
        </div>
    </MainLayout>
</template>
