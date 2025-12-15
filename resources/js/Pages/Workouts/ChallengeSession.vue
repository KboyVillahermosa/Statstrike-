<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    challenge: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    duration_seconds: 0,
    count: 0,
    rpe: 5,
    movement_data: null,
    notes: '',
});

// Timer state
const isRunning = ref(false);
const isPaused = ref(false);
const elapsedSeconds = ref(0);
const timerInterval = ref(null);

// Camera state
const videoRef = ref(null);
const streamRef = ref(null);
const cameraError = ref(null);
const isCameraActive = ref(false);

// Movement tracking state
const movementCount = ref(0);
const isTracking = ref(false);

// Format time for display
const formattedTime = computed(() => {
    const hours = Math.floor(elapsedSeconds.value / 3600);
    const minutes = Math.floor((elapsedSeconds.value % 3600) / 60);
    const seconds = elapsedSeconds.value % 60;
    
    if (hours > 0) {
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }
    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
});

// Start timer
const startTimer = () => {
    if (!isRunning.value) {
        isRunning.value = true;
        isPaused.value = false;
        timerInterval.value = setInterval(() => {
            elapsedSeconds.value++;
        }, 1000);
    }
};

// Pause timer
const pauseTimer = () => {
    if (isRunning.value && !isPaused.value) {
        isPaused.value = true;
        clearInterval(timerInterval.value);
    } else if (isPaused.value) {
        isPaused.value = false;
        timerInterval.value = setInterval(() => {
            elapsedSeconds.value++;
        }, 1000);
    }
};

// Stop timer
const stopTimer = () => {
    isRunning.value = false;
    isPaused.value = false;
    clearInterval(timerInterval.value);
    timerInterval.value = null;
};

// Reset timer
const resetTimer = () => {
    stopTimer();
    elapsedSeconds.value = 0;
    movementCount.value = 0;
};

// Check if camera is supported
const isCameraSupported = () => {
    return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
};

// Initialize camera
const startCamera = async () => {
    try {
        cameraError.value = null;
        
        // Check if camera is supported
        if (!isCameraSupported()) {
            cameraError.value = 'Camera access is not supported in this browser. Please use a modern browser like Chrome, Firefox, or Safari.';
            isCameraActive.value = false;
            return;
        }
        
        // Check if we're on HTTPS or localhost/127.0.0.1
        const hostname = window.location.hostname.toLowerCase();
        const isSecure = window.location.protocol === 'https:' || 
                        hostname === 'localhost' || 
                        hostname === '127.0.0.1' ||
                        hostname === '[::1]';
        if (!isSecure) {
            cameraError.value = `Camera access requires HTTPS or localhost. Current URL: ${window.location.protocol}//${window.location.hostname}. Please access via HTTPS or use localhost/127.0.0.1 for development.`;
            isCameraActive.value = false;
            return;
        }
        
        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'user',
                width: { ideal: 1280 },
                height: { ideal: 720 },
            },
        });
        
        streamRef.value = stream;
        if (videoRef.value) {
            videoRef.value.srcObject = stream;
            isCameraActive.value = true;
        }
        
        // Movement tracking is now manual only
        isTracking.value = true;
    } catch (error) {
        console.error('Camera access error:', error);
        let errorMessage = 'Unable to access camera. ';
        
        // Provide specific error messages based on error type
        if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
            errorMessage += 'Camera permission was denied. Please allow camera access in your browser settings and try again.';
        } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
            errorMessage += 'No camera found. Please connect a camera and try again.';
        } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
            errorMessage += 'Camera is already in use by another application. Please close other applications using the camera and try again.';
        } else if (error.name === 'OverconstrainedError' || error.name === 'ConstraintNotSatisfiedError') {
            errorMessage += 'Camera does not support the required settings. Trying with default settings...';
            // Try with simpler constraints
            try {
                const fallbackStream = await navigator.mediaDevices.getUserMedia({ video: true });
                streamRef.value = fallbackStream;
                if (videoRef.value) {
                    videoRef.value.srcObject = fallbackStream;
                    isCameraActive.value = true;
                    isTracking.value = true;
                    cameraError.value = null;
                    return;
                }
            } catch (fallbackError) {
                errorMessage += ' Default settings also failed.';
            }
        } else if (error.name === 'NotSupportedError') {
            errorMessage += 'Camera access is not supported. Please use HTTPS.';
        } else {
            errorMessage += `Error: ${error.message || 'Unknown error occurred.'}`;
        }
        
        cameraError.value = errorMessage;
        isCameraActive.value = false;
    }
};

