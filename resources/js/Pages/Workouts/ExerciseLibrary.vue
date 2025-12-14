<template>
  <Head title="Exercise Library" />

  <DashboardLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Exercise Library</h1>
        <p class="text-gray-400">Browse exercises by category and difficulty</p>
      </div>

      <!-- Filters -->
      <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Category Filter -->
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Category</label>
            <select
              v-model="selectedCategory"
              @change="applyFilters"
              class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
            >
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category.id" :value="category.slug">
                {{ category.icon }} {{ category.name }}
              </option>
            </select>
          </div>

          <!-- Difficulty Filter -->
          <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Difficulty</label>
            <select
              v-model="selectedDifficulty"
              @change="applyFilters"
              class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
            >
              <option value="">All Levels</option>
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Exercises Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="exercise in exercises"
          :key="exercise.id"
          @click="viewExercise(exercise.id)"
          class="bg-gray-950 rounded-lg border border-gray-900 p-6 hover:border-orange-500/50 transition-all cursor-pointer group"
        >
          <div class="flex items-start justify-between mb-4">
            <div>
              <h3 class="text-xl font-bold text-white mb-1 group-hover:text-orange-500 transition-colors">
                {{ exercise.name }}
              </h3>
              <span class="inline-block px-2 py-1 text-xs font-semibold rounded"
                :class="{
                  'bg-green-500/20 text-green-400': exercise.difficulty === 'beginner',
                  'bg-yellow-500/20 text-yellow-400': exercise.difficulty === 'intermediate',
                  'bg-red-500/20 text-red-400': exercise.difficulty === 'advanced',
                }"
              >
                {{ exercise.difficulty }}
              </span>
            </div>
            <span class="text-2xl">{{ exercise.category.icon }}</span>
          </div>

          <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ exercise.description }}</p>

          <div class="flex items-center gap-2 text-sm text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>{{ exercise.target_muscles?.join(', ') || 'Full Body' }}</span>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="exercises.length === 0" class="text-center py-12">
        <p class="text-gray-400">No exercises found. Try adjusting your filters.</p>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
  exercises: Array,
  categories: Array,
  filters: Object,
});

const selectedCategory = ref(props.filters?.category || '');
const selectedDifficulty = ref(props.filters?.difficulty || '');

const applyFilters = () => {
  router.get(route('exercises.index'), {
    category: selectedCategory.value || null,
    difficulty: selectedDifficulty.value || null,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const viewExercise = (exerciseId) => {
  router.visit(route('exercises.show', exerciseId));
};
</script>

