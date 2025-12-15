<template>
  <Head title="Workout Routine" />

  <DashboardLayout>
    <div class="space-y-6">
      <!-- Header (unchanged) -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-6 mb-2">
        <div class="flex-1 min-w-0">
          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3 sm:mb-4 leading-tight">Weekly Workout Routines</h1>
          <p class="text-gray-400 text-base sm:text-lg leading-relaxed max-w-2xl">Create and manage your weekly workout schedule with built-in rest days for optimal recovery</p>
        </div>
        <button 
          @click="openBuilder()" 
          class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 sm:py-4 px-6 sm:px-8 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30 transform hover:scale-[1.02] whitespace-nowrap flex-shrink-0"
        >
          <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span class="text-sm sm:text-base">Create Routine</span>
        </button>
      </div>

      <!-- Routines List -->
      <div v-if="routines && routines.length > 0" class="space-y-8 sm:space-y-10 lg:space-y-12">
        <div 
          v-for="routine in routines" 
          :key="routine.id" 
          class="bg-gradient-to-br from-gray-950 to-gray-900 border border-gray-800 rounded-xl sm:rounded-2xl overflow-hidden shadow-2xl hover:shadow-3xl transition-shadow duration-300"
        >
          <!-- Routine Header (unchanged) -->
          <div class="p-6 sm:p-8 lg:p-10 border-b border-gray-800 bg-gradient-to-r from-gray-900/50 to-gray-950/50">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 sm:gap-6">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4 flex-wrap">
                  <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white break-words">{{ routine.name }}</h2>
                  <span 
                    v-if="routine.is_active" 
                    class="px-3 sm:px-4 py-1.5 sm:py-2 bg-green-500/20 border border-green-500/30 text-green-400 text-xs sm:text-sm font-semibold rounded-full whitespace-nowrap"
                  >
                    ✓ Active
                  </span>
                  <span 
                    v-else 
                    class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-500/20 border border-gray-500/30 text-gray-400 text-xs sm:text-sm font-semibold rounded-full whitespace-nowrap"
                  >
                    Inactive
                  </span>
                </div>
                <p v-if="routine.description" class="text-gray-400 text-sm sm:text-base leading-relaxed max-w-3xl">{{ routine.description }}</p>
              </div>
              <div class="flex gap-2 sm:gap-3 flex-shrink-0">
                <button 
                  @click="editRoutine(routine)" 
                  class="px-4 sm:px-5 py-2.5 sm:py-3 bg-gray-800 hover:bg-gray-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg"
                >
                  <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  <span class="hidden sm:inline">Edit</span>
                </button>
                <button 
                  @click="deleteRoutine(routine.id)" 
                  class="px-4 sm:px-5 py-2.5 sm:py-3 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg"
                >
                  <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  <span class="hidden sm:inline">Delete</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Weekly Schedule - UPDATED to 2x2 layout -->
          <div class="p-4 sm:p-6 lg:p-8">
            <!-- First Row: Monday - Thursday -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 lg:gap-6 mb-4 sm:mb-5 lg:mb-6">
              <!-- Monday -->

              <div 
                class="rounded-xl border-2 transition-all duration-300 hover:scale-[1.02] min-h-[350px]"
                :class="getDayWorkout(routine, 'monday') 
                  ? 'bg-gradient-to-br from-gray-900 via-gray-900 to-gray-950 border-orange-500/40 hover:border-orange-500/60 shadow-xl shadow-orange-500/10' 
                  : 'bg-gradient-to-br from-gray-800/40 via-gray-800/30 to-gray-900/40 border-gray-700/40 hover:border-gray-600/60 shadow-lg'"
              >
                <DayCard 
                  :day="getDayOfWeek('monday')" 
                  :routine="routine" 
                  @start-workout="startWorkout"
                />
              </div>
              

              <!-- Tuesday -->
              <div 
                class="rounded-xl border-2 transition-all duration-300 hover:scale-[1.02] min-h-[350px]"
                :class="getDayWorkout(routine, 'tuesday') 
                  ? 'bg-gradient-to-br from-gray-900 via-gray-900 to-gray-950 border-orange-500/40 hover:border-orange-500/60 shadow-xl shadow-orange-500/10' 
                  : 'bg-gradient-to-br from-gray-800/40 via-gray-800/30 to-gray-900/40 border-gray-700/40 hover:border-gray-600/60 shadow-lg'"
              >
                <DayCard 
                  :day="getDayOfWeek('tuesday')" 
                  :routine="routine" 
                  @start-workout="startWorkout"
                />
              </div>
            </div>
            
            <!-- Second Row: Wednesday - Thursday -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 lg:gap-6 mb-4 sm:mb-5 lg:mb-6">

              <!-- Wednesday -->
              <div 
                class="rounded-xl border-2 transition-all duration-300 hover:scale-[1.02] min-h-[350px]"
                :class="getDayWorkout(routine, 'wednesday') 
                  ? 'bg-gradient-to-br from-gray-900 via-gray-900 to-gray-950 border-orange-500/40 hover:border-orange-500/60 shadow-xl shadow-orange-500/10' 
                  : 'bg-gradient-to-br from-gray-800/40 via-gray-800/30 to-gray-900/40 border-gray-700/40 hover:border-gray-600/60 shadow-lg'"
              >
                <DayCard 
                  :day="getDayOfWeek('wednesday')" 
                  :routine="routine" 
                  @start-workout="startWorkout"
                />
              </div>
              

              <!-- Thursday -->
              <div 
                class="rounded-xl border-2 transition-all duration-300 hover:scale-[1.02] min-h-[350px]"
                :class="getDayWorkout(routine, 'thursday') 
                  ? 'bg-gradient-to-br from-gray-900 via-gray-900 to-gray-950 border-orange-500/40 hover:border-orange-500/60 shadow-xl shadow-orange-500/10' 
                  : 'bg-gradient-to-br from-gray-800/40 via-gray-800/30 to-gray-900/40 border-gray-700/40 hover:border-gray-600/60 shadow-lg'"
              >
                <DayCard 
                  :day="getDayOfWeek('thursday')" 
                  :routine="routine" 
                  @start-workout="startWorkout"
                />
              </div>
            </div>
            
            <!-- Third Row: Friday - Sunday -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">

              <!-- Friday -->
              <div 
                class="rounded-xl border-2 transition-all duration-300 hover:scale-[1.02] min-h-[350px]"
                :class="getDayWorkout(routine, 'friday') 
                  ? 'bg-gradient-to-br from-gray-900 via-gray-900 to-gray-950 border-orange-500/40 hover:border-orange-500/60 shadow-xl shadow-orange-500/10' 
                  : 'bg-gradient-to-br from-gray-800/40 via-gray-800/30 to-gray-900/40 border-gray-700/40 hover:border-gray-600/60 shadow-lg'"
              >
                <DayCard 
                  :day="getDayOfWeek('friday')" 
                  :routine="routine" 
                  @start-workout="startWorkout"
                />
              </div>
              

              <!-- Saturday -->
              <div 
                class="rounded-xl border-2 transition-all duration-300 hover:scale-[1.02] min-h-[350px]"
                :class="getDayWorkout(routine, 'saturday') 
                  ? 'bg-gradient-to-br from-gray-900 via-gray-900 to-gray-950 border-orange-500/40 hover:border-orange-500/60 shadow-xl shadow-orange-500/10' 
                  : 'bg-gradient-to-br from-gray-800/40 via-gray-800/30 to-gray-900/40 border-gray-700/40 hover:border-gray-600/60 shadow-lg'"
              >
                <DayCard 
                  :day="getDayOfWeek('saturday')" 
                  :routine="routine" 
                  @start-workout="startWorkout"
                />
              </div>
              

              <!-- Sunday -->
              <div 
                class="rounded-xl border-2 transition-all duration-300 hover:scale-[1.02] min-h-[350px]"
                :class="getDayWorkout(routine, 'sunday') 
                  ? 'bg-gradient-to-br from-gray-900 via-gray-900 to-gray-950 border-orange-500/40 hover:border-orange-500/60 shadow-xl shadow-orange-500/10' 
                  : 'bg-gradient-to-br from-gray-800/40 via-gray-800/30 to-gray-900/40 border-gray-700/40 hover:border-gray-600/60 shadow-lg'"
              >
                <DayCard 
                  :day="getDayOfWeek('sunday')" 
                  :routine="routine" 
                  @start-workout="startWorkout"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State (unchanged) -->
      <div v-else class="bg-gradient-to-br from-gray-950 to-gray-900 border border-gray-800 rounded-xl sm:rounded-2xl p-12 sm:p-16 lg:p-20 text-center shadow-2xl">
        <div class="w-24 h-24 sm:w-28 sm:h-28 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 sm:mb-8">
          <svg class="w-12 h-12 sm:w-14 sm:h-14 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <h3 class="text-2xl sm:text-3xl font-bold text-white mb-3 sm:mb-4">No Workout Routines</h3>
        <p class="text-gray-400 text-base sm:text-lg mb-8 sm:mb-10 max-w-md mx-auto">Create your first weekly workout routine to get started with your fitness journey.</p>
        <button 
          @click="openBuilder()" 
          class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 sm:py-4 px-8 sm:px-10 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30 transform hover:scale-[1.02] text-base sm:text-lg"
        >
          Create Routine
        </button>
      </div>

    </div>

    <!-- Builder Modal (unchanged) -->
    
    <!-- Workout Runner Modal -->
    <div v-if="runner.open" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div class="bg-gradient-to-br from-gray-950 to-gray-900 border border-gray-800 rounded-2xl p-6 sm:p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">{{ runner.workout?.title || 'Workout Session' }}</h2>
            <div class="flex items-center gap-3 text-sm text-gray-400">
              <span>Round {{ session.round }} / {{ runner.workout?.rounds || 1 }}</span>
              <span class="w-1 h-1 bg-gray-500 rounded-full"></span>
              <span class="capitalize">{{ phaseLabel }}</span>
            </div>
          </div>
          <button 
            @click="closeRunner()" 
            class="text-gray-400 hover:text-white transition-colors p-2 rounded-lg hover:bg-gray-800"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Timer Display -->
        <div class="text-center mb-8">
          <div class="text-6xl sm:text-8xl font-bold text-white mb-2 font-mono">{{ mmss }}</div>
          <div class="text-lg text-gray-400 capitalize">{{ phaseLabel }} Phase</div>
        </div>

        <!-- Current Exercise -->
        <div v-if="session.phase === 'work'" class="bg-gray-800/50 rounded-xl p-6 mb-6">
          <h3 class="text-lg font-semibold text-white mb-3">Current Exercise</h3>
          <div class="text-2xl font-bold text-orange-400">{{ currentExerciseLabel }}</div>
          <div class="text-sm text-gray-400 mt-2">Exercise {{ session.itemIndex + 1 }} of {{ runner.workout?.exercises?.length || 0 }}</div>
        </div>

        <!-- Camera Section -->
        <div class="mb-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Camera</h3>
            <button 
              @click="toggleCamera()" 
              class="px-4 py-2 rounded-lg font-medium transition-colors"
              :class="cameraOpen 
                ? 'bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30' 
                : 'bg-gray-800 text-gray-300 border border-gray-700 hover:bg-gray-700'"
            >
              {{ cameraOpen ? 'Stop Camera' : 'Start Camera' }}
            </button>
          </div>
          
          <div v-if="cameraError" class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 mb-4">
            <p class="text-red-400 text-sm">{{ cameraError }}</p>
          </div>
          
          <div v-if="cameraOpen" class="bg-black rounded-lg overflow-hidden">
            <video 
              ref="cameraVideo" 
              class="w-full h-64 object-cover" 
              autoplay 
              playsinline
              muted
            ></video>
          </div>
        </div>

        <!-- Controls -->
        <div class="flex flex-col sm:flex-row gap-3">
          <button 
            @click="togglePause()" 
            class="flex-1 bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 text-blue-400 font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="session.paused" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m6-10V7a3 3 0 00-3-3H9a3 3 0 00-3 3v4a1 1 0 001 1h1m8 0H8m8 0v4a1 1 0 01-1 1h-1M9 13v4a1 1 0 001 1h1m8 0h-1v4a1 1 0 01-1 1h-1" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6" />
            </svg>
            {{ session.paused ? 'Resume' : 'Pause' }}
          </button>
          
          <button 
            @click="skipPhase()" 
            class="flex-1 bg-orange-500/20 hover:bg-orange-500/30 border border-orange-500/30 text-orange-400 font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            Skip Phase
          </button>
          
          <button 
            @click="closeRunner()" 
            class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            End Workout
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onBeforeUnmount } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

