<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { ref } from 'vue';

const form = ref({
    experienceLevel: 'Intermediate',
    duration: '15',
    preferredPunches: 'jab, cross, hook, uppercut',
    focusArea: 'speed and accuracy'
});

const experienceLevels = ['Beginner', 'Intermediate', 'Advanced', 'Professional'];

const isGenerating = ref(false);

const generateDrill = () => {
    isGenerating.value = true;
    // TODO: Implement API call to generate drill
    setTimeout(() => {
        isGenerating.value = false;
        // Handle response
    }, 2000);
};
</script>

<template>
    <Head title="AI Shadow Boxing Coach" />

    <DashboardLayout>
        <div class="max-w-3xl mx-auto">
            <!-- Card Container -->
            <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 md:p-8 lg:p-10 shadow-xl">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white tracking-tight">AI Shadow Boxing Coach</h1>
                            <p class="text-gray-400 text-sm md:text-base mt-1">Get a real-time, AI-generated drill to follow for your shadow boxing workout.</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="generateDrill" class="space-y-8">
                    <!-- Experience Level -->
                    <div class="space-y-2">
                        <label for="experienceLevel" class="block text-sm font-semibold text-gray-300 tracking-wide">
                            Experience Level
                        </label>
                        <select
                            id="experienceLevel"
                            v-model="form.experienceLevel"
                            class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3.5 text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-200"
                        >
                            <option v-for="level in experienceLevels" :key="level" :value="level">
                                {{ level }}
                            </option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1.5">Select your current skill level to get appropriate drills.</p>
                    </div>

                    <!-- Duration -->
                    <div class="space-y-2">
                        <label for="duration" class="block text-sm font-semibold text-gray-300 tracking-wide">
                            Duration (minutes)
                        </label>
                        <input
                            id="duration"
                            v-model="form.duration"
                            type="number"
                            min="5"
                            max="60"
                            class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3.5 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-200"
                            placeholder="15"
                        />
                        <p class="text-xs text-gray-500 mt-1.5">How long do you want your shadow boxing session to be? (5-60 minutes)</p>
                    </div>

                    <!-- Preferred Punches -->
                    <div class="space-y-2">
                        <label for="preferredPunches" class="block text-sm font-semibold text-gray-300 tracking-wide">
                            Preferred Punches
                        </label>
                        <input
                            id="preferredPunches"
                            v-model="form.preferredPunches"
                            type="text"
                            class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3.5 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-200"
                            placeholder="e.g., jab, cross, hook, uppercut"
                        />
                        <p class="text-xs text-gray-500 mt-1.5">List the punches you want to focus on during the drill.</p>
                    </div>

                    <!-- Focus Area -->
                    <div class="space-y-2">
                        <label for="focusArea" class="block text-sm font-semibold text-gray-300 tracking-wide">
                            Focus Area
                        </label>
                        <input
                            id="focusArea"
                            v-model="form.focusArea"
                            type="text"
                            class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3.5 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-200"
                            placeholder="e.g., speed and accuracy, power, endurance"
                        />
                        <p class="text-xs text-gray-500 mt-1.5">What aspect of your technique do you want to improve?</p>
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
                            <span>{{ isGenerating ? 'Generating Drill...' : 'Generate Drill' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </DashboardLayout>
</template>

