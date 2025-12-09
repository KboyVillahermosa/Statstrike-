<template>
  <Head title="Community Challenges" />

  <DashboardLayout>
    <div>
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">Community Challenges</h1>
        <p class="text-xl text-gray-400">
          Join a challenge and push your limits with the community.
        </p>
      </div>

      <!-- Challenge Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="challenge in challenges"
          :key="challenge.id"
          class="bg-gray-950 rounded-xl border border-gray-900 overflow-hidden hover:border-orange-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-orange-500/10"
        >
          <!-- Challenge Image -->
          <div class="h-48 bg-gradient-to-br from-orange-900/20 to-purple-900/20 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute inset-0 flex items-center justify-center">
              <svg class="w-24 h-24 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
          </div>

          <!-- Challenge Content -->
          <div class="p-6">
            <h3 class="text-2xl font-bold text-white mb-3">{{ challenge.title }}</h3>
            <p class="text-gray-400 mb-4 leading-relaxed">
              {{ challenge.description }}
            </p>

            <!-- Participants -->
            <div class="flex items-center gap-2 text-gray-400 text-sm mb-4">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span>{{ formatParticipants(challenge.participants_count) }} participants</span>
            </div>

            <!-- Progress Bar (only show if joined) -->
            <div v-if="challenge.joined" class="mb-4">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-400">Progress</span>
                <span class="text-sm font-semibold text-orange-400">{{ Math.round(challenge.progress_percentage) }}%</span>
              </div>
              <div class="w-full bg-gray-900 rounded-full h-2.5">
                <div 
                  class="bg-gradient-to-r from-orange-500 to-orange-600 h-2.5 rounded-full transition-all duration-300" 
                  :style="{ width: `${Math.min(100, challenge.progress_percentage)}%` }"
                ></div>
              </div>
              <div class="text-xs text-gray-500 mt-1">
                {{ challenge.progress }} / {{ challenge.target_count }} {{ challenge.target_unit || 'completed' }}
              </div>
            </div>

            <!-- Completed Badge -->
            <div v-if="challenge.completed" class="mb-4 p-3 bg-green-500/10 border border-green-500/20 rounded-lg">
              <div class="flex items-center gap-2 text-green-400">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">Challenge Completed!</span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2">
              <Link
                v-if="challenge.joined"
                :href="route('challenges.session.start', challenge.id)"
                class="block w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30 text-center"
              >
                Start Workout Session
              </Link>
              <form
                v-else
                @submit.prevent="joinChallenge(challenge.id)"
                class="w-full"
              >
                <button
                  type="submit"
                  class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30"
                >
                  Join Challenge
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="challenges.length === 0" class="text-center py-12">
        <p class="text-gray-400 text-lg">No active challenges at the moment. Check back soon!</p>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const props = defineProps({
    challenges: {
        type: Array,
        default: () => [],
    },
});

// Scroll to top when page loads (useful after redirect from workout session)
onMounted(() => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

const formatParticipants = (count) => {
    if (count >= 1000) {
        return (count / 1000).toFixed(1) + 'k';
    }
    return count.toString();
};

const joinChallenge = (challengeId) => {
    router.post(route('challenges.join', challengeId), {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Success toast will be shown via flash message
        },
    });
};
</script>

