<template>
  <Head title="Workout Templates" />

  <DashboardLayout>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Create Your Own Card -->
      <div class="bg-gray-900 rounded-lg border-2 border-orange-500 p-6 flex flex-col items-center text-center">
        <div class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center mb-4">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </div>
        <h2 class="text-xl font-bold text-white mb-2">Create Your Own</h2>
        <p class="text-gray-400 mb-6 text-sm">
          Build a workout tailored to your specific goals and needs.
        </p>
        <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
          Start Building
        </button>
      </div>

      <!-- Boxing Essentials Card -->
      <div class="bg-gray-900 rounded-lg border border-gray-800 p-6 flex flex-col">
        <h2 class="text-xl font-bold text-white mb-2">Boxing Essentials</h2>
        <p class="text-gray-400 mb-4 text-sm">
          Master the fundamentals with this classic boxing workout.
        </p>
        <ul class="space-y-2 mb-4 flex-1">
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>10 min jump rope</span>
          </li>
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>5 rounds shadow boxing</span>
          </li>
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>5 rounds heavy bag</span>
          </li>
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>3 rounds speed bag</span>
          </li>
        </ul>
        <div class="flex flex-wrap gap-2 mb-4">
          <span class="px-3 py-1 bg-gray-800 text-gray-300 text-xs rounded-full">Boxing</span>
          <span class="px-3 py-1 bg-gray-800 text-gray-300 text-xs rounded-full">Beginner</span>
          <span class="px-3 py-1 bg-gray-800 text-gray-300 text-xs rounded-full">Cardio</span>
        </div>
        <button class="w-full bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
          Start Workout
        </button>
      </div>

      <!-- Muay Thai Power Card -->
      <div class="bg-gray-900 rounded-lg border border-gray-800 p-6 flex flex-col">
        <h2 class="text-xl font-bold text-white mb-2">Muay Thai Power</h2>
        <p class="text-gray-400 mb-4 text-sm">
          Develop explosive power in your kicks, knees, and elbows.
        </p>
        <ul class="space-y-2 mb-4 flex-1">
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>Clinch work and drills</span>
          </li>
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>5 rounds of pad work</span>
          </li>
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>Heavy bag conditioning</span>
          </li>
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>Technique sparring</span>
          </li>
        </ul>
        <div class="flex flex-wrap gap-2 mb-4">
          <span class="px-3 py-1 bg-gray-800 text-gray-300 text-xs rounded-full">Muay Thai</span>
          <span class="px-3 py-1 bg-gray-800 text-gray-300 text-xs rounded-full">Intermediate</span>
          <span class="px-3 py-1 bg-gray-800 text-gray-300 text-xs rounded-full">Power</span>
        </div>
        <button class="w-full bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
          Start Workout
        </button>
      </div>

      <!-- BJJ Drilling Card -->
      <div class="bg-gray-900 rounded-lg border border-gray-800 p-6 flex flex-col">
        <h2 class="text-xl font-bold text-white mb-2">BJJ Drilling</h2>
        <p class="text-gray-400 mb-4 text-sm">
          Sharpen your ground game with focused solo and partner drills.
        </p>
        <ul class="space-y-2 mb-4 flex-1">
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>Movement drills</span>
          </li>
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>Submission chains</span>
          </li>
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>Guard passing series</span>
          </li>
          <li class="flex items-start gap-2 text-gray-300 text-sm">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>Positional sparring</span>
          </li>
        </ul>
        <div class="flex flex-wrap gap-2 mb-4">
          <span class="px-3 py-1 bg-gray-800 text-gray-300 text-xs rounded-full">BJJ</span>
          <span class="px-3 py-1 bg-gray-800 text-gray-300 text-xs rounded-full">All Levels</span>
          <span class="px-3 py-1 bg-gray-800 text-gray-300 text-xs rounded-full">Technique</span>
        </div>
        <button class="w-full bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
          Start Workout
        </button>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
</script>