// Import the DayCard component
import DayCard from './DayCard.vue';

const props = defineProps({
  routines: {
    type: Array,
    default: () => [],
  },
});

const daysOfWeek = [
  { label: 'Monday', value: 'monday' },
  { label: 'Tuesday', value: 'tuesday' },
  { label: 'Wednesday', value: 'wednesday' },
  { label: 'Thursday', value: 'thursday' },
  { label: 'Friday', value: 'friday' },
  { label: 'Saturday', value: 'saturday' },
  { label: 'Sunday', value: 'sunday' },
];

const routines = ref(props.routines || []);

// Helper function to get day object
function getDayOfWeek(dayValue) {
  return daysOfWeek.find(day => day.value === dayValue) || daysOfWeek[0];
}

// Builder modal state
const builder = ref({
  open: false,
  editing: false,
  routineId: null,
  form: {
    name: '',
    description: '',
    is_active: true,
    days: daysOfWeek.reduce((acc, day) => {
      acc[day.value] = {
        title: '',
        description: '',
        exercisesRaw: '',
        rounds: 5,
        intensity: 'medium',
        rest_minutes: 2,
        enabled: false,
      };
      return acc;
    }, {}),
  },
});

function hasDayWorkout(dayValue) {
  return builder.value.form.days[dayValue].enabled;
}

function toggleDayWorkout(dayValue) {
  builder.value.form.days[dayValue].enabled = !builder.value.form.days[dayValue].enabled;
  if (!builder.value.form.days[dayValue].enabled) {
    // Reset form when disabling
    builder.value.form.days[dayValue] = {
      title: '',
      description: '',
      exercisesRaw: '',
      rounds: 5,
      intensity: 'medium',
      rest_minutes: 2,
      enabled: false,
    };
  }
}

function openBuilder() {
  builder.value.open = true;
  builder.value.editing = false;
  builder.value.routineId = null;
  resetBuilderForm();
}

function editRoutine(routine) {
  builder.value.open = true;
  builder.value.editing = true;
  builder.value.routineId = routine.id;
  builder.value.form.name = routine.name;
  builder.value.form.description = routine.description || '';
  builder.value.form.is_active = routine.is_active ?? true;
  
  // Reset days
  daysOfWeek.forEach(day => {
    const dayWorkout = routine.days?.find(d => d.day_of_week === day.value);
    if (dayWorkout) {
      builder.value.form.days[day.value] = {
        title: dayWorkout.title,
        description: dayWorkout.description || '',
        exercisesRaw: Array.isArray(dayWorkout.exercises) ? dayWorkout.exercises.join('\n') : '',
        rounds: dayWorkout.rounds,
        intensity: dayWorkout.intensity,
        rest_minutes: dayWorkout.rest_minutes,
        enabled: true,
      };
    } else {
      builder.value.form.days[day.value] = {
        title: '',
        description: '',
        exercisesRaw: '',
        rounds: 5,
        intensity: 'medium',
        rest_minutes: 2,
        enabled: false,
      };
    }
  });
}

