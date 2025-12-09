<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { ref } from 'vue';

const form = ref({
    fitnessGoals: 'Improve stamina and footwork.',
    preferredStyle: 'Boxing',
    workoutHistory: '3 times a week, focus on cardio and boxing drills.',
    otherConsiderations: 'Limited access to heavy bag.'
});

const isGenerating = ref(false);

const generateWorkout = () => {
    isGenerating.value = true;
    // TODO: Implement API call to generate workout
    setTimeout(() => {
        isGenerating.value = false;
        // Handle response
    }, 2000);
};
</script>

<template>
    <Head title="AI Workout Generator" />

    <DashboardLayout>
        <div class="max-w-3xl mx-auto">
            <!-- Card Container -->
            <div class="bg-gray-950 rounded-lg border border-gray-900 p-6 md:p-8 lg:p-10 shadow-xl">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white tracking-tight">AI Workout Generator</h1>
                            <p class="text-gray-400 text-sm md:text-base mt-1">Tell our AI your goals, and get a personalized training plan.</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="generateWorkout" class="space-y-8">
                    <!-- Fitness Goals -->
                    <div class="space-y-2">
                        <label for="fitnessGoals" class="block text-sm font-semibold text-gray-300 tracking-wide">
                            Fitness Goals
                        </label>
                        <input
                            id="fitnessGoals"
                            v-model="form.fitnessGoals"
                            type="text"
                            class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3.5 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-200"
                            placeholder="e.g., Improve stamina and footwork"
                        />
                        <p class="text-xs text-gray-500 mt-1.5">Describe your primary fitness and training objectives.</p>
                    </div>

                    <!-- Preferred Martial Arts Style -->
                    <div class="space-y-2">
                        <label for="preferredStyle" class="block text-sm font-semibold text-gray-300 tracking-wide">
                            Preferred Martial Arts Style
                        </label>
                        <input
                            id="preferredStyle"
                            v-model="form.preferredStyle"
                            type="text"
                            class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3.5 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-200"
                            placeholder="e.g., Boxing, Muay Thai, BJJ"
                        />
                        <p class="text-xs text-gray-500 mt-1.5">Specify your preferred martial arts discipline.</p>
                    </div>

                    <!-- Workout History -->
                    <div class="space-y-2">
                        <label for="workoutHistory" class="block text-sm font-semibold text-gray-300 tracking-wide">
                            Workout History
                        </label>
                        <textarea
                            id="workoutHistory"
                            v-model="form.workoutHistory"
                            rows="5"
                            class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3.5 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-200 resize-none"
                            placeholder="e.g., Frequency, types of exercises, duration."
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1.5">Describe your current training routine and frequency.</p>
                    </div>

                    <!-- Other Considerations -->
                    <div class="space-y-2">
                        <label for="otherConsiderations" class="block text-sm font-semibold text-gray-300 tracking-wide">
                            Other Considerations
                        </label>
                        <textarea
                            id="otherConsiderations"
                            v-model="form.otherConsiderations"
                            rows="4"
                            class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3.5 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-200 resize-none"
                            placeholder="e.g., Equipment available, injuries, time constraints"
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1.5">Include any limitations, equipment access, or special requirements.</p>
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
                            <span>{{ isGenerating ? 'Generating...' : 'Generate Workout' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </DashboardLayout>
</template>

