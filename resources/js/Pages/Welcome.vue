<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    movies: Array
});

const currentFilter = ref('all');
const filteredMovies = computed(() => {
    if (currentFilter.value === 'all') {
        return props.movies;
    }
    return props.movies.filter(movie => movie.status === currentFilter.value);
});
</script>

<template>
    <div class="bg-gray-900 min-h-screen p-8 text-white">
        <h1 class="text-4xl font-bold mb-4 text-center">My Movie Collection</h1>

        <div class="flex justify-center gap-4 mb-12">
            <button
                @click="currentFilter = 'all'"
                :class="['px-6 py-2 rounded-full font-bold transition', currentFilter === 'all' ? 'bg-yellow-500 text-black' : 'bg-gray-800 text-gray-400 hover:bg-gray-700']"
            >
                All
            </button>
            <button
                @click="currentFilter = 'watched'"
                :class="['px-6 py-2 rounded-full font-bold transition', currentFilter === 'watched' ? 'bg-green-500 text-black' : 'bg-gray-800 text-gray-400 hover:bg-gray-700']"
            >
                Watched
            </button>
            <button
                @click="currentFilter = 'watchlist'"
                :class="['px-6 py-2 rounded-full font-bold transition', currentFilter === 'watchlist' ? 'bg-blue-500 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700']"
            >
                Watchlist
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
            <div v-for="movie in filteredMovies" :key="movie.id" class="relative bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-700">
                <div v-if="movie.rating" class="absolute top-2 right-2 bg-yellow-500 text-black font-bold px-2 py-1 rounded shadow-md z-10">
                    ★ {{ movie.rating }}
                </div>

                <img :src="movie.poster_url" :alt="movie.title" class="w-full h-auto">

                <div class="p-4">
                    <h2 class="text-xl font-bold">{{ movie.title }}</h2>
                    <p class="text-gray-400 text-sm mt-2 line-clamp-3">{{ movie.overview }}</p>
                </div>
            </div>
        </div>

        <div v-if="filteredMovies.length === 0" class="text-center text-gray-500 mt-20">
            No movies found in this category.
        </div>
    </div>
</template>
