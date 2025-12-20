<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const showDropdown = ref(false);
</script>

<template>
    <header class="bg-gray-900 border-b border-gray-800 px-8 py-4 relative z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">

            <Link href="/" class="text-2xl font-black text-yellow-500 tracking-tighter uppercase">
                Cool <span class="text-white">Movies</span>
            </Link>

            <nav class="hidden md:flex gap-8">
                <Link href="/" class="text-gray-300 hover:text-yellow-500 transition">Home</Link>
                <Link :href="route('dashboard')" class="text-gray-300 hover:text-yellow-500 transition">My Collection</Link>
            </nav>

            <div class="flex items-center gap-4">
                <template v-if="!$page.props.auth.user">
                    <Link :href="route('login')" class="text-gray-400 hover:text-white text-sm">Log in</Link>
                    <Link :href="route('register')" class="bg-yellow-500 text-black px-4 py-2 rounded-lg font-bold text-sm hover:bg-yellow-400 transition">
                        Register
                    </Link>
                </template>

                <div v-else class="relative">
                    <button
                        @click="showDropdown = !showDropdown"
                        class="flex items-center gap-2 text-yellow-500 hover:text-yellow-400 transition focus:outline-none"
                    >
                        <span class="font-bold text-sm">{{ $page.props.auth.user.name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div v-if="showDropdown" @click="showDropdown = false" class="fixed inset-0 z-40 cursor-default"></div>

                    <div v-if="showDropdown" class="absolute right-0 mt-3 w-48 bg-gray-900 border border-gray-700 rounded-xl shadow-2xl py-2 z-50 overflow-hidden">
                        <div class="px-4 py-2 border-b border-gray-800 text-xs text-gray-500">
                            Manage Account
                        </div>

                        <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition">
                            Profile
                        </Link>

                        <Link :href="route('dashboard')" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition">
                            Dashboard
                        </Link>

                        <div class="border-t border-gray-800 my-1"></div>

                        <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-800 hover:text-red-300 transition">
                            Log Out
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