function resetBuilderForm() {
  builder.value.form.name = '';
  builder.value.form.description = '';
  builder.value.form.is_active = true;
  daysOfWeek.forEach(day => {
    builder.value.form.days[day.value] = {
      title: '',
      description: '',
      exercisesRaw: '',
      rounds: 5,
      intensity: 'medium',
      rest_minutes: 2,
      enabled: false,
    };
  });
}

function closeBuilder() {
  builder.value.open = false;
  resetBuilderForm();
}

function saveRoutine() {
  const formData = {
    name: builder.value.form.name,
    description: builder.value.form.description,
    is_active: builder.value.form.is_active,
    days: daysOfWeek
      .filter(day => builder.value.form.days[day.value].enabled)
      .map(day => {
        const dayData = builder.value.form.days[day.value];
        const exercises = dayData.exercisesRaw
          .split('\n')
          .map(e => e.trim())
          .filter(e => e.length > 0);
        
        if (exercises.length === 0 || !dayData.title) {
          return null;
        }
        
        return {
          day_of_week: day.value,
          title: dayData.title,
          description: dayData.description || null,
          exercises: exercises,
          rounds: dayData.rounds,
          intensity: dayData.intensity,
          rest_minutes: dayData.rest_minutes,
          tags: [],
        };
      })
      .filter(Boolean),
  };

  if (formData.days.length === 0) {
    alert('Please add at least one workout day.');
    return;
  }

  if (builder.value.editing) {
    router.put(route('workouts.routines.update', builder.value.routineId), formData, {
      preserveScroll: true,
      onSuccess: () => {
        closeBuilder();
        router.reload();
      },
    });
  } else {
    router.post(route('workouts.routines.store'), formData, {
      preserveScroll: true,
      onSuccess: () => {
        closeBuilder();
        router.reload();
      },
    });
  }
}

