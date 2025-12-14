<template>
  <Head title="Workout Summary" />

  <DashboardLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Success Message -->
      <div v-if="$page.props.flash?.success" class="bg-green-500/20 border border-green-500/50 rounded-lg p-4 mb-6">
        <p class="text-green-400">{{ $page.props.flash.success }}</p>
      </div>

      <!-- Header -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-8 mb-6 text-center">
        <div class="text-6xl mb-4">🎉</div>
        <h1 class="text-3xl font-bold text-white mb-2">Workout Complete!</h1>
        <p class="text-gray-400">{{ session.name || 'Great job on completing your workout!' }}</p>
      </div>

      <!-- Summary Stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 text-center">
          <div class="text-3xl font-bold text-orange-500 mb-2">{{ formatDuration(session.duration_seconds) }}</div>
          <div class="text-sm text-gray-400">Duration</div>
        </div>
        <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 text-center">
          <div class="text-3xl font-bold text-orange-500 mb-2">{{ session.total_exercises }}</div>
          <div class="text-sm text-gray-400">Exercises</div>
        </div>
        <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 text-center">
          <div class="text-3xl font-bold text-orange-500 mb-2">{{ session.total_sets }}</div>
          <div class="text-sm text-gray-400">Sets</div>
        </div>
        <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 text-center">
          <div class="text-3xl font-bold text-orange-500 mb-2">{{ session.total_reps }}</div>
          <div class="text-sm text-gray-400">Reps</div>
        </div>
      </div>

      <!-- RPE -->
      <div v-if="session.rpe" class="bg-gray-950 rounded-lg border border-gray-900 p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-2">Rate of Perceived Exertion</h2>
        <div class="flex items-center gap-2">
          <div class="flex-1 bg-gray-900 rounded-full h-3">
            <div
              class="bg-orange-500 h-3 rounded-full transition-all"
              :style="{ width: `${(session.rpe / 10) * 100}%` }"
            ></div>
          </div>
          <span class="text-white font-semibold">{{ session.rpe }}/10</span>
        </div>
      </div>

      <!-- Exercises Completed -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-8 mb-6">
        <h2 class="text-2xl font-bold text-white mb-6">Exercises Completed</h2>
        <div class="space-y-4">
          <div
            v-for="(sessionExercise, index) in session.exercises"
            :key="sessionExercise.id"
            class="bg-gray-900 rounded-lg p-6 border border-gray-800"
          >
            <div class="flex items-start justify-between mb-4">
              <div>
                <div class="flex items-center gap-3 mb-2">
                  <span class="text-xl font-bold text-orange-500">#{{ index + 1 }}</span>
                  <h3 class="text-xl font-bold text-white">{{ sessionExercise.exercise.name }}</h3>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div v-if="sessionExercise.sets_completed > 0">
                <div class="text-sm text-gray-400 mb-1">Sets</div>
                <div class="text-lg font-semibold text-white">{{ sessionExercise.sets_completed }}</div>
              </div>
              <div v-if="sessionExercise.reps_completed > 0">
                <div class="text-sm text-gray-400 mb-1">Reps</div>
                <div class="text-lg font-semibold text-white">{{ sessionExercise.reps_completed }}</div>
              </div>
              <div v-if="sessionExercise.duration_seconds">
                <div class="text-sm text-gray-400 mb-1">Duration</div>
                <div class="text-lg font-semibold text-white">{{ formatDuration(sessionExercise.duration_seconds) }}</div>
              </div>
            </div>

            <div v-if="sessionExercise.notes" class="mt-4 pt-4 border-t border-gray-800">
              <div class="text-sm text-gray-400 mb-1">Notes</div>
              <div class="text-gray-300">{{ sessionExercise.notes }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Session Notes -->
      <div v-if="session.notes" class="bg-gray-950 rounded-lg border border-gray-900 p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-2">Session Notes</h2>
        <p class="text-gray-300">{{ session.notes }}</p>
      </div>

      <!-- Actions -->
      <div class="flex gap-4">
        <button
          @click="router.visit(route('workout-sessions.create'))"
          class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors"
        >
          Start New Workout
        </button>
        <button
          @click="router.visit(route('workouts.history'))"
          class="flex-1 bg-gray-800 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors"
        >
          View History
        </button>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
  session: Object,
});

const formatDuration = (seconds) => {
  if (!seconds) return '0:00';
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  const secs = seconds % 60;
  
  if (hours > 0) {
    return `${hours}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
  }
  return `${minutes}:${String(secs).padStart(2, '0')}`;
};
</script>

