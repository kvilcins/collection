<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Terms of Service" />
    <MainLayout>
        <div class="max-w-4xl mx-auto p-8 text-gray-300">
            <h1 class="text-4xl font-black text-white mb-8">Terms of Service</h1>

            <div class="space-y-6 leading-relaxed">
                <p>By accessing Cool Movies, you agree to be bound by these Terms of Service.</p>

                <h2 class="text-2xl font-bold text-yellow-500 mt-8">1. License</h2>
                <p>Permission is granted to temporarily download one copy of the materials (information or software) on Cool Movies' website for personal, non-commercial transitory viewing only.</p>

                <h2 class="text-2xl font-bold text-yellow-500 mt-8">2. Disclaimer</h2>
                <p>The materials on Cool Movies' website are provided on an 'as is' basis. Makes no warranties, expressed or implied.</p>
            </div>
        </div>
    </MainLayout>
</template>
