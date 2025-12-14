<template>
  <Head title="Start Workout" />

  <DashboardLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Start Workout</h1>
        <p class="text-gray-400">Choose a program or create a custom workout</p>
      </div>

      <!-- Program Selection -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 mb-6">
        <h2 class="text-xl font-bold text-white mb-4">Select a Program</h2>
        <select
          v-model="selectedProgramId"
          @change="loadProgram"
          class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
        >
          <option value="">Choose a program...</option>
          <option v-for="prog in programs" :key="prog.id" :value="prog.id">
            {{ prog.name }} ({{ prog.level }})
          </option>
        </select>
      </div>

      <!-- Selected Program Info -->
      <div v-if="program" class="bg-gray-950 rounded-lg border border-gray-900 p-6 mb-6">
        <h2 class="text-xl font-bold text-white mb-4">{{ program.name }}</h2>
        <p class="text-gray-400 mb-4">{{ program.description }}</p>
        <div class="grid grid-cols-3 gap-4 mb-4">
          <div>
            <div class="text-sm text-gray-400 mb-1">Duration</div>
            <div class="text-lg font-semibold text-white">{{ program.duration_minutes }} min</div>
          </div>
          <div>
            <div class="text-sm text-gray-400 mb-1">Exercises</div>
            <div class="text-lg font-semibold text-white">{{ exercises.length }}</div>
          </div>
          <div>
            <div class="text-sm text-gray-400 mb-1">Level</div>
            <div class="text-lg font-semibold text-white">{{ program.level }}</div>
          </div>
        </div>
        <div class="space-y-2">
          <div
            v-for="(exercise, index) in exercises"
            :key="exercise.id"
            class="flex items-center gap-3 p-3 bg-gray-900 rounded-lg border border-gray-800"
          >
            <span class="text-orange-500 font-bold">#{{ index + 1 }}</span>
            <span class="text-white flex-1">{{ exercise.name }}</span>
            <span class="text-gray-400 text-sm">
              {{ exercise.pivot.sets }} sets
              <span v-if="exercise.pivot.reps">× {{ exercise.pivot.reps }} reps</span>
              <span v-if="exercise.pivot.duration_seconds">× {{ Math.floor(exercise.pivot.duration_seconds / 60) }} min</span>
            </span>
          </div>
        </div>
      </div>

      <!-- Custom Workout Option -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 mb-6">
        <h2 class="text-xl font-bold text-white mb-4">Or Create Custom Workout</h2>
        <input
          v-model="customWorkoutName"
          type="text"
          placeholder="Enter workout name..."
          class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500 mb-4"
        />
        <p class="text-sm text-gray-400 mb-4">You can add exercises during the workout.</p>
      </div>

      <!-- Start Button -->
      <button
        @click="startWorkout"
        :disabled="!program && !customWorkoutName"
        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 disabled:from-gray-700 disabled:to-gray-800 disabled:cursor-not-allowed text-white font-semibold py-4 px-6 rounded-lg transition-all shadow-lg shadow-orange-500/20"
      >
        Start Workout
      </button>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  program: Object,
  exercises: Array,
  allExercises: Array,
  programs: Array,
});

const selectedProgramId = ref(props.program?.id || '');
const customWorkoutName = ref('');

const loadProgram = () => {
  if (selectedProgramId.value) {
    router.visit(route('workout-sessions.create', { program: selectedProgramId.value }), {
      preserveState: true,
      preserveScroll: true,
    });
  }
};

const startWorkout = () => {
  const form = useForm({
    workout_program_id: props.program?.id || null,
    name: customWorkoutName.value || null,
  });

  form.post(route('workout-sessions.start'), {
    onSuccess: (page) => {
      // Redirect to tracking page will be handled by the controller
    },
  });
};
</script>

