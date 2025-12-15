<template>
  <Head title="Workout Routine" />

  <DashboardLayout>
    <div class="space-y-6">
      <!-- Header (unchanged) -->
      <!-- ... header code remains the same ... -->

      <!-- Routines List -->
      <div v-if="routines && routines.length > 0" class="space-y-8 sm:space-y-10 lg:space-y-12">
        <div 
          v-for="routine in routines" 
          :key="routine.id" 
          class="bg-gradient-to-br from-gray-950 to-gray-900 border border-gray-800 rounded-xl sm:rounded-2xl overflow-hidden shadow-2xl hover:shadow-3xl transition-shadow duration-300"
        >
          <!-- Routine Header (unchanged) -->
          <!-- ... routine header code remains the same ... -->

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
                  @delete-day="deleteDayCard(routine.id, 'monday')"
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
                  @delete-day="deleteDayCard(routine.id, 'tuesday')"
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
                  @delete-day="deleteDayCard(routine.id, 'wednesday')"
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
                  @delete-day="deleteDayCard(routine.id, 'thursday')"
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
                  @delete-day="deleteDayCard(routine.id, 'friday')"
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
                  @delete-day="deleteDayCard(routine.id, 'saturday')"
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
                  @delete-day="deleteDayCard(routine.id, 'sunday')"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State (unchanged) -->
      <!-- ... empty state code remains the same ... -->

    </div>

    <!-- Builder Modal -->
    <!-- ... builder modal code remains the same ... -->

    <!-- Workout Runner Modal -->
    <!-- ... workout runner code remains the same ... -->

  </DashboardLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onBeforeUnmount, reactive } from 'vue';
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

// Use reactive for local state management
const routines = reactive(JSON.parse(JSON.stringify(props.routines || [])));

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
    // Update locally first for immediate UI response
    const routineIndex = routines.findIndex(r => r.id === builder.value.routineId);
    if (routineIndex !== -1) {
      const updatedRoutine = {
        ...routines[routineIndex],
        name: formData.name,
        description: formData.description,
        is_active: formData.is_active,
        days: formData.days
      };
      
      // If activating this routine, deactivate others
      if (formData.is_active) {
        routines.forEach(routine => {
          if (routine.id !== builder.value.routineId) {
            routine.is_active = false;
          }
        });
      }
      
      routines[routineIndex] = updatedRoutine;
    }

    router.put(route('workouts.routines.update', builder.value.routineId), formData, {
      preserveScroll: true,
      onSuccess: () => {
        closeBuilder();
        // No need to reload since we updated locally
      },
      onError: (errors) => {
        // Revert local changes if server fails
        alert('Failed to update routine. Please try again.');
        // Reload from server to get correct state
        router.reload();
      },
    });
  } else {
    router.post(route('workouts.routines.store'), formData, {
      preserveScroll: true,
      onSuccess: (response) => {
        closeBuilder();
        // Add new routine to local state immediately
        if (response.props && response.props.routines) {
          // Clear and replace with new data from server
          routines.splice(0, routines.length, ...response.props.routines);
        } else if (response.data && response.data.routine) {
          // If only the new routine is returned, add it to the list
          const newRoutine = response.data.routine;
          
          // If new routine is active, deactivate others
          if (newRoutine.is_active) {
            routines.forEach(routine => {
              routine.is_active = false;
            });
          }
          
          routines.push(newRoutine);
        }
      },
      onError: () => {
        alert('Failed to create routine. Please try again.');
      },
    });
  }
}

function deleteRoutine(id) {
  if (confirm('Are you sure you want to delete this routine?')) {
    // Remove from local state immediately for instant UI update
    const routineIndex = routines.findIndex(r => r.id === id);
    if (routineIndex !== -1) {
      routines.splice(routineIndex, 1);
    }
    
    router.delete(route('workouts.routines.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        // Already removed from UI, no action needed
      },
      onError: () => {
        alert('Failed to delete routine. Please try again.');
        // Reload to get correct state
        router.reload();
      },
    });
  }
}

function getDayWorkout(routine, dayOfWeek) {
  return routine.days?.find(d => d.day_of_week === dayOfWeek);
}

function deleteDayCard(routineId, dayOfWeek) {
  if (confirm(`Are you sure you want to remove the workout for ${dayOfWeek}?`)) {
    const routineIndex = routines.findIndex(r => r.id === routineId);
    if (routineIndex !== -1) {
      const routine = routines[routineIndex];
      
      // Remove the day from local state
      if (routine.days) {
        const dayIndex = routine.days.findIndex(d => d.day_of_week === dayOfWeek);
        if (dayIndex !== -1) {
          routine.days.splice(dayIndex, 1);
          
          // Send update to server
          const updateData = {
            name: routine.name,
            description: routine.description,
            is_active: routine.is_active,
            days: routine.days // Send updated days array
          };
          
          router.put(route('workouts.routines.update', routineId), updateData, {
            preserveScroll: true,
            onSuccess: () => {
              // Success - local state already updated
            },
            onError: () => {
              alert('Failed to update routine. Please try again.');
              // Reload to get correct state
              router.reload();
            },
          });
        }
      }
    }
  }
}

// Workout runner (unchanged)
const runner = ref({ open: false, workout: null });
const session = ref({ round: 1, itemIndex: 0, phase: 'work', remaining: 0, paused: false, timerId: null });

// Camera state (unchanged)
const cameraOpen = ref(false);
const cameraVideo = ref(null);
const isCameraActive = ref(false);
const cameraError = ref(null);
let cameraStream = null;

// Computed properties (unchanged)
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

// Camera functions (unchanged)
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
/* ... existing styles remain the same ... */
</style>