// Stop camera
const stopCamera = () => {
    if (streamRef.value) {
        streamRef.value.getTracks().forEach(track => track.stop());
        streamRef.value = null;
    }
    if (videoRef.value) {
        videoRef.value.srcObject = null;
    }
    isCameraActive.value = false;
    isTracking.value = false;
};

// Movement tracking is now manual only - use +/- buttons to count

// Increment movement count manually (placeholder)
const incrementCount = () => {
    movementCount.value++;
};

// Decrement movement count
const decrementCount = () => {
    if (movementCount.value > 0) {
        movementCount.value--;
    }
};

// Submit session
const submitSession = () => {
    if (elapsedSeconds.value === 0) {
        alert('Please start a workout session first.');
        return;
    }
    
    form.duration_seconds = elapsedSeconds.value;
    form.count = movementCount.value;
    
    form.post(route('challenges.session.store', props.challenge.id), {
        preserveScroll: false,
        onSuccess: (page) => {
            stopCamera();
            resetTimer();
            
            // Show toast from flash message if available
            if (page.props.flash && page.props.flash.success) {
                setTimeout(() => {
                    if (window.toast && typeof window.toast.success === 'function') {
                        window.toast.success(page.props.flash.success);
                    }
                }, 300);
            } else {
                // Fallback: show generic success message
                setTimeout(() => {
                    if (window.toast && typeof window.toast.success === 'function') {
                        window.toast.success('Workout session completed successfully!');
                    }
                }, 300);
            }
        },
        onError: () => {
            if (window.toast) {
                window.toast.error('Failed to save workout session. Please try again.');
            }
        },
    });
};

// Cleanup on unmount
onUnmounted(() => {
    stopTimer();
    stopCamera();
});

// Auto-start camera when component mounts (optional)
onMounted(() => {
    // Commented out - uncomment if you want camera to start automatically
    // startCamera();
});
</script>

