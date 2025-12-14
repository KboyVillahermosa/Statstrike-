<template>
  <Head :title="program.name" />

  <DashboardLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Back Button -->
      <button
        @click="router.visit(route('workout-programs.index'))"
        class="mb-6 flex items-center gap-2 text-gray-400 hover:text-white transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Programs
      </button>

      <!-- Program Card -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-8 mb-6">
        <!-- Header -->
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-white mb-3">{{ program.name }}</h1>
          <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-3 py-1 text-sm font-semibold rounded bg-blue-500/20 text-blue-400">
              {{ program.level }}
            </span>
            <span v-if="program.goal" class="px-3 py-1 text-sm font-semibold rounded bg-purple-500/20 text-purple-400">
              {{ formatGoal(program.goal) }}
            </span>
          </div>
          <p class="text-gray-400">{{ program.description }}</p>
        </div>

        <!-- Program Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
          <div class="bg-gray-900 rounded-lg p-4 border border-gray-800 text-center">
            <div class="text-2xl font-bold text-white mb-1">{{ program.duration_minutes }}</div>
            <div class="text-sm text-gray-400">Minutes</div>
          </div>
          <div class="bg-gray-900 rounded-lg p-4 border border-gray-800 text-center">
            <div class="text-2xl font-bold text-white mb-1">{{ program.days_per_week }}</div>
            <div class="text-sm text-gray-400">Days/Week</div>
          </div>
          <div class="bg-gray-900 rounded-lg p-4 border border-gray-800 text-center">
            <div class="text-2xl font-bold text-white mb-1">{{ program.exercises?.length || 0 }}</div>
            <div class="text-sm text-gray-400">Exercises</div>
          </div>
        </div>

        <!-- Start Workout Button -->
        <button
          @click="startWorkout"
          class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-lg shadow-orange-500/20"
        >
          Start This Workout
        </button>
      </div>

      <!-- Exercises List -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-8">
        <h2 class="text-2xl font-bold text-white mb-6">Exercises</h2>
        <div class="space-y-4">
          <div
            v-for="(exercise, index) in program.exercises"
            :key="exercise.id"
            class="bg-gray-900 rounded-lg p-6 border border-gray-800"
          >
            <div class="flex items-start justify-between mb-4">
              <div>
                <div class="flex items-center gap-3 mb-2">
                  <span class="text-2xl font-bold text-orange-500">#{{ index + 1 }}</span>
                  <h3 class="text-xl font-bold text-white">{{ exercise.name }}</h3>
                </div>
                <p class="text-gray-400 text-sm">{{ exercise.description }}</p>
              </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div v-if="exercise.pivot.sets">
                <div class="text-sm text-gray-400 mb-1">Sets</div>
                <div class="text-lg font-semibold text-white">{{ exercise.pivot.sets }}</div>
              </div>
              <div v-if="exercise.pivot.reps">
                <div class="text-sm text-gray-400 mb-1">Reps</div>
                <div class="text-lg font-semibold text-white">{{ exercise.pivot.reps }}</div>
              </div>
              <div v-if="exercise.pivot.duration_seconds">
                <div class="text-sm text-gray-400 mb-1">Duration</div>
                <div class="text-lg font-semibold text-white">{{ Math.floor(exercise.pivot.duration_seconds / 60) }} min</div>
              </div>
              <div>
                <div class="text-sm text-gray-400 mb-1">Rest</div>
                <div class="text-lg font-semibold text-white">{{ exercise.pivot.rest_seconds }}s</div>
              </div>
            </div>
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
  program: Object,
});

const formatGoal = (goal) => {
  const goals = {
    weight_loss: 'Weight Loss',
    muscle_building: 'Muscle Building',
    toning: 'Toning',
    mobility_flexibility: 'Mobility & Flexibility',
    athletic_performance: 'Athletic Performance',
  };
  return goals[goal] || goal;
};

const startWorkout = () => {
  router.visit(route('workout-sessions.create', { program: props.program.id }));
};
</script>

