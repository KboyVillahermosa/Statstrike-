<template>
  <Head title="Log Workout" />

  <DashboardLayout>
    <div class="max-w-3xl mx-auto">
      <!-- Card Container -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 md:p-8 lg:p-10 shadow-xl">
        <!-- Header -->
        <div class="mb-8">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg shadow-orange-500/20">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
              </svg>
            </div>
            <div>
              <h1 class="text-3xl font-bold text-white tracking-tight">Log Workout</h1>
              <p class="text-gray-400 text-sm md:text-base mt-1">Record your training session details.</p>
            </div>
          </div>
        </div>


        <!-- Form -->
        <form @submit.prevent="handleSubmit" class="space-y-8">
          <!-- Drills / Exercises -->
          <div class="space-y-2">
            <label for="drills" class="block text-sm font-semibold text-gray-300 tracking-wide">
              Drills / Exercises
            </label>
            <textarea
              id="drills"
              v-model="form.drills"
              rows="5"
              :class="[
                'w-full bg-gray-900/50 border rounded-lg px-4 py-3.5 text-white placeholder-gray-600 focus:outline-none focus:ring-2 transition-all duration-200 resize-none',
                form.errors.drills 
                  ? 'border-red-500/50 focus:ring-red-500/50 focus:border-red-500/50' 
                  : 'border-gray-800 focus:ring-orange-500/50 focus:border-orange-500/50'
              ]"
              placeholder="e.g., Jab-cross combos, roundhouse kicks, sparring..."
            ></textarea>
            <p v-if="form.errors.drills" class="text-xs text-red-400 mt-1.5">{{ form.errors.drills }}</p>
            <p v-else class="text-xs text-gray-500 mt-1.5">Describe the exercises and drills you performed during this session.</p>
          </div>

          <!-- Rounds and Duration Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Rounds -->
            <div class="space-y-2">
              <label for="rounds" class="block text-sm font-semibold text-gray-300 tracking-wide">
                Rounds
              </label>
              <input
                id="rounds"
                v-model.number="form.rounds"
                type="number"
                min="1"
                :class="[
                  'w-full bg-gray-900/50 border rounded-lg px-4 py-3.5 text-white focus:outline-none focus:ring-2 transition-all duration-200',
                  form.errors.rounds 
                    ? 'border-red-500/50 focus:ring-red-500/50 focus:border-red-500/50' 
                    : 'border-gray-800 focus:ring-orange-500/50 focus:border-orange-500/50'
                ]"
              />
              <p v-if="form.errors.rounds" class="text-xs text-red-400 mt-1.5">{{ form.errors.rounds }}</p>
            </div>

            <!-- Duration -->
            <div class="space-y-2">
              <label for="duration" class="block text-sm font-semibold text-gray-300 tracking-wide">
                Duration (minutes)
              </label>
              <input
                id="duration"
                v-model.number="form.duration"
                type="number"
                min="1"
                :class="[
                  'w-full bg-gray-900/50 border rounded-lg px-4 py-3.5 text-white focus:outline-none focus:ring-2 transition-all duration-200',
                  form.errors.duration 
                    ? 'border-red-500/50 focus:ring-red-500/50 focus:border-red-500/50' 
                    : 'border-gray-800 focus:ring-orange-500/50 focus:border-orange-500/50'
                ]"
              />
              <p v-if="form.errors.duration" class="text-xs text-red-400 mt-1.5">{{ form.errors.duration }}</p>
            </div>
          </div>

          <!-- RPE Slider -->
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <label class="block text-sm font-semibold text-gray-300 tracking-wide">
                Rate of Perceived Exertion (RPE)
              </label>
              <span class="text-lg font-bold text-orange-500 bg-orange-500/10 px-3 py-1 rounded-md">
                {{ form.rpe }}/10
              </span>
            </div>
            <p v-if="form.errors.rpe" class="text-xs text-red-400">{{ form.errors.rpe }}</p>
            
            <!-- Custom Slider -->
            <div class="relative">
              <input
                v-model.number="form.rpe"
                type="range"
                min="1"
                max="10"
                step="1"
                class="w-full h-2.5 bg-gray-900 rounded-lg appearance-none cursor-pointer slider"
                :style="{
                  background: `linear-gradient(to right, #f97316 0%, #f97316 ${((form.rpe - 1) / 9) * 100}%, #1f2937 ${((form.rpe - 1) / 9) * 100}%, #1f2937 100%)`
                }"
              />
              
              <!-- RPE Scale Labels -->
              <div class="flex justify-between mt-2 text-xs text-gray-500">
                <span>1</span>
                <span>5</span>
                <span>10</span>
              </div>
            </div>
            
            <div class="flex justify-between text-xs text-gray-500 mt-1">
              <span>Very Light</span>
              <span>Max Effort</span>
            </div>
            
            <p class="text-xs text-gray-500 mt-2 pt-3 border-t border-gray-800">
              <span class="font-medium text-gray-400">RPE Scale:</span> 1 = Very Light Activity, 10 = Max Effort Activity.
            </p>
          </div>

          <!-- Submit Button -->
          <div class="pt-4">
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3.5 px-6 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="form.processing">Logging...</span>
              <span v-else>Log Workout</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useToast } from '@/composables/useToast';

const { toast } = useToast();

const form = useForm({
  drills: '',
  rounds: 1,
  duration: 30,
  rpe: 5,
});

const handleSubmit = () => {
  form.post(route('workouts.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      form.rpe = 5;
      form.rounds = 1;
      form.duration = 30;
      
      // Toast will be shown automatically via useToast composable
      // But we can also trigger it manually if needed
      if (window.toast) {
        window.toast.success('Workout logged successfully!');
      }
    },
    onError: () => {
      if (window.toast) {
        window.toast.error('Please fix the errors and try again.');
      }
    },
  });
};
</script>

<style scoped>
.slider::-webkit-slider-thumb {
  appearance: none;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #f97316;
  cursor: pointer;
  border: 3px solid #1f2937;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  transition: all 0.2s ease;
}

.slider::-webkit-slider-thumb:hover {
  transform: scale(1.1);
  box-shadow: 0 4px 8px rgba(249, 115, 22, 0.4);
}

.slider::-moz-range-thumb {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #f97316;
  cursor: pointer;
  border: 3px solid #1f2937;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  transition: all 0.2s ease;
}

.slider::-moz-range-thumb:hover {
  transform: scale(1.1);
  box-shadow: 0 4px 8px rgba(249, 115, 22, 0.4);
}
</style>

