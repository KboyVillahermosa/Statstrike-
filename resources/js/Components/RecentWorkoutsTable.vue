<template>
  <div class="bg-gray-950 rounded-lg p-4 md:p-6 border border-gray-900">
    <h3 class="text-white font-bold text-base md:text-lg mb-2">{{ title }}</h3>
    <p class="text-gray-500 text-xs md:text-sm mb-6">{{ subtitle }}</p>
    
    <div class="overflow-x-auto">
      <table class="w-full min-w-max md:min-w-full">
        <thead>
          <tr class="border-b border-gray-900">
            <th class="text-left py-3 px-2 md:px-4 text-gray-500 font-medium text-xs md:text-sm">{{ columns.type }}</th>
            <th class="text-left py-3 px-2 md:px-4 text-gray-500 font-medium text-xs md:text-sm">{{ columns.duration }}</th>
            <th class="text-left py-3 px-2 md:px-4 text-gray-500 font-medium text-xs md:text-sm">{{ columns.rpe }}</th>
            <th class="text-left py-3 px-2 md:px-4 text-gray-500 font-medium text-xs md:text-sm">{{ columns.date }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="workout in workouts" :key="workout.id" class="border-b border-gray-900 hover:bg-gray-900/30 transition">
            <td class="py-4 px-2 md:px-4 text-white font-medium text-sm">{{ workout.type }}</td>
            <td class="py-4 px-2 md:px-4 text-gray-400 text-sm">{{ workout.duration }}</td>
            <td class="py-4 px-2 md:px-4">
              <span
                class="inline-block px-2 md:px-3 py-1 rounded-full text-white font-medium text-xs md:text-sm"
                :class="getRPEStyles(workout.rpe)"
              >
                {{ workout.rpe }}
              </span>
            </td>
            <td class="py-4 px-2 md:px-4 text-gray-500 text-sm">{{ workout.date }}</td>
          </tr>
        </tbody>
      </table>
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
  columns: {
    type: Object,
    default: () => ({
      type: 'Type',
      duration: 'Duration',
      rpe: 'RPE',
      date: 'Date',
    }),
  },
  workouts: {
    type: Array,
    default: () => [
      { id: 1, type: 'Boxing', duration: '45 min', rpe: '8/10', date: '2 days ago' },
      { id: 2, type: 'Kicking Drills', duration: '60 min', rpe: '7/10', date: '3 days ago' },
      { id: 3, type: 'Sparring', duration: '30 min', rpe: '9/10', date: '5 days ago' },
    ],
  },
});

const getRPEStyles = (rpe) => {
  // Handle both "8/10" format and plain numbers
  const value = typeof rpe === 'string' ? parseInt(rpe.split('/')[0]) : parseInt(rpe);
  if (value >= 9) return 'bg-red-600';
  if (value >= 8) return 'bg-orange-600';
  if (value >= 7) return 'bg-orange-500';
  return 'bg-orange-400';
};
</script>
