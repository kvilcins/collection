<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    movie: Object,
    folders: Array
});

const form = useForm({
    status: 'watchlist',
    folder_id: null,
    personal_rating: null
});

const submitToCollection = () => {
    form.post(route('user-movies.update', props.movie.id), {
        onSuccess: () => alert('Updated in your collection!')
    });
};
</script>

<template>
    <Head :title="movie.title" />
    <MainLayout>
        <div class="min-h-screen bg-black text-white pb-20">
            <div class="relative h-[60vh] w-full overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent z-10"></div>
                <img :src="movie.poster_url" class="w-full h-full object-cover opacity-50 scale-110 blur-sm">

                <div class="absolute bottom-0 left-0 w-full p-8 md:p-16 z-20 flex flex-col md:flex-row items-end gap-10">
                    <img :src="movie.poster_url" class="w-48 md:w-64 rounded-2xl shadow-[0_0_50px_rgba(0,0,0,0.8)] border border-white/10 hidden md:block">

                    <div class="flex-grow">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="bg-yellow-500 text-black font-black px-4 py-1 rounded-lg text-2xl shadow-lg">★ {{ movie.rating }}</span>
                            <span class="text-gray-300 font-medium">{{ movie.release_date }}</span>
                        </div>
                        <h1 class="text-5xl md:text-7xl font-black mb-6 tracking-tight">{{ movie.title }}</h1>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="genre in movie.genres" :key="genre" class="bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full text-sm font-semibold border border-white/20">
                                {{ genre }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-8 md:px-16 mt-12 grid grid-cols-1 lg:grid-cols-3 gap-16">
                <div class="lg:col-span-2 space-y-12">
                    <section>
                        <h2 class="text-2xl font-bold mb-4 text-yellow-500 uppercase tracking-widest text-sm">Description</h2>
                        <p class="text-xl text-gray-300 leading-relaxed font-light">{{ movie.overview }}</p>
                    </section>

                    <section v-if="movie.tags.length">
                        <h2 class="text-2xl font-bold mb-4 text-yellow-500 uppercase tracking-widest text-sm">Keywords</h2>
                        <div class="flex flex-wrap gap-3">
                            <span v-for="tag in movie.tags" :key="tag" class="text-gray-500 hover:text-white transition cursor-default">
                                #{{ tag.replace(/\s+/g, '_') }}
                            </span>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6">
                    <div v-if="$page.props.auth.user" class="bg-gray-900/50 backdrop-blur-xl p-8 rounded-3xl border border-white/5 space-y-6 shadow-2xl">
                        <h3 class="text-xl font-bold">Manage Movie</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-gray-500 uppercase font-bold mb-2 block">Status</label>
                                <select v-model="form.status" class="w-full bg-black border-gray-800 rounded-xl text-sm focus:ring-yellow-500">
                                    <option value="watchlist">Watchlist</option>
                                    <option value="watched">Watched</option>
                                </select>
                            </div>

                            <div v-if="folders.length">
                                <label class="text-xs text-gray-500 uppercase font-bold mb-2 block">Move to Folder</label>
                                <select v-model="form.folder_id" class="w-full bg-black border-gray-800 rounded-xl text-sm focus:ring-yellow-500">
                                    <option :value="null">No Folder</option>
                                    <option v-for="folder in folders" :key="folder.id" :value="folder.id">{{ folder.name }}</option>
                                </select>
                            </div>

                            <button @click="submitToCollection" class="w-full bg-yellow-500 hover:bg-yellow-400 text-black font-black py-4 rounded-2xl transition shadow-[0_10px_20px_rgba(234,179,8,0.2)]">
                                SAVE TO MY LIST
                            </button>
                        </div>
                    </div>

                    <div v-else class="bg-gray-900/50 p-8 rounded-3xl border border-white/5 text-center">
                        <p class="text-gray-400 mb-6 italic">Join us to rate and organize your movies</p>
                        <Link href="/login" class="inline-block bg-white text-black font-bold px-8 py-3 rounded-xl hover:bg-gray-200 transition">Get Started</Link>
                    </div>
                </aside>
            </div>
        </div>
    </MainLayout>
</template>
