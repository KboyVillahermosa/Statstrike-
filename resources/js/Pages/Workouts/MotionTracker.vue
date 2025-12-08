<template>
  <Head title="Motion Tracker" />

  <DashboardLayout>
    <div class="flex items-center justify-center min-h-[60vh]">
      <div class="max-w-md w-full bg-gray-900 rounded-lg border border-gray-800 p-8 md:p-12 text-center">
        <!-- Video Camera Icon -->
        <div class="w-24 h-24 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
          </svg>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-white mb-4">Motion Tracker</h1>

        <!-- Description -->
        <p class="text-gray-400 mb-6">
          Track your punches, kicks, and movements in real-time.
        </p>

        <!-- Development Message -->
        <p class="text-gray-500 text-sm">
          Our advanced AI motion capture feature is under development. Get ready to analyze your form like never before!
        </p>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
</script>

