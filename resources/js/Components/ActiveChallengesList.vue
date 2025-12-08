<template>
  <div class="bg-gray-950 rounded-lg p-4 md:p-6 border border-gray-900">
    <h3 class="text-white font-bold text-base md:text-lg mb-2">{{ title }}</h3>
    <p class="text-gray-500 text-xs md:text-sm mb-6">{{ subtitle }}</p>
    
    <div class="space-y-4">
      <div v-for="challenge in challenges" :key="challenge.id">
        <div class="flex justify-between items-center mb-2">
          <h4 class="text-white font-medium text-sm md:text-base">{{ challenge.title }}</h4>
          <span class="text-gray-500 text-xs md:text-sm">{{ challenge.current }} / {{ challenge.total }}</span>
        </div>
        <div class="w-full bg-gray-900 rounded-full h-2">
          <div
            class="bg-orange-500 h-2 rounded-full transition-all duration-300"
            :style="{ width: (challenge.current / challenge.total) * 100 + '%' }"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  subtitle: {
    type: String,
    required: true,
  },
  challenges: {
    type: Array,
    default: () => [
      { id: 1, title: '500 Kicks Challenge', current: 325, total: 500 },
      { id: 2, title: '20 Rounds of Sparring', current: 19, total: 20 },
    ],
  },
});
</script>
