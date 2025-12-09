<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
        type: Object,
        default: () => ({}),
    },
});

const page = usePage();
// Get user data from props or fallback to auth user
const userData = computed(() => {
    return props.user && Object.keys(props.user).length > 0 
        ? props.user 
        : (page.props.auth?.user || {});
});

const photoPreview = ref(null);
const photoInput = ref(null);

const form = useForm({
    name: userData.value.name || '',
    email: userData.value.email || '',
    profile_photo: null,
    fitness_goal: userData.value.fitness_goal || '',
    experience_level: userData.value.experience_level || 'beginner',
    subscription_tier: userData.value.subscription_tier || 'free',
    device_capability: userData.value.device_capability || null,
    mediapipe_supported: userData.value.mediapipe_supported || false,
});

// Watch for userData changes and update form if form fields are empty
watch(userData, (newUserData) => {
    // Always sync name and email - they're required fields
    if (newUserData.name) {
        form.name = newUserData.name;
    }
    if (newUserData.email) {
        form.email = newUserData.email;
    }
    // Sync optional fields only if form is empty
    if (newUserData.fitness_goal !== undefined && (!form.fitness_goal || form.fitness_goal.trim() === '')) {
        form.fitness_goal = newUserData.fitness_goal || '';
    }
    if (newUserData.experience_level) {
        form.experience_level = newUserData.experience_level || 'beginner';
    }
    // Always ensure subscription_tier has a value
    if (newUserData.subscription_tier) {
        form.subscription_tier = newUserData.subscription_tier;
    } else if (!form.subscription_tier) {
        form.subscription_tier = 'free';
    }
}, { immediate: true, deep: true });

const profilePhotoUrl = computed(() => {
    if (photoPreview.value) {
        return photoPreview.value;
    }
    if (userData.value.profile_photo) {
        return `/storage/${userData.value.profile_photo}`;
    }
    return null;
});

const selectNewPhoto = () => {
    photoInput.value?.click();
};

const updatePhotoPreview = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.profile_photo = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        form.profile_photo = null;
        photoPreview.value = null;
    }
};

const submit = () => {
    // Final check: ensure all required fields are set before submission
    // This is a safety net in case the watcher didn't catch it
    if (!form.name || form.name.trim() === '') {
        form.name = userData.value.name || '';
    }
    if (!form.email || form.email.trim() === '') {
        form.email = userData.value.email || '';
    }
    if (!form.subscription_tier || form.subscription_tier.trim() === '') {
        form.subscription_tier = userData.value.subscription_tier || 'free';
    }
    
    // Check if there's a file to upload
    const hasFile = form.profile_photo instanceof File;
    
    // Log before submission for debugging
    console.log('Submitting form with data:', {
        name: form.name,
        email: form.email,
        subscription_tier: form.subscription_tier,
        hasFile: hasFile,
        formData: form.data(),
    });
    
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
            console.log('Form state after error:', {
                name: form.name,
                email: form.email,
                subscription_tier: form.subscription_tier,
                formData: form.data(),
            });
        },
    };
    
    // When uploading a file, use transform to ensure all fields are included in FormData
    if (hasFile) {
        form.transform((data) => {
            // Return all data including the file - this ensures FormData includes everything
            return {
                name: data.name || form.name || userData.value.name || '',
                email: data.email || form.email || userData.value.email || '',
                fitness_goal: data.fitness_goal || '',
                experience_level: data.experience_level || 'beginner',
                subscription_tier: data.subscription_tier || form.subscription_tier || userData.value.subscription_tier || 'free',
                profile_photo: data.profile_photo,
                device_capability: data.device_capability || null,
                mediapipe_supported: data.mediapipe_supported || false,
            };
        }).patch(route('profile.update'), {
            ...options,
            forceFormData: true,
        });
    } else {
        // No file upload - use normal JSON submission
        form.patch(route('profile.update'), options);
    }
};

// Device capability detection
const detectDeviceCapability = () => {
    const capability = {
        userAgent: navigator.userAgent,
        platform: navigator.platform,
        hardwareConcurrency: navigator.hardwareConcurrency || 0,
        deviceMemory: navigator.deviceMemory || 0,
        webGLSupported: checkWebGLSupport(),
        mediaDevicesSupported: !!navigator.mediaDevices && !!navigator.mediaDevices.getUserMedia,
        touchSupport: 'ontouchstart' in window || navigator.maxTouchPoints > 0,
    };

    // Check MediaPipe compatibility
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const hasGoodGPU = capability.webGLSupported && capability.hardwareConcurrency >= 4;
    const mediapipeSupported = !isMobile && hasGoodGPU && capability.mediaDevicesSupported;

    form.device_capability = capability;
    form.mediapipe_supported = mediapipeSupported;

    // Auto-save device capability
    form.patch(route('profile.update'), {
        forceFormData: true,
        preserveScroll: true,
    });
};

const checkWebGLSupport = () => {
    try {
        const canvas = document.createElement('canvas');
        return !!(canvas.getContext('webgl') || canvas.getContext('experimental-webgl'));
    } catch (e) {
        return false;
    }
};

