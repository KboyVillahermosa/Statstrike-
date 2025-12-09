<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-black flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8">
        <!-- Logo -->
        <div class="mb-8">
            <Link href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/20 overflow-hidden">
                    <img src="/images/48pxLogo.png" alt="Stat Strike" class="w-full h-full object-cover rounded-full" />
                </div>
                <span class="text-2xl font-bold text-white">Stat Strike</span>
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
