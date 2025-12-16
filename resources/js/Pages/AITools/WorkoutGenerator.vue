
<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const form = ref({
    fitnessGoals: 'Improve stamina and footwork.',
    preferredStyle: 'Boxing',
    workoutHistory: '3 times a week, focus on cardio and boxing drills.',
    otherConsiderations: 'Limited access to heavy bag.'
});

const isGenerating = ref(false);
const generatedWorkout = ref(null);
const showPreview = ref(false);
const isSaving = ref(false);

const generateWorkout = async () => {
    isGenerating.value = true;
    try {
        const response = await fetch('/api/ai/workout-generator/generate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(form.value),
        });
        
        const data = await response.json();
        
        if (data.success) {
            generatedWorkout.value = data.workout;
            showPreview.value = true;
        } else {
            alert(data.message || 'Failed to generate workout. Please try again.');
        }
    } catch (error) {
        console.error('Error generating workout:', error);
        alert('An error occurred while generating your workout. Please try again.');
    } finally {
        isGenerating.value = false;
    }
};

const saveAsRoutine = async () => {
    if (!generatedWorkout.value) return;
    
    isSaving.value = true;
    try {
        const response = await fetch('/api/ai/workout-generator/save-routine', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(generatedWorkout.value),
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Workout routine created successfully! You can find it in your workout templates.');
            showPreview.value = false;
            generatedWorkout.value = null;
            // Optionally redirect to workout templates
            // router.visit('/workouts/templates');
        } else {
            alert(data.message || 'Failed to save workout routine. Please try again.');
        }
    } catch (error) {
        console.error('Error saving routine:', error);
        alert('An error occurred while saving your routine. Please try again.');
    } finally {
        isSaving.value = false;
    }
};

const closePreview = () => {
    showPreview.value = false;
    generatedWorkout.value = null;
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

        <!-- Generated Workout Preview Modal -->
        <div v-if="showPreview && generatedWorkout" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="closePreview"></div>
            <div class="relative bg-gradient-to-br from-gray-900 to-gray-950 border border-gray-800 rounded-xl p-6 sm:p-8 w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-white">{{ generatedWorkout.name }}</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ generatedWorkout.description }}</p>
                    </div>
                    <button class="text-gray-400 hover:text-white transition-colors" @click="closePreview">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Weekly Schedule Preview -->
                <div class="space-y-6 mb-8">
                    <h4 class="text-lg font-semibold text-white">Weekly Schedule</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div 
                            v-for="day in generatedWorkout.days" 
                            :key="day.day_of_week"
                            class="bg-gray-800/50 rounded-lg p-4 border border-gray-700"
                        >
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-bold text-white text-sm uppercase tracking-wide">{{ day.day_of_week }}</h5>
                                <span 
                                    v-if="day.title !== 'Rest Day'"
                                    class="px-2 py-1 rounded text-xs font-semibold"
                                    :class="{
                                        'bg-green-500/20 text-green-400': day.intensity === 'easy',
                                        'bg-yellow-500/20 text-yellow-400': day.intensity === 'medium',
                                        'bg-red-500/20 text-red-400': day.intensity === 'hard'
                                    }"
                                >
                                    {{ day.intensity }}
                                </span>
                            </div>
                            
                            <h6 class="text-orange-400 font-semibold mb-2">{{ day.title }}</h6>
                            <p v-if="day.description" class="text-gray-400 text-xs mb-3">{{ day.description }}</p>
                            
                            <div v-if="day.title !== 'Rest Day'" class="space-y-2">
                                <div class="text-xs text-gray-500">
                                    <span class="font-semibold">{{ day.rounds }}</span> rounds • 
                                    <span class="font-semibold">{{ day.rest_minutes }}</span>min rest
                                </div>
                                
                                <div class="space-y-1">
                                    <div 
                                        v-for="(exercise, idx) in day.exercises" 
                                        :key="idx"
                                        class="flex items-center justify-between text-xs"
                                    >
                                        <span class="text-gray-300">{{ exercise.label }}</span>
                                        <span class="text-gray-500">{{ exercise.seconds }}s</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-else class="text-center py-2 text-gray-500 text-xs">
                                Active Recovery
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button 
                        @click="closePreview" 
                        class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Close Preview
                    </button>
                    
                    <button 
                        @click="saveAsRoutine" 
                        :disabled="isSaving"
                        class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        <svg v-if="isSaving" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        {{ isSaving ? 'Saving...' : 'Save as Workout Routine' }}
                    </button>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

