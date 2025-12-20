<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const form = useForm({
    email: '',
    message: ''
});

const submit = () => {
    form.post(route('contact.submit'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const successMessage = computed(() => usePage().props.flash?.success);
</script>

<template>
    <Head title="Contact Us" />
    <MainLayout>
        <div class="max-w-2xl mx-auto p-8 min-h-screen">
            <h1 class="text-4xl font-black text-white mb-2">Contact Us</h1>
            <p class="text-gray-400 mb-8">Have a question or a movie suggestion? Drop us a line.</p>

            <div v-if="successMessage" class="mb-6 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ successMessage }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 text-white focus:ring-yellow-500 focus:border-yellow-500 transition"
                        :class="{'border-red-500': form.errors.email}"
                        placeholder="your@email.com"
                    >
                    <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2">Message</label>
                    <textarea
                        v-model="form.message"
                        rows="5"
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 text-white focus:ring-yellow-500 focus:border-yellow-500 transition"
                        :class="{'border-red-500': form.errors.message}"
                        placeholder="How can we help?"
                    ></textarea>
                    <div v-if="form.errors.message" class="text-red-500 text-xs mt-1">{{ form.errors.message }}</div>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold px-8 py-3 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                >
                    <span v-if="form.processing">Sending...</span>
                    <span v-else>Send Message</span>
                </button>
            </form>
        </div>
    </MainLayout>
</template>