// Device capability detection will be triggered manually by the user
// or can be called automatically on component mount if needed
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-xl font-semibold text-white">
                Profile Information
            </h2>
            <p class="mt-1 text-sm text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Profile Photo -->
            <div>
                <InputLabel for="photo" value="Profile Photo" />
                
                <div class="mt-2 flex items-center gap-6">
                    <!-- Current Profile Photo -->
                    <div v-show="profilePhotoUrl" class="shrink-0">
                        <img
                            :src="profilePhotoUrl"
                            :alt="userData.name || form.name"
                            class="h-20 w-20 rounded-full object-cover border-2 border-gray-800"
                        />
                    </div>

                    <!-- Default Avatar -->
                    <div v-show="!profilePhotoUrl" class="shrink-0">
                        <div class="h-20 w-20 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center border-2 border-gray-800">
                            <span class="text-2xl font-bold text-white">
                                {{ (userData.name || form.name)?.charAt(0).toUpperCase() || 'U' }}
                            </span>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="selectNewPhoto"
                        class="px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white text-sm font-medium hover:bg-gray-800 transition-colors"
                    >
                        Select New Photo
                    </button>

                    <input
                        ref="photoInput"
                        type="file"
                        class="hidden"
                        @change="updatePhotoPreview"
                        accept="image/*"
                    />
                </div>

                <InputError class="mt-2" :message="form.errors.profile_photo" />
            </div>

            <!-- Name -->
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full bg-gray-900 border-gray-800 text-white placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Email -->
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-gray-900 border-gray-800 text-white placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Fitness Goal -->
            <div>
                <InputLabel for="fitness_goal" value="Fitness Goal" />
                <TextInput
                    id="fitness_goal"
                    type="text"
                    class="mt-1 block w-full bg-gray-900 border-gray-800 text-white placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500"
                    v-model="form.fitness_goal"
                    placeholder="e.g., Build strength, Improve endurance, Weight loss"
                    autocomplete="off"
                />
                <InputError class="mt-2" :message="form.errors.fitness_goal" />
            </div>

            <!-- Experience Level -->
            <div>
                <InputLabel for="experience_level" value="Experience Level" />
                <select
                    id="experience_level"
                    v-model="form.experience_level"
                    class="mt-1 block w-full rounded-lg border bg-gray-900 border-gray-800 px-4 py-2.5 text-white placeholder-gray-500 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:outline-none transition-all duration-200"
                >
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                    <option value="expert">Expert</option>
                </select>
                <InputError class="mt-2" :message="form.errors.experience_level" />
            </div>

            <!-- Subscription Tier -->
            <div>
                <InputLabel for="subscription_tier" value="Subscription Tier" />
                <div class="mt-2 grid grid-cols-3 gap-3">
                    <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none"
                        :class="form.subscription_tier === 'free' ? 'border-orange-500 bg-orange-500/10' : 'border-gray-800 bg-gray-900'">
                        <input
                            type="radio"
                            v-model="form.subscription_tier"
                            value="free"
                            class="sr-only"
                        />
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <span class="block text-sm font-medium text-white">Free</span>
                                <span class="mt-1 text-xs text-gray-400">Basic features</span>
                            </div>
                        </div>
                        <svg v-if="form.subscription_tier === 'free'" class="h-5 w-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </label>
                    <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none"
                        :class="form.subscription_tier === 'standard' ? 'border-orange-500 bg-orange-500/10' : 'border-gray-800 bg-gray-900'">
                        <input
                            type="radio"
                            v-model="form.subscription_tier"
                            value="standard"
                            class="sr-only"
                        />
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <span class="block text-sm font-medium text-white">Standard</span>
                                <span class="mt-1 text-xs text-gray-400">More features</span>
                            </div>
                        </div>
                        <svg v-if="form.subscription_tier === 'standard'" class="h-5 w-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </label>
                    <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none"
                        :class="form.subscription_tier === 'pro' ? 'border-orange-500 bg-orange-500/10' : 'border-gray-800 bg-gray-900'">
                        <input
                            type="radio"
                            v-model="form.subscription_tier"
                            value="pro"
                            class="sr-only"
                        />
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <span class="block text-sm font-medium text-white">Pro</span>
                                <span class="mt-1 text-xs text-gray-400">All features</span>
                            </div>
                        </div>
                        <svg v-if="form.subscription_tier === 'pro'" class="h-5 w-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </label>
                </div>
                <InputError class="mt-2" :message="form.errors.subscription_tier" />
            </div>

            <!-- Device Capability -->
            <div v-if="userData.device_capability" class="p-4 bg-gray-900 border border-gray-800 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <InputLabel value="Device Capability" />
                    <span :class="userData.mediapipe_supported ? 'text-green-400' : 'text-yellow-400'" class="text-sm font-medium">
                        {{ userData.mediapipe_supported ? 'MediaPipe Supported' : 'MediaPipe Not Supported' }}
                    </span>
                </div>
                <div class="text-xs text-gray-400 space-y-1">
                    <div>CPU Cores: {{ userData.device_capability?.hardwareConcurrency || 'N/A' }}</div>
                    <div>WebGL: {{ userData.device_capability?.webGLSupported ? 'Yes' : 'No' }}</div>
                    <div>Camera: {{ userData.device_capability?.mediaDevicesSupported ? 'Available' : 'Not Available' }}</div>
                </div>
                <button
                    type="button"
                    @click="detectDeviceCapability"
                    class="mt-3 text-xs text-orange-400 hover:text-orange-300"
                >
                    Re-detect Device Capability
                </button>
            </div>

            <!-- Email Verification Notice -->
            <div v-if="mustVerifyEmail && userData.email_verified_at === null" class="p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                <p class="text-sm text-yellow-400">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline hover:text-yellow-300"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton 
                    :disabled="form.processing"
                    class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white border-0"
                >
                    Save Changes
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-green-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
