<script setup>
import { Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    movies: Array,
    genres: Array
});

const searchQuery = ref('');
const selectedGenre = ref(null);
const showNotification = ref(false);

/**
 * Filter movies based on search query and selected genre.
 */
const filteredMovies = computed(() => {
    return props.movies.filter(movie => {
        const matchesSearch = movie.title.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesGenre = selectedGenre.value
            ? movie.genres.includes(selectedGenre.value)
            : true;

        return matchesSearch && matchesGenre;
    });
});

const addToWatchlist = (movieId) => {
    router.post(route('user-movies.update', movieId), {
        status: 'watchlist'
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showNotification.value = true;
            setTimeout(() => {
                showNotification.value = false;
            }, 3000);
        }
    });
};
</script>

<template>
    <MainLayout>
        <div class="p-6 md:p-12 min-h-screen">

            <div class="max-w-7xl mx-auto mb-12 flex flex-col items-center text-center">
                <h1 class="text-5xl font-black mb-6 text-white tracking-tight">
                    Explore <span class="text-yellow-500">Movies</span>
                </h1>

                <div class="w-full max-w-xl relative mb-8">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Find a movie..."
                        class="w-full bg-gray-900 border border-gray-700 text-white px-6 py-4 rounded-full focus:ring-2 focus:ring-yellow-500 focus:border-transparent outline-none shadow-xl transition"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 absolute right-6 top-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex flex-wrap justify-center gap-2">
                    <button
                        @click="selectedGenre = null"
                        :class="['px-4 py-1.5 rounded-full text-sm font-bold transition border border-gray-800',
                            selectedGenre === null ? 'bg-white text-black' : 'bg-gray-900 text-gray-400 hover:bg-gray-800']"
                    >
                        All
                    </button>
                    <button
                        v-for="genre in genres"
                        :key="genre.id"
                        @click="selectedGenre = genre.name"
                        :class="['px-4 py-1.5 rounded-full text-sm font-bold transition border border-gray-800',
                            selectedGenre === genre.name ? 'bg-yellow-500 text-black border-yellow-500' : 'bg-gray-900 text-gray-400 hover:bg-gray-800']"
                    >
                        {{ genre.name }}
                    </button>
                </div>
            </div>

            <div v-if="filteredMovies.length" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 max-w-7xl mx-auto">
                <div v-for="movie in filteredMovies" :key="movie.id"
                     class="group relative bg-gray-900 rounded-xl overflow-hidden shadow-2xl border border-gray-800 transition-all duration-300 hover:scale-105 hover:border-yellow-500/50">

                    <div v-if="movie.global_rating"
                         class="absolute top-3 left-3 bg-black/80 backdrop-blur-md text-yellow-500 font-bold px-2 py-1 rounded-md z-30 shadow-lg text-xs border border-white/10 flex items-center gap-1">
                        <span>IMDb</span> {{ Number(movie.global_rating).toFixed(1) }}
                    </div>

                    <div v-if="movie.rating > 0"
                         class="absolute top-3 right-3 bg-blue-600 text-white font-bold px-2 py-1 rounded-md z-30 shadow-lg text-xs">
                        ★ {{ movie.rating }}
                    </div>

                    <div class="absolute inset-0 bg-black/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20 flex flex-col items-center justify-center gap-4 p-4 text-center">
                        <button
                            @click="addToWatchlist(movie.id)"
                            class="bg-yellow-500 hover:bg-yellow-400 text-black w-12 h-12 flex items-center justify-center rounded-full transition-transform active:scale-90 shadow-[0_0_20px_rgba(234,179,8,0.5)]"
                            title="Add to Watchlist"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>

                        <Link
                            :href="route('movies.show', movie.id)"
                            class="text-white text-xs font-bold bg-white/10 hover:bg-white/20 backdrop-blur-md py-2 px-6 rounded-lg transition border border-white/20"
                        >
                            VIEW DETAILS
                        </Link>
                    </div>

                    <div class="aspect-[2/3] overflow-hidden">
                        <img :src="movie.poster_url" :alt="movie.title" class="w-full h-full object-cover grayscale-[0.2] group-hover:grayscale-0 transition-all duration-500">
                    </div>

                    <div class="p-4 bg-gradient-to-b from-gray-900 to-black h-24 flex flex-col justify-center">
                        <h2 class="text-white font-bold text-sm line-clamp-2 leading-tight">{{ movie.title }}</h2>
                        <div class="flex gap-1 mt-2 overflow-hidden flex-wrap h-5">
                            <span v-for="genre in movie.genres.slice(0, 2)" :key="genre"
                                  class="text-[9px] text-gray-500 uppercase tracking-widest border border-gray-800 px-1 py-0.5 rounded">
                                {{ genre }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-20">
                <div class="text-6xl mb-4 grayscale opacity-30">🔍</div>
                <p class="text-xl text-gray-500 font-medium">No movies found matching your criteria.</p>
                <button @click="() => { searchQuery = ''; selectedGenre = null; }" class="mt-4 text-yellow-500 hover:underline">
                    Clear filters
                </button>
            </div>

            <Transition
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="showNotification" class="fixed bottom-6 right-6 z-50">
                    <div class="bg-gray-900 border border-green-500/50 text-white px-6 py-4 rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.5)] flex items-center gap-3">
                        <div class="bg-green-500 rounded-full p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">Success</h4>
                            <p class="text-xs text-gray-400">Movie added to your watchlist.</p>
                        </div>
                    </div>
                </div>
            </Transition>

        </div>
    </MainLayout>
</template>
