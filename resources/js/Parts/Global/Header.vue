<script setup>
import { Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
</script>

<template>
    <header class="bg-gray-900 border-b border-gray-800 px-8 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">

            <Link href="/" class="text-2xl font-black text-yellow-500 tracking-tighter uppercase">
                Cool <span class="text-white">Movies</span>
            </Link>

            <nav class="hidden md:flex gap-8">
                <Link href="/" class="text-gray-300 hover:text-yellow-500 transition">Home</Link>
                <Link href="#" class="text-gray-300 hover:text-yellow-500 transition">All Movies</Link>
            </nav>

            <div class="flex items-center gap-4">
                <template v-if="!$page.props.auth.user">
                    <Link :href="route('login')" class="text-gray-400 hover:text-white text-sm">Log in</Link>
                    <Link :href="route('register')" class="bg-yellow-500 text-black px-4 py-2 rounded-lg font-bold text-sm hover:bg-yellow-400 transition">
                        Register
                    </Link>
                </template>

                <template v-else>
                    <Link :href="route('dashboard')" class="text-gray-400 hover:text-white text-sm">Dashboard</Link>
                    <span class="text-gray-600">|</span>
                    <span class="text-yellow-500 text-sm font-medium">{{ $page.props.auth.user.name }}</span>
                </template>
            </div>
        </div>
    </header>
</template>
