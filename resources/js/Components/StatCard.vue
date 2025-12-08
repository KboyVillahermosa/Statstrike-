<template>
  <div class="bg-gray-950 rounded-lg p-4 md:p-6 border border-gray-900">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-gray-500 text-xs md:text-sm font-medium mb-2">{{ title }}</p>
        <div class="flex items-baseline gap-2">
          <h2 class="text-2xl md:text-3xl font-bold text-white">{{ metric }}</h2>
          <p v-if="change" class="text-xs md:text-sm font-medium" :class="[changePositive ? 'text-green-400' : 'text-red-400']">
            {{ changePositive ? '+' : '' }}{{ change }}
          </p>
        </div>
      </div>
      <div v-if="icon" class="text-xl md:text-2xl text-orange-500">{{ icon }}</div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  metric: {
    type: [String, Number],
    required: true,
  },
  change: {
    type: String,
    default: null,
  },
  changePositive: {
    type: Boolean,
    default: true,
  },
  icon: {
    type: String,
    default: null,
  },
});
</script>
