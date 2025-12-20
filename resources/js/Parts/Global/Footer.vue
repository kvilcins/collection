<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="bg-gray-900 border-t border-gray-800 py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-8 flex flex-col md:flex-row justify-between items-center gap-6">

            <div class="text-gray-500 text-sm">
                &copy; 2025 Cool Movies. Built with Laravel & Vue.
            </div>

            <div class="flex gap-6">
                <Link :href="route('pages.privacy')" class="text-gray-500 hover:text-white transition text-sm">Privacy Policy</Link>
                <Link :href="route('pages.terms')" class="text-gray-500 hover:text-white transition text-sm">Terms</Link>
                <Link :href="route('pages.contact')" class="text-gray-500 hover:text-white transition text-sm">Contact</Link>
            </div>

        </div>
    </footer>
</template>
