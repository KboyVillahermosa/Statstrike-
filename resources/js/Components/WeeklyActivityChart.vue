<template>
  <div class="bg-gray-950 rounded-lg p-4 md:p-6 border border-gray-900">
    <h3 class="text-white font-bold text-base md:text-lg mb-2">{{ title }}</h3>
    <p class="text-gray-500 text-xs md:text-sm mb-6">{{ subtitle }}</p>
    
    <div class="flex items-end justify-center gap-1 md:gap-2 h-40 md:h-48">
      <div v-for="(bar, index) in bars" :key="index" class="flex flex-col items-center flex-1 gap-2">
        <div
          class="w-full bg-orange-500 rounded-t transition-all duration-300 hover:opacity-80"
          :style="{ height: bar.height + '%' }"
        ></div>
        <span class="text-xs text-gray-500 mt-2">{{ bar.label }}</span>
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
  bars: {
    type: Array,
    default: () => [
      { label: 'Mon', height: 45 },
      { label: 'Tue', height: 65 },
      { label: 'Wed', height: 40 },
      { label: 'Thu', height: 75 },
      { label: 'Fri', height: 60 },
      { label: 'Sat', height: 85 },
      { label: 'Sun', height: 35 },
    ],
  },
});
</script>