<template>
    <Head :title="`${challenge.title} - Workout Session`" />

    <DashboardLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">{{ challenge.title }}</h1>
                <p class="text-gray-400">{{ challenge.description }}</p>
            </div>

            <div class="space-y-6">
                <!-- Camera Section - Full Width -->
                <div class="w-full">
                    <div class="bg-gray-950 border border-gray-900 rounded-lg overflow-hidden">
                        <div class="relative bg-black aspect-video flex items-center justify-center">
                            <video
                                ref="videoRef"
                                autoplay
                                playsinline
                                muted
                                class="w-full h-full object-cover"
                                v-show="isCameraActive"
                            ></video>
                            
                            <!-- Camera Placeholder -->
                            <div v-if="!isCameraActive" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 p-6">
                                <svg class="w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-400 mb-2">Camera not active</p>
                                <div v-if="cameraError" class="mt-4 max-w-md w-full">
                                    <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4">
                                        <p class="text-red-400 text-sm text-center mb-3">{{ cameraError }}</p>
                                        <div class="text-xs text-gray-500 space-y-1">
                                            <p class="font-semibold text-gray-400 mb-2">Troubleshooting tips:</p>
                                            <ul class="list-disc list-inside space-y-1 ml-2">
                                                <li>Make sure you're using HTTPS (required for camera access)</li>
                                                <li>Check browser permissions in settings</li>
                                                <li>Ensure no other app is using the camera</li>
                                                <li>Try refreshing the page and allowing permissions</li>
                                                <li>On mobile, ensure camera permissions are granted</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-center mt-4">
                                    <p class="text-sm text-gray-500">Click "Start Camera" to begin tracking</p>
                                    <p class="text-xs text-gray-600 mt-1">Camera access requires HTTPS or localhost</p>
                                </div>
                            </div>

                            <!-- Movement Count Overlay -->
                            <div v-if="isCameraActive" class="absolute top-4 right-4 bg-black/70 backdrop-blur-sm rounded-lg px-4 py-2">
                                <p class="text-xs text-gray-400 mb-1">Count</p>
                                <p class="text-2xl font-bold text-white">{{ movementCount }}</p>
                            </div>
                        </div>

                        <!-- Camera Controls -->
                        <div class="p-4 bg-gray-900 border-t border-gray-800">
                            <div class="flex items-center justify-between">
                                <button
                                    @click="isCameraActive ? stopCamera() : startCamera()"
                                    :class="[
                                        'px-4 py-2 rounded-lg font-medium transition-colors',
                                        isCameraActive
                                            ? 'bg-red-500 hover:bg-red-600 text-white'
                                            : 'bg-orange-500 hover:bg-orange-600 text-white'
                                    ]"
                                >
                                    {{ isCameraActive ? 'Stop Camera' : 'Start Camera' }}
                                </button>
                                
                                <div class="flex gap-2">
                                    <button
                                        @click="decrementCount"
                                        class="px-3 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition-colors"
                                        :disabled="movementCount === 0"
                                        title="Decrement count"
                                    >
                                        -
                                    </button>
                                    <button
                                        @click="incrementCount"
                                        class="px-3 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition-colors"
                                        title="Increment count"
                                    >
                                        +
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-2 text-xs text-gray-500 text-center">
                                💡 Use +/- buttons to manually count your movements
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timer and Controls Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-4">
                    <!-- Timer Display -->
                    <div class="bg-gray-950 border border-gray-900 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Workout Timer</h3>
                        <div class="text-center mb-6">
                            <div class="text-5xl font-bold text-orange-500 mb-2">{{ formattedTime }}</div>
                            <div class="text-sm text-gray-400">
                                {{ challenge.target_unit ? `${movementCount} / ${challenge.target_count} ${challenge.target_unit}` : `${movementCount} movements` }}
                            </div>
                        </div>

                        <!-- Timer Controls -->
                        <div class="flex gap-2 mb-4">
                            <button
                                v-if="!isRunning"
                                @click="startTimer"
                                class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200"
                            >
                                Start
                            </button>
                            <button
                                v-else
                                @click="pauseTimer"
                                class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200"
                            >
                                {{ isPaused ? 'Resume' : 'Pause' }}
                            </button>
                            <button
                                @click="stopTimer"
                                class="px-4 py-3 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all duration-200"
                                :disabled="!isRunning && !isPaused"
                            >
                                Stop
                            </button>
                        </div>

                        <button
                            @click="resetTimer"
                            class="w-full bg-gray-800 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200"
                        >
                            Reset
                        </button>
                    </div>

                    <!-- RPE Rating -->
                    <div class="bg-gray-950 border border-gray-900 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Rate of Perceived Exertion (RPE)</h3>
                        <input
                            type="range"
                            v-model="form.rpe"
                            min="1"
                            max="10"
                            class="w-full h-2 bg-gray-800 rounded-lg appearance-none cursor-pointer slider"
                        />
                        <div class="flex justify-between text-sm text-gray-400 mt-2">
                            <span>1 - Very Easy</span>
                            <span class="text-white font-bold">{{ form.rpe }}/10</span>
                            <span>10 - Maximum</span>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-gray-950 border border-gray-900 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Notes</h3>
                        <textarea
                            v-model="form.notes"
                            rows="4"
                            class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 focus:outline-none"
                            placeholder="Add notes about your workout..."
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button
                        @click="submitSession"
                        :disabled="form.processing || elapsedSeconds === 0"
                        class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Complete Workout
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<style scoped>
.slider::-webkit-slider-thumb {
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #f97316;
    cursor: pointer;
    border: 3px solid #1f2937;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    transition: all 0.2s ease;
}

.slider::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(249, 115, 22, 0.4);
}

.slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #f97316;
    cursor: pointer;
    border: 3px solid #1f2937;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    transition: all 0.2s ease;
}

.slider::-moz-range-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(249, 115, 22, 0.4);
}

.slider-small::-webkit-slider-thumb {
    appearance: none;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #6b7280;
    cursor: pointer;
    border: 2px solid #1f2937;
    transition: all 0.2s ease;
}

.slider-small::-webkit-slider-thumb:hover {
    background: #9ca3af;
}

.slider-small::-moz-range-thumb {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #6b7280;
    cursor: pointer;
    border: 2px solid #1f2937;
    transition: all 0.2s ease;
}

.slider-small::-moz-range-thumb:hover {
    background: #9ca3af;
}
</style>

