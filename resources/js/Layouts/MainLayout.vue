<script setup>
import Header from '@/Parts/Global/Header.vue';
import Footer from '@/Parts/Global/Footer.vue';
</script>

<template>
    <div class="min-h-screen bg-black flex flex-col text-white">
        <Header />

        <main class="flex-grow">
            <slot />
        </main>

        <Footer />
    </div>
</template>
