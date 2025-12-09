<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-black flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8">
        <!-- Logo -->
        <div class="mb-8">
            <Link href="/" class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg shadow-orange-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-2xl font-bold text-white">Change Test</span>
            </Link>
        </div>

        <!-- Card Container -->
        <div class="w-full max-w-md bg-gray-950 rounded-xl border border-gray-900 shadow-xl p-8 md:p-10">
            <slot />
        </div>

        <!-- Back to Home Link -->
        <div class="mt-6">
            <Link href="/" class="text-gray-400 hover:text-white text-sm transition-colors">
                ← Back to home
            </Link>
        </div>
    </div>
</template>
