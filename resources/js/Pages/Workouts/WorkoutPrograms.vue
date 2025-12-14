<template>
  <Head title="Workout Programs" />

  <DashboardLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Workout Programs</h1>
        <p class="text-gray-400">Choose a program that fits your goals and experience level</p>
      </div>

      <!-- Filters -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Level Filter -->
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Level</label>
            <select
              v-model="selectedLevel"
              @change="applyFilters"
              class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
            >
              <option value="">All Levels</option>
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
            </select>
          </div>

          <!-- Goal Filter -->
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Goal</label>
            <select
              v-model="selectedGoal"
              @change="applyFilters"
              class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
            >
              <option value="">All Goals</option>
              <option value="weight_loss">Weight Loss</option>
              <option value="muscle_building">Muscle Building</option>
              <option value="toning">Toning</option>
              <option value="mobility_flexibility">Mobility & Flexibility</option>
              <option value="athletic_performance">Athletic Performance</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Programs Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="program in programs"
          :key="program.id"
          @click="viewProgram(program.id)"
          class="bg-gray-950 rounded-lg border border-gray-900 p-6 hover:border-orange-500/50 transition-all cursor-pointer group"
        >
          <div class="flex items-start justify-between mb-4">
            <div>
              <h3 class="text-xl font-bold text-white mb-2 group-hover:text-orange-500 transition-colors">
                {{ program.name }}
              </h3>
              <div class="flex flex-wrap gap-2 mb-2">
                <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-500/20 text-blue-400">
                  {{ program.level }}
                </span>
                <span v-if="program.goal" class="px-2 py-1 text-xs font-semibold rounded bg-purple-500/20 text-purple-400">
                  {{ formatGoal(program.goal) }}
                </span>
              </div>
            </div>
          </div>

          <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ program.description }}</p>

          <div class="flex items-center gap-4 text-sm text-gray-500">
            <div class="flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ program.duration_minutes }} min</span>
            </div>
            <div class="flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <span>{{ program.days_per_week }}/week</span>
            </div>
            <div class="flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
              </svg>
              <span>{{ program.exercises?.length || 0 }} exercises</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="programs.length === 0" class="text-center py-12">
        <p class="text-gray-400">No programs found. Try adjusting your filters.</p>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  programs: Array,
  filters: Object,
});

const selectedLevel = ref(props.filters?.level || '');
const selectedGoal = ref(props.filters?.goal || '');

const applyFilters = () => {
  router.get(route('workout-programs.index'), {
    level: selectedLevel.value || null,
    goal: selectedGoal.value || null,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const viewProgram = (programId) => {
  router.visit(route('workout-programs.show', programId));
};

const formatGoal = (goal) => {
  const goals = {
    weight_loss: 'Weight Loss',
    muscle_building: 'Muscle Building',
    toning: 'Toning',
    mobility_flexibility: 'Mobility',
    athletic_performance: 'Performance',
  };
  return goals[goal] || goal;
};
</script>

