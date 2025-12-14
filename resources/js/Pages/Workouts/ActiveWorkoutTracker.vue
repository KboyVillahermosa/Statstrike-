<template>
  <Head title="Active Workout" />

  <DashboardLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header with Timer -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h1 class="text-2xl font-bold text-white">{{ session.name || 'Workout Session' }}</h1>
            <p v-if="session.workout_program" class="text-gray-400 text-sm">{{ session.workout_program.name }}</p>
          </div>
          <div class="text-right">
            <div class="text-3xl font-bold text-orange-500 font-mono">{{ formattedTime }}</div>
            <div class="text-sm text-gray-400">Duration</div>
          </div>
        </div>

        <!-- Timer Controls -->
        <div class="flex items-center gap-3">
          <button
            v-if="!isRunning"
            @click="startTimer"
            class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors"
          >
            Start
          </button>
          <button
            v-else
            @click="pauseTimer"
            class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors"
          >
            Pause
          </button>
          <button
            @click="resetTimer"
            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
          >
            Reset
          </button>
        </div>
      </div>

      <!-- Exercises List -->
      <div class="space-y-4 mb-6">
        <div
          v-for="(sessionExercise, index) in session.exercises"
          :key="sessionExercise.id"
          class="bg-gray-950 rounded-lg border border-gray-900 p-6"
        >
          <div class="flex items-start justify-between mb-4">
            <div>
              <div class="flex items-center gap-3 mb-2">
                <span class="text-xl font-bold text-orange-500">#{{ index + 1 }}</span>
                <h3 class="text-xl font-bold text-white">{{ sessionExercise.exercise.name }}</h3>
              </div>
              <p class="text-gray-400 text-sm">{{ sessionExercise.exercise.description }}</p>
            </div>
          </div>

          <!-- Tracking Inputs -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div>
              <label class="block text-sm text-gray-400 mb-1">Sets Completed</label>
              <input
                v-model.number="sessionExercise.sets_completed"
                @change="updateExercise(sessionExercise)"
                type="number"
                min="0"
                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
              />
            </div>
            <div>
              <label class="block text-sm text-gray-400 mb-1">Reps Completed</label>
              <input
                v-model.number="sessionExercise.reps_completed"
                @change="updateExercise(sessionExercise)"
                type="number"
                min="0"
                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
              />
            </div>
            <div v-if="sessionExercise.exercise.duration_seconds">
              <label class="block text-sm text-gray-400 mb-1">Duration (sec)</label>
              <input
                v-model.number="sessionExercise.duration_seconds"
                @change="updateExercise(sessionExercise)"
                type="number"
                min="0"
                class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
              />
            </div>
            <div>
              <label class="block text-sm text-gray-400 mb-1">Rest Timer</label>
              <button
                @click="startRestTimer(60)"
                class="w-full bg-gray-800 hover:bg-gray-700 text-white rounded-lg px-3 py-2 transition-colors"
              >
                {{ restTimer > 0 ? `${restTimer}s` : '60s Rest' }}
              </button>
            </div>
          </div>

          <!-- Notes -->
          <div>
            <label class="block text-sm text-gray-400 mb-1">Notes</label>
            <textarea
              v-model="sessionExercise.notes"
              @blur="updateExercise(sessionExercise)"
              rows="2"
              class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500"
              placeholder="Add notes about this exercise..."
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Add Exercise Button -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-4">Add Exercise</h2>
        <select
          v-model="selectedExerciseId"
          class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-orange-500 mb-3"
        >
          <option value="">Select an exercise...</option>
          <option v-for="exercise in allExercises" :key="exercise.id" :value="exercise.id">
            {{ exercise.name }} ({{ exercise.category?.name || 'Uncategorized' }})
          </option>
        </select>
        <button
          @click="addExercise"
          :disabled="!selectedExerciseId"
          class="w-full bg-orange-500 hover:bg-orange-600 disabled:bg-gray-700 disabled:cursor-not-allowed text-white font-semibold py-3 px-6 rounded-lg transition-colors"
        >
          Add Exercise
        </button>
      </div>

      <!-- Complete Workout Button -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-6">
        <h2 class="text-lg font-bold text-white mb-4">Complete Workout</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm text-gray-400 mb-2">Rate of Perceived Exertion (1-10)</label>
            <input
              v-model.number="rpe"
              type="number"
              min="1"
              max="10"
              class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
            />
          </div>
          <div>
            <label class="block text-sm text-gray-400 mb-2">Session Notes</label>
            <textarea
              v-model="notes"
              rows="3"
              class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500"
              placeholder="How did the workout feel? Any observations?"
            ></textarea>
          </div>
          <button
            @click="completeWorkout"
            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-4 px-6 rounded-lg transition-all shadow-lg shadow-green-500/20"
          >
            Complete Workout
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  session: Object,
  allExercises: Array,
});

const isRunning = ref(false);
const elapsedSeconds = ref(0);
const timerInterval = ref(null);
const restTimer = ref(0);
const restTimerInterval = ref(null);
const selectedExerciseId = ref('');
const rpe = ref(null);
const notes = ref('');

const formattedTime = computed(() => {
  const hours = Math.floor(elapsedSeconds.value / 3600);
  const minutes = Math.floor((elapsedSeconds.value % 3600) / 60);
  const seconds = elapsedSeconds.value % 60;
  
  if (hours > 0) {
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
  }
  return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
});

const startTimer = () => {
  if (!isRunning.value) {
    isRunning.value = true;
    timerInterval.value = setInterval(() => {
      elapsedSeconds.value++;
    }, 1000);
  }
};

const pauseTimer = () => {
  if (isRunning.value) {
    isRunning.value = false;
    clearInterval(timerInterval.value);
  }
};

const resetTimer = () => {
  pauseTimer();
  elapsedSeconds.value = 0;
};

const startRestTimer = (seconds) => {
  restTimer.value = seconds;
  if (restTimerInterval.value) {
    clearInterval(restTimerInterval.value);
  }
  restTimerInterval.value = setInterval(() => {
    restTimer.value--;
    if (restTimer.value <= 0) {
      clearInterval(restTimerInterval.value);
      restTimerInterval.value = null;
    }
  }, 1000);
};

const updateExercise = (sessionExercise) => {
  const form = useForm({
    sets_completed: sessionExercise.sets_completed || 0,
    reps_completed: sessionExercise.reps_completed || 0,
    duration_seconds: sessionExercise.duration_seconds || null,
    notes: sessionExercise.notes || null,
  });

  form.put(route('workout-sessions.update-exercise', [props.session.id, sessionExercise.id]), {
    preserveState: true,
    preserveScroll: true,
  });
};

const addExercise = () => {
  if (!selectedExerciseId.value) return;

  const form = useForm({
    exercise_id: selectedExerciseId.value,
  });

  form.post(route('workout-sessions.add-exercise', props.session.id), {
    onSuccess: () => {
      selectedExerciseId.value = '';
      router.reload({ only: ['session'] });
    },
  });
};

const completeWorkout = () => {
  const form = useForm({
    rpe: rpe.value,
    notes: notes.value,
  });

  form.post(route('workout-sessions.complete', props.session.id));
};

onMounted(() => {
  // Calculate elapsed time from start
  if (props.session.started_at) {
    const startTime = new Date(props.session.started_at);
    elapsedSeconds.value = Math.floor((Date.now() - startTime.getTime()) / 1000);
  }
});

onUnmounted(() => {
  if (timerInterval.value) {
    clearInterval(timerInterval.value);
  }
  if (restTimerInterval.value) {
    clearInterval(restTimerInterval.value);
  }
});
</script>

