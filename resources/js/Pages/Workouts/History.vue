<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    workouts: {
        type: Object,
        default: () => ({ data: [] }),
    },
    analytics: {
        type: Object,
        default: () => ({}),
    },
});

const totalHours = computed(() => {
    return props.analytics.totalMinutes 
        ? Math.round((props.analytics.totalMinutes / 60) * 10) / 10 
        : 0;
});
</script>

<template>
    <Head title="Workout History" />

    <DashboardLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Workout History & Analytics</h1>
                <p class="text-gray-400">Track your progress and analyze your performance</p>
            </div>

            <!-- Analytics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Total Workouts -->
                <div class="bg-gradient-to-br from-orange-500/20 to-orange-600/20 border border-orange-500/30 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-400">Total Workouts</h3>
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ analytics.totalWorkouts || 0 }}</p>
                </div>

                <!-- Total Time -->
                <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 border border-blue-500/30 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-400">Total Time</h3>
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ totalHours }}h</p>
                    <p class="text-sm text-gray-400 mt-1">{{ analytics.totalMinutes || 0 }} minutes</p>
                </div>

                <!-- Average RPE -->
                <div class="bg-gradient-to-br from-purple-500/20 to-purple-600/20 border border-purple-500/30 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-400">Average RPE</h3>
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ analytics.averageRPE || 0 }}</p>
                    <p class="text-sm text-gray-400 mt-1">out of 10</p>
                </div>

                <!-- Total Rounds -->
                <div class="bg-gradient-to-br from-green-500/20 to-green-600/20 border border-green-500/30 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-400">Total Rounds</h3>
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ analytics.totalRounds || 0 }}</p>
                </div>
            </div>

            <!-- Charts Section (Placeholder) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Monthly Workouts Chart -->
                <div class="bg-gray-950 border border-gray-900 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Workouts by Month</h3>
                    <div class="space-y-3">
                        <div v-for="stat in analytics.monthlyStats" :key="stat.month" class="flex items-center justify-between">
                            <span class="text-sm text-gray-400">{{ stat.month }}</span>
                            <div class="flex items-center gap-3 flex-1 mx-4">
                                <div class="flex-1 bg-gray-900 rounded-full h-2">
                                    <div 
                                        class="bg-orange-500 h-2 rounded-full"
                                        :style="{ width: `${Math.min(100, (stat.count / Math.max(...(analytics.monthlyStats?.map(s => s.count) || [1]))) * 100)}%` }"
                                    ></div>
                                </div>
                                <span class="text-sm text-white font-medium min-w-[3rem] text-right">{{ stat.count }}</span>
                            </div>
                        </div>
                        <p v-if="!analytics.monthlyStats || analytics.monthlyStats.length === 0" class="text-sm text-gray-500 text-center py-4">
                            No workout data available
                        </p>
                    </div>
                </div>

                <!-- Average RPE Chart -->
                <div class="bg-gray-950 border border-gray-900 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Average RPE by Month</h3>
                    <div class="space-y-3">
                        <div v-for="stat in analytics.rpeStats" :key="stat.month" class="flex items-center justify-between">
                            <span class="text-sm text-gray-400">{{ stat.month }}</span>
                            <div class="flex items-center gap-3 flex-1 mx-4">
                                <div class="flex-1 bg-gray-900 rounded-full h-2">
                                    <div 
                                        class="bg-purple-500 h-2 rounded-full"
                                        :style="{ width: `${(stat.avg_rpe / 10) * 100}%` }"
                                    ></div>
                                </div>
                                <span class="text-sm text-white font-medium min-w-[3rem] text-right">{{ Math.round(stat.avg_rpe * 10) / 10 }}</span>
                            </div>
                        </div>
                        <p v-if="!analytics.rpeStats || analytics.rpeStats.length === 0" class="text-sm text-gray-500 text-center py-4">
                            No RPE data available
                        </p>
                    </div>
                </div>
            </div>

            <!-- Workout History Table -->
            <div class="bg-gray-950 border border-gray-900 rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-900">
                    <h3 class="text-lg font-semibold text-white">Recent Workouts</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Drills</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Rounds</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">RPE</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-900">
                            <tr v-for="workout in workouts.data" :key="workout.id" class="hover:bg-gray-900/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    {{ new Date(workout.created_at).toLocaleDateString() }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    <div class="max-w-md truncate">{{ workout.drills || 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ workout.rounds }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ workout.duration }} min</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                        :class="{
                                            'bg-green-500/20 text-green-400': workout.rpe <= 5,
                                            'bg-yellow-500/20 text-yellow-400': workout.rpe > 5 && workout.rpe <= 7,
                                            'bg-red-500/20 text-red-400': workout.rpe > 7
                                        }">
                                        {{ workout.rpe }}/10
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="!workouts.data || workouts.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                    No workouts found. Complete workouts from your routines or challenges to see them here.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div v-if="workouts.links && workouts.links.length > 3" class="px-6 py-4 border-t border-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">
                                Showing
                                <span class="font-medium text-white">{{ workouts.from || 0 }}</span>
                                to
                                <span class="font-medium text-white">{{ workouts.to || 0 }}</span>
                                of
                                <span class="font-medium text-white">{{ workouts.total || 0 }}</span>
                                results
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <Link
                                v-for="link in workouts.links"
                                :key="link.label"
                                :href="link.url || '#'"
                                :class="[
                                    'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                                    link.active
                                        ? 'bg-orange-500 text-white'
                                        : 'bg-gray-900 border border-gray-800 text-gray-300 hover:bg-gray-800',
                                    !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                                ]"
                                v-html="link.label"
                                preserve-state
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

