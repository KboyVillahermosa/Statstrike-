<template>
  <div class="h-full flex flex-col">
    <!-- Day Header -->
    <div class="p-4 sm:p-5 border-b border-gray-800/50">
      <div class="flex items-center justify-between">
        <div class="font-bold text-white text-base sm:text-lg uppercase tracking-wider">
          {{ day.label }}
        </div>
        <div 
          v-if="workout"
          class="w-3 h-3 bg-orange-500 rounded-full shadow-lg shadow-orange-500/50 animate-pulse"
        ></div>
        <div 
          v-else
          class="w-3 h-3 bg-blue-500 rounded-full shadow-lg shadow-blue-500/50"
        ></div>
      </div>
    </div>

    <!-- Workout Day Content -->
    <div v-if="workout" class="p-4 sm:p-5 flex-1 flex flex-col">
      <div class="mb-3">
        <div class="text-white font-bold text-lg sm:text-xl mb-2 leading-tight line-clamp-2">
          {{ workout.title }}
        </div>
        <div v-if="workout.description" class="text-gray-400 text-sm leading-relaxed line-clamp-2">
          {{ workout.description }}
        </div>
      </div>
      
      <div class="flex-1 mb-3">
        <div class="text-xs sm:text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Exercises</div>
        <ul class="space-y-1.5 max-h-32 overflow-y-auto custom-scrollbar pr-2">
          <li 
            v-for="(exercise, idx) in workout.exercises" 
            :key="idx"
            class="text-sm text-gray-300 flex items-start gap-2 leading-relaxed"
          >
            <span class="text-orange-500 mt-0.5 flex-shrink-0 font-bold text-xs">▸</span>
            <span class="flex-1">{{ getExerciseLabel(exercise) }}</span>
          </li>
        </ul>
      </div>
      
      <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-gray-800/50">
        <span class="px-2.5 py-1 bg-orange-500/20 border border-orange-500/30 text-orange-400 text-xs font-semibold rounded-lg whitespace-nowrap">
          {{ workout.rounds }} rounds
        </span>
        <span 
          class="px-2.5 py-1 text-xs font-semibold rounded-lg border whitespace-nowrap"
          :class="{
            'bg-green-500/20 border-green-500/30 text-green-400': workout.intensity === 'easy',
            'bg-yellow-500/20 border-yellow-500/30 text-yellow-400': workout.intensity === 'medium',
            'bg-red-500/20 border-red-500/30 text-red-400': workout.intensity === 'hard'
          }"
        >
          {{ workout.intensity }}
        </span>
        <span class="px-2.5 py-1 bg-gray-800/50 border border-gray-700/50 text-gray-400 text-xs font-semibold rounded-lg whitespace-nowrap">
          {{ workout.rest_minutes }}m rest
        </span>
      </div>
      

      <button 
        @click="$emit('start-workout', { ...workout, routine_id: routine.id, day_of_week: day.value })"
        class="w-full mt-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-sm font-bold py-2.5 px-4 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/30 hover:shadow-xl hover:shadow-orange-500/40 transform hover:scale-[1.02]"
      >
        Start Workout
      </button>
    </div>

    <!-- Rest Day Content -->
    <div v-else class="p-6 sm:p-8 text-center flex flex-col justify-center items-center h-full">
      <div class="w-14 h-14 sm:w-16 sm:h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 animate-pulse">
        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
      </div>
      <div class="text-blue-400 font-bold text-base sm:text-lg mb-1">Rest Day</div>
      <div class="text-gray-500 text-sm">Recovery & Rest</div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  day: {
    type: Object,
    required: true,
    default: () => ({ label: '', value: '' })
  },
  routine: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['start-workout']);

const workout = computed(() => {
  return props.routine.days?.find(d => d.day_of_week === props.day.value);
});

const getExerciseLabel = (exercise) => {
  if (!exercise) return '';
  
  // Handle if exercise is an object with label property
  if (typeof exercise === 'object' && exercise !== null) {
    return exercise.label || exercise.name || String(exercise);
  }
  
  // Handle if exercise is a JSON string
  if (typeof exercise === 'string') {
    try {
      const parsed = JSON.parse(exercise);
      if (typeof parsed === 'object' && parsed !== null && parsed.label) {
        return parsed.label;
      }
    } catch (e) {
      // Not JSON, use as-is
    }
    return exercise;
  }
  
  return String(exercise);
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
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

.line-clamp-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
}
</style>