function deleteRoutine(id) {
  if (confirm('Are you sure you want to delete this routine?')) {
    router.delete(route('workouts.routines.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        router.reload();
      },
    });
  }
}

function getDayWorkout(routine, dayOfWeek) {
  return routine.days?.find(d => d.day_of_week === dayOfWeek);
}

// Workout runner
const runner = ref({ open: false, workout: null });
const session = ref({ round: 1, itemIndex: 0, phase: 'work', remaining: 0, paused: false, timerId: null });

// Camera state
const cameraOpen = ref(false);
const cameraVideo = ref(null);
const isCameraActive = ref(false);
const cameraError = ref(null);
let cameraStream = null;

const phaseLabel = computed(() => session.value.phase === 'work' ? 'Work' : 'Rest');
const mmss = computed(() => {
  const s = Math.max(0, session.value.remaining | 0);
  const m = Math.floor(s / 60).toString().padStart(2, '0');
  const r = (s % 60).toString().padStart(2, '0');
  return `${m}:${r}`;
});

const intensityToSeconds = (intensity) => {
  switch (intensity) {
    case 'hard': return 8 * 60;
    case 'medium': return 5 * 60;
    default: return 3 * 60;
  }
};

function itemDurationFor(workout) {
  const total = intensityToSeconds(workout?.intensity || 'easy');
  const count = Math.max(1, (workout?.exercises || []).length);
  return Math.max(30, Math.floor(total / count));
}

const clearTimer = () => {
  if (session.value.timerId) {
    clearInterval(session.value.timerId);
    session.value.timerId = null;
  }
};

const tick = () => {
  if (session.value.paused) return;
  session.value.remaining -= 1;
  if (session.value.remaining <= 0) {
    if (session.value.phase === 'work') {
      session.value.phase = 'rest';
      session.value.remaining = (runner.value.workout?.rest_minutes || 0) * 60;
    } else {
      if (session.value.round >= (runner.value.workout?.rounds || 1)) {
        closeRunner();
        return;
      }
      session.value.round += 1;
      session.value.phase = 'work';
      session.value.itemIndex = 0;
      session.value.remaining = itemDurationFor(runner.value.workout);
    }
  }
};

const startTimer = () => {
  clearTimer();
  session.value.timerId = setInterval(tick, 1000);
};

function startWorkout(workout) {
  runner.value.open = true;
  runner.value.workout = workout;
  session.value.round = 1;
  session.value.itemIndex = 0;
  session.value.phase = 'work';
  session.value.paused = false;
  session.value.remaining = itemDurationFor(workout);
  startTimer();
}

function closeRunner() {
  clearTimer();
  closeCamera();
  runner.value.open = false;
  runner.value.workout = null;
}

// Camera functions
async function startCamera() {
  try {
    cameraError.value = null;
    
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      cameraError.value = 'Camera access is not supported in this browser.';
      return;
    }
    
    const hostname = window.location.hostname.toLowerCase();
    const isSecure = window.location.protocol === 'https:' || 
                    hostname === 'localhost' || 
                    hostname === '127.0.0.1' ||
                    hostname === '[::1]';
    if (!isSecure) {
      cameraError.value = 'Camera access requires HTTPS or localhost.';
      return;
    }
    
    cameraStream = await navigator.mediaDevices.getUserMedia({
      video: {
        facingMode: 'user',
        width: { ideal: 1280 },
        height: { ideal: 720 },
      },
    });
    
    if (cameraVideo.value) {
      cameraVideo.value.srcObject = cameraStream;
      await cameraVideo.value.play();
      isCameraActive.value = true;
    }
  } catch (error) {
    console.error('Camera error:', error);
    let errorMessage = 'Unable to access camera. ';
    
    if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
      errorMessage += 'Camera permission was denied. Please allow camera access.';
    } else if (error.name === 'NotFoundError') {
      errorMessage += 'No camera found.';
    } else if (error.name === 'NotReadableError') {
      errorMessage += 'Camera is already in use.';
    } else {
      errorMessage += error.message || 'Unknown error occurred.';
    }
    
    cameraError.value = errorMessage;
    isCameraActive.value = false;
    cameraOpen.value = false;
  }
}

function stopCamera() {
  if (cameraStream) {
    cameraStream.getTracks().forEach(track => track.stop());
    cameraStream = null;
  }
  if (cameraVideo.value) {
    cameraVideo.value.srcObject = null;
  }
  isCameraActive.value = false;
  cameraError.value = null;
}

function toggleCamera() {
  if (cameraOpen.value) {
    stopCamera();
    cameraOpen.value = false;
  } else {
    cameraOpen.value = true;
    startCamera();
  }
}

function closeCamera() {
  stopCamera();
  cameraOpen.value = false;
}

function togglePause() { 
  session.value.paused = !session.value.paused; 
}

function skipPhase() {
  if (!runner.value.workout) return;
  if (session.value.phase === 'work') {
    if (session.value.itemIndex < (runner.value.workout.exercises || []).length - 1) {
      session.value.itemIndex += 1;
      session.value.remaining = itemDurationFor(runner.value.workout);
    } else {
      if (session.value.round >= runner.value.workout.rounds) return closeRunner();
      session.value.phase = 'rest';
      session.value.remaining = (runner.value.workout.rest_minutes || 0) * 60;
    }
  } else {
    if (session.value.round >= runner.value.workout.rounds) return closeRunner();
    session.value.round += 1;
    session.value.itemIndex = 0;
    session.value.phase = 'work';
    session.value.remaining = itemDurationFor(runner.value.workout);
  }
}

const currentExerciseLabel = computed(() => {
  const workout = runner.value.workout;
  if (!workout) return '';
  const idx = session.value.itemIndex || 0;
  return workout.exercises?.[idx] || '';
});

onBeforeUnmount(() => {
  clearTimer();
  closeCamera();
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(31, 41, 55, 0.5);
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(249, 115, 22, 0.5);
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(249, 115, 22, 0.7);
}
</style>