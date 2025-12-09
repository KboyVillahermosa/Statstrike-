<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { ref } from 'vue';

const workoutLogs = ref(JSON.stringify([
    {
        "date": "2024-07-20",
        "type": "Boxing",
        "duration_min": 45,
        "rpe": 8
    }
], null, 2));

const fitnessGoals = ref(JSON.stringify([
    {
        "goal": "Increase kick speed",
        "target": "10% faster by next month"
    }
], null, 2));

const performanceMetrics = ref(JSON.stringify([
    {
        "metric": "Max punch speed",
        "value": "25 mph"
    }
], null, 2));

const isGenerating = ref(false);

const generateInsights = () => {
    isGenerating.value = true;
    // TODO: Implement API call to generate insights
    setTimeout(() => {
        isGenerating.value = false;
        // Handle response
    }, 2000);
};
</script>

<template>
    <Head title="AI Progress Insights" />

    <DashboardLayout>
        <div class="max-w-4xl mx-auto">
            <!-- Card Container -->
            <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 md:p-8 lg:p-10 shadow-xl">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white tracking-tight">AI Progress Insights</h1>
                            <p class="text-gray-400 text-sm md:text-base mt-1">Get AI-driven feedback on your training data to accelerate your growth.</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="generateInsights" class="space-y-8">
                    <!-- Workout Logs -->
                    <div class="space-y-2">
                        <label for="workoutLogs" class="block text-sm font-semibold text-gray-300 tracking-wide">
                            Workout Logs (JSON)
                        </label>
                        <textarea
                            id="workoutLogs"
                            v-model="workoutLogs"
                            rows="8"
                            class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3.5 text-white font-mono text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-200 resize-none"
                            placeholder='[{"date": "2024-07-20", "type": "Boxing", "duration_min": 45, "rpe": 8}]'
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1.5">Enter your workout history as a JSON array. Include date, type, duration, and RPE.</p>
                    </div>

                    <!-- Fitness Goals -->
                    <div class="space-y-2">
                        <label for="fitnessGoals" class="block text-sm font-semibold text-gray-300 tracking-wide">
                            Fitness Goals (JSON)
                        </label>
                        <textarea
                            id="fitnessGoals"
                            v-model="fitnessGoals"
                            rows="6"
                            class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3.5 text-white font-mono text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-200 resize-none"
                            placeholder='[{"goal": "Increase kick speed", "target": "10% faster by next month"}]'
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1.5">List your fitness goals and targets as a JSON array.</p>
                    </div>

                    <!-- Performance Metrics -->
                    <div class="space-y-2">
                        <label for="performanceMetrics" class="block text-sm font-semibold text-gray-300 tracking-wide">
                            Performance Metrics (JSON)
                        </label>
                        <textarea
                            id="performanceMetrics"
                            v-model="performanceMetrics"
                            rows="6"
                            class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3.5 text-white font-mono text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-200 resize-none"
                            placeholder='[{"metric": "Max punch speed", "value": "25 mph"}]'
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1.5">Include your performance measurements and metrics as a JSON array.</p>
                    </div>

                    <!-- Generate Button -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            :disabled="isGenerating"
                            class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3.5 px-6 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        >
                            <svg v-if="isGenerating" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>{{ isGenerating ? 'Generating Insights...' : 'Generate Insights' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </DashboardLayout>
</template>

