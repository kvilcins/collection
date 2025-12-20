<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    movies: Array,
    folders: Array
});

const currentTab = ref('all');

const filteredMovies = computed(() => {
    if (currentTab.value === 'all') {
        return props.movies;
    }

    if (currentTab.value === 'watchlist') {
        return props.movies.filter(m => m.status === 'watchlist');
    }
    if (currentTab.value === 'watched') {
        return props.movies.filter(m => m.status === 'watched');
    }

    return props.movies.filter(m => m.folder_id === currentTab.value);
});

const folderForm = useForm({ name: '' });

const createFolder = () => {
    folderForm.post(route('folders.store'), {
        onSuccess: () => folderForm.reset()
    });
};

const updateMovie = (movie, payload) => {
    router.post(route('user-movies.update', movie.id), payload, {
        preserveScroll: true
    });
};

const removeMovie = (movie) => {
    if (confirm(`Remove "${movie.title}" from collection?`)) {
        router.delete(route('user-movies.destroy', movie.id));
    }
};

const getFolderName = (id) => {
    const f = props.folders.find(f => f.id === id);
    return f ? f.name : '';
};
</script>

<template>
    <Head title="Dashboard" />

    <MainLayout>
        <div class="min-h-screen bg-gray-950 text-white p-6 md:p-12">

            <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-4xl font-black text-white">Dashboard</h1>
                    <p class="text-gray-400 mt-1">Your personal movie library</p>
                </div>

                <form @submit.prevent="createFolder" class="flex gap-2 w-full md:w-auto">
                    <input
                        v-model="folderForm.name"
                        type="text"
                        placeholder="New Folder Name..."
                        class="bg-gray-900 border-gray-700 rounded-lg text-sm px-4 py-2 w-full md:w-64 focus:ring-yellow-500 focus:border-yellow-500 placeholder-gray-600"
                    >
                    <button
                        type="submit"
                        class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold px-4 py-2 rounded-lg transition"
                    >
                        +
                    </button>
                </form>
            </div>

            <div class="flex flex-wrap gap-2 mb-8 border-b border-gray-800 pb-4">
                <button @click="currentTab = 'all'"
                        :class="['px-4 py-2 rounded-full text-sm font-bold transition', currentTab === 'all' ? 'bg-white text-black' : 'bg-gray-900 text-gray-400 hover:bg-gray-800']">
                    All
                </button>

                <button @click="currentTab = 'watchlist'"
                        :class="['px-4 py-2 rounded-full text-sm font-bold transition', currentTab === 'watchlist' ? 'bg-blue-600 text-white' : 'bg-gray-900 text-gray-400 hover:bg-gray-800']">
                    Watchlist
                </button>

                <button @click="currentTab = 'watched'"
                        :class="['px-4 py-2 rounded-full text-sm font-bold transition', currentTab === 'watched' ? 'bg-green-600 text-white' : 'bg-gray-900 text-gray-400 hover:bg-gray-800']">
                    Watched
                </button>

                <div v-if="folders.length" class="w-px h-8 bg-gray-800 mx-2"></div>

                <button v-for="folder in folders" :key="folder.id" @click="currentTab = folder.id"
                        :class="['px-4 py-2 rounded-full text-sm font-bold transition border border-gray-800',
                        currentTab === folder.id ? 'bg-purple-600 text-white border-purple-600' : 'bg-transparent text-gray-400 hover:border-gray-600']">
                    📁 {{ folder.name }}
                </button>
            </div>

            <div v-if="filteredMovies.length > 0" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">

                <div v-for="movie in filteredMovies" :key="movie.id"
                     class="flex bg-gray-900 rounded-xl overflow-hidden border border-gray-800 shadow-xl hover:border-gray-700 transition group">

                    <Link :href="route('movies.show', movie.id)" class="w-32 sm:w-40 flex-shrink-0 relative">
                        <img :src="movie.poster_url" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="text-xs font-bold uppercase tracking-wider">Details</span>
                        </div>
                    </Link>

                    <div class="flex-grow p-4 flex flex-col justify-between">
                        <div>
                            <Link :href="route('movies.show', movie.id)" class="hover:text-yellow-500 transition block mb-1">
                                <h3 class="font-bold text-lg leading-tight">{{ movie.title }}</h3>
                            </Link>

                            <div class="flex flex-wrap gap-2 mb-3">
                                <span :class="['text-[10px] font-bold px-2 py-0.5 rounded uppercase border',
                                    movie.status === 'watched' ? 'bg-green-900/30 text-green-400 border-green-900' : 'bg-blue-900/30 text-blue-400 border-blue-900']">
                                    {{ movie.status }}
                                </span>
                                <span v-if="movie.folder_id" class="text-[10px] font-bold px-2 py-0.5 rounded bg-purple-900/30 text-purple-400 border border-purple-900">
                                    {{ getFolderName(movie.folder_id) }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2 mt-2">

                            <div class="bg-black/40 p-1.5 rounded-lg flex gap-1">
                                <button
                                    @click="updateMovie(movie, { status: 'watchlist' })"
                                    :class="['flex-1 text-xs font-bold py-1.5 rounded transition',
                                        movie.status === 'watchlist' ? 'bg-blue-600 text-white shadow' : 'text-gray-500 hover:bg-white/5']"
                                >
                                    Watchlist
                                </button>
                                <button
                                    @click="updateMovie(movie, { status: 'watched' })"
                                    :class="['flex-1 text-xs font-bold py-1.5 rounded transition',
                                        movie.status === 'watched' ? 'bg-green-600 text-white shadow' : 'text-gray-500 hover:bg-white/5']"
                                >
                                    Watched
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div class="bg-black/40 px-2 py-1.5 rounded-lg flex items-center justify-between">
                                    <span class="text-yellow-500 text-xs">★</span>
                                    <select
                                        :value="movie.personal_rating"
                                        @change="(e) => updateMovie(movie, { personal_rating: e.target.value })"
                                        class="bg-transparent border-none text-white font-bold p-0 text-sm focus:ring-0 cursor-pointer text-right w-full"
                                    >
                                        <option :value="null" class="bg-gray-900 text-gray-500">-</option>
                                        <option v-for="n in 10" :key="n" :value="n" class="bg-gray-900">{{ n }}</option>
                                    </select>
                                </div>

                                <div class="bg-black/40 px-2 py-1.5 rounded-lg flex items-center justify-between relative">
                                    <span class="text-gray-500 text-xs">📁</span>
                                    <select
                                        :value="movie.folder_id"
                                        @change="(e) => updateMovie(movie, { folder_id: e.target.value || null })"
                                        class="bg-transparent border-none text-purple-400 font-bold p-0 text-sm focus:ring-0 cursor-pointer text-right w-full"
                                    >
                                        <option :value="null" class="bg-gray-900 text-gray-500">None</option>
                                        <option v-for="folder in folders" :key="folder.id" :value="folder.id" class="bg-gray-900">
                                            {{ folder.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <button
                                @click="removeMovie(movie)"
                                class="w-full text-[10px] font-bold text-red-500/50 hover:text-red-500 py-1 transition uppercase"
                            >
                                Remove
                            </button>

                        </div>
                    </div>
                </div>

            </div>

            <div v-else class="flex flex-col items-center justify-center py-24 text-gray-700">
                <div class="text-5xl mb-4 grayscale opacity-30">🎬</div>
                <p class="text-lg">No movies found in this section.</p>
            </div>

        </div>
    </MainLayout>
</template>
