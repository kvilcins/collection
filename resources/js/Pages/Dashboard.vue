<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { ref, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';

const props = defineProps({
    movies: Array,
    folders: Array
});

const currentTab = ref('all');

const filteredMovies = computed(() => {
    if (currentTab.value === 'all') return props.movies;
    if (currentTab.value === 'watched' || currentTab.value === 'watchlist') {
        return props.movies.filter(m => m.status === currentTab.value);
    }
    return props.movies.filter(m => m.folder_id === currentTab.value);
});

const folderForm = useForm({ name: '' });
const createFolder = () => {
    folderForm.post(route('folders.store'), {
        onSuccess: () => folderForm.reset()
    });
};

const updateMovie = (movie, data) => {
    useForm({ ...movie, ...data }).post(route('user-movies.update', movie.id));
};

const removeMovie = (id) => {
    if (confirm('Remove from collection?')) {
        useForm({}).delete(route('user-movies.destroy', id));
    }
};
</script>

<template>
    <Head title="My Cinema Dashboard" />
    <MainLayout>
        <div class="p-8 text-white">
            <h1 class="text-3xl font-bold mb-8">My Collection</h1>

            <div class="flex flex-wrap gap-4 mb-8">
                <button @click="currentTab = 'all'" :class="['px-4 py-2 rounded-lg', currentTab === 'all' ? 'bg-yellow-500 text-black' : 'bg-gray-800']">All</button>
                <button @click="currentTab = 'watched'" :class="['px-4 py-2 rounded-lg', currentTab === 'watched' ? 'bg-green-600' : 'bg-gray-800']">Watched</button>
                <button @click="currentTab = 'watchlist'" :class="['px-4 py-2 rounded-lg', currentTab === 'watchlist' ? 'bg-blue-600' : 'bg-gray-800']">Watchlist</button>

                <button v-for="folder in folders" :key="folder.id" @click="currentTab = folder.id"
                        :class="['px-4 py-2 rounded-lg border border-gray-600', currentTab === folder.id ? 'bg-purple-600' : 'bg-gray-800']">
                    {{ folder.name }}
                </button>

                <form @submit.prevent="createFolder" class="flex gap-2">
                    <input v-model="folderForm.name" type="text" placeholder="New folder..." class="bg-gray-900 border-gray-700 rounded text-sm w-32">
                    <button type="submit" class="bg-gray-700 px-3 rounded">+</button>
                </form>
            </div>

            <div v-if="filteredMovies.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="movie in filteredMovies" :key="movie.id" class="flex bg-gray-800 rounded-xl overflow-hidden shadow-2xl border border-gray-700">
                    <img :src="movie.poster_url" class="w-32 object-cover">
                    <div class="p-4 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold">{{ movie.title }}</h3>
                            <p class="text-sm text-gray-400 capitalize">{{ movie.status }}</p>
                        </div>

                        <div class="mt-4 flex items-center gap-4">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500">My Rating:</span>
                                <select @change="(e) => updateMovie(movie, { personal_rating: e.target.value })"
                                        class="bg-gray-900 border-none text-yellow-500 font-bold rounded p-1">
                                    <option v-for="n in 10" :key="n" :value="n" :selected="movie.personal_rating == n">{{ n }} ★</option>
                                </select>
                            </div>

                            <button @click="removeMovie(movie.id)" class="text-red-500 hover:text-red-400 text-sm ml-auto">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-center py-20 text-gray-500">
                This folder is empty.
            </div>
        </div>
    </MainLayout>
</template>
