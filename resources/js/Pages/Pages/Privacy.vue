<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Privacy Policy" />
    <MainLayout>
        <div class="max-w-4xl mx-auto p-8 text-gray-300">
            <h1 class="text-4xl font-black text-white mb-8">Privacy Policy</h1>

            <div class="space-y-6 leading-relaxed">
                <p>Last updated: December 2025</p>
                <p>Welcome to Cool Movies. We respect your privacy and are committed to protecting your personal data.</p>

                <h2 class="text-2xl font-bold text-yellow-500 mt-8">1. Information We Collect</h2>
                <p>We only collect information that helps us provide the best movie tracking experience. This includes your email address when you register and your movie preferences (watchlist, ratings).</p>

                <h2 class="text-2xl font-bold text-yellow-500 mt-8">2. How We Use Your Data</h2>
                <p>We use your data to:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Manage your account and registration.</li>
                    <li>Provide personalized movie recommendations.</li>
                    <li>Save your watchlist and watched history.</li>
                </ul>
            </div>
        </div>
    </MainLayout>
</template>
