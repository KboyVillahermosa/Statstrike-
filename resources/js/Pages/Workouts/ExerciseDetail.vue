<template>
  <Head :title="exercise.name" />

  <DashboardLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Back Button -->
      <button
        @click="router.visit(route('exercises.index'))"
        class="mb-6 flex items-center gap-2 text-gray-400 hover:text-white transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Library
      </button>

      <!-- Exercise Card -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-8">
        <!-- Header -->
        <div class="flex items-start justify-between mb-6">
          <div>
            <div class="flex items-center gap-3 mb-3">
              <span class="text-4xl">{{ exercise.category.icon }}</span>
              <div>
                <h1 class="text-3xl font-bold text-white">{{ exercise.name }}</h1>
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded mt-2"
                  :class="{
                    'bg-green-500/20 text-green-400': exercise.difficulty === 'beginner',
                    'bg-yellow-500/20 text-yellow-400': exercise.difficulty === 'intermediate',
                    'bg-red-500/20 text-red-400': exercise.difficulty === 'advanced',
                  }"
                >
                  {{ exercise.difficulty }}
                </span>
              </div>
            </div>
            <p class="text-gray-400">{{ exercise.description }}</p>
          </div>
        </div>

        <!-- Demo Video Placeholder -->
        <div class="mb-6 bg-gray-900 rounded-lg aspect-video flex items-center justify-center border border-gray-800">
          <div class="text-center">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-gray-500 text-sm">Demo Video Placeholder</p>
          </div>
        </div>

        <!-- Target Muscles -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-white mb-3">Target Muscles</h2>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="muscle in exercise.target_muscles"
              :key="muscle"
              class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-sm font-medium"
            >
              {{ muscle }}
            </span>
          </div>
        </div>

        <!-- Instructions -->
        <div>
          <h2 class="text-lg font-semibold text-white mb-3">Step-by-Step Instructions</h2>
          <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
            <pre class="text-gray-300 whitespace-pre-wrap font-sans">{{ exercise.instructions }}</pre>
          </div>
        </div>

        <!-- Duration (if applicable) -->
        <div v-if="exercise.duration_seconds" class="mt-6 p-4 bg-gray-900 rounded-lg border border-gray-800">
          <div class="flex items-center gap-2 text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Recommended Duration: {{ Math.floor(exercise.duration_seconds / 60) }} minutes</span>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
  exercise: Object,
});
</script>

