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

            <!-- Subscription Tier Display & Management -->
            <div>
                <InputLabel for="subscription_tier" value="Subscription Tier" />
                
                <!-- Current Plan Display (Read-only Card) -->
                <div class="mt-2 p-4 rounded-lg border bg-gray-900"
                     :class="{
                         'border-gray-700': form.subscription_tier === 'free',
                         'border-green-700': form.subscription_tier === 'standard',
                         'border-orange-600': form.subscription_tier === 'pro'
                     }">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <!-- Plan Badge -->
                                <span :class="{
                                    'px-3 py-1 rounded-full text-xs font-semibold': true,
                                    'bg-gray-800 text-gray-300': form.subscription_tier === 'free',
                                    'bg-green-900/40 text-green-300': form.subscription_tier === 'standard',
                                    'bg-orange-900/40 text-orange-300': form.subscription_tier === 'pro'
                                }">
                                    {{ form.subscription_tier.toUpperCase() }} PLAN
                                </span>
                                <span class="text-white font-medium">
                                    {{ planDetails[form.subscription_tier].name }}
                                </span>
                            </div>
                            <!-- Plan Description -->
                            <p class="mt-2 text-sm text-gray-400">
                                {{ planDetails[form.subscription_tier].description }}
                            </p>
                        </div>
                        
                        <!-- Action Button -->
                        <button
                            type="button"
                            @click="openSubscriptionModal"
                            class="ml-4 px-4 py-2 rounded-lg font-medium transition-colors"
                            :class="{
                                'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white': form.subscription_tier === 'free',
                                'bg-gray-800 border border-gray-700 hover:bg-gray-700 text-gray-300': form.subscription_tier !== 'free'
                            }">
                            {{ form.subscription_tier === 'free' ? 'Upgrade Plan' : 'Change Plan' }}
                        </button>
                    </div>
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

        <!-- Subscription Plan Selection Modal -->
        <div v-if="showSubscriptionModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4">
                <!-- Background Overlay -->
                <div class="fixed inset-0 bg-black/70" @click="closeSubscriptionModal"></div>
                
                <!-- Modal Container -->
                <div class="relative w-full max-w-4xl bg-gray-950 rounded-2xl border border-gray-800 shadow-2xl overflow-hidden">
                    <!-- Modal Header -->
                    <div class="p-6 border-b border-gray-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-bold text-white">Choose Your Plan</h3>
                            <button @click="closeSubscriptionModal" class="text-gray-500 hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-2 text-gray-400">Select the plan that best fits your fitness journey</p>
                    </div>
                    
                    <!-- Plans Comparison Table -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Free Plan -->
                            <div class="border border-gray-800 rounded-xl p-6 bg-gray-900/50">
                                <div class="mb-6">
                                    <span class="inline-block px-3 py-1 bg-gray-800 text-gray-300 rounded-full text-sm font-semibold mb-3">FREE</span>
                                    <h4 class="text-xl font-bold text-white mb-2">Basic Plan</h4>
                                    <p class="text-gray-400 text-sm mb-4">Best for casual users & beginners</p>
                                    <div class="text-3xl font-bold text-white">$0<span class="text-gray-400 text-lg">/month</span></div>
                                </div>
                                <ul class="space-y-3 mb-6">
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Preset workout routines only</span>
                                    </li>
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Basic timers & optional MediaPipe detection</span>
                                    </li>
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Daily & weekly workout history</span>
                                    </li>
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Basic profile customization</span>
                                    </li>
                                </ul>
                                <button @click="selectPlan('free')" class="w-full py-3 border border-gray-700 text-gray-300 rounded-lg hover:bg-gray-800 transition-colors">
                                    {{ form.subscription_tier === 'free' ? 'Current Plan' : 'Select Free' }}
                                </button>
                            </div>
                            
                            <!-- Standard Plan -->
                            <div class="border border-green-700 rounded-xl p-6 bg-gray-900 relative">
                                <div class="absolute top-0 right-0 bg-green-600 text-white px-3 py-1 text-xs font-bold rounded-bl-lg">POPULAR</div>
                                <div class="mb-6">
                                    <span class="inline-block px-3 py-1 bg-green-900/40 text-green-300 rounded-full text-sm font-semibold mb-3">STANDARD</span>
                                    <h4 class="text-xl font-bold text-white mb-2">Standard Plan</h4>
                                    <p class="text-gray-400 text-sm mb-4">Regular users wanting customization</p>
                                    <div class="text-3xl font-bold text-white">$9.99<span class="text-gray-400 text-lg">/month</span></div>
                                </div>
                                <ul class="space-y-3 mb-6">
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Everything in Free, plus...</span>
                                    </li>
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Create custom workout routines</span>
                                    </li>
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>MediaPipe Smart Mode with form feedback</span>
                                    </li>
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Monthly progress charts & analytics</span>
                                    </li>
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Join global challenges & post videos</span>
                                    </li>
                                </ul>
                                <button @click="selectPlan('standard')" :class="{
                                    'w-full py-3 rounded-lg font-medium transition-colors': true,
                                    'bg-gradient-to-r from-green-500 to-green-600 text-white hover:from-green-600 hover:to-green-700': form.subscription_tier !== 'standard',
                                    'bg-gray-800 text-gray-300 cursor-default': form.subscription_tier === 'standard'
                                }">
                                    {{ form.subscription_tier === 'standard' ? 'Current Plan' : 'Select Standard' }}
                                </button>
                            </div>
                            
                            <!-- Pro Plan -->
                            <div class="border border-orange-600 rounded-xl p-6 bg-gray-900/50">
                                <div class="mb-6">
                                    <span class="inline-block px-3 py-1 bg-orange-900/40 text-orange-300 rounded-full text-sm font-semibold mb-3">PRO</span>
                                    <h4 class="text-xl font-bold text-white mb-2">Pro Plan</h4>
                                    <p class="text-gray-400 text-sm mb-4">Dedicated users wanting advanced tools</p>
                                    <div class="text-3xl font-bold text-white">$19.99<span class="text-gray-400 text-lg">/month</span></div>
                                </div>
                                <ul class="space-y-3 mb-6">
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Everything in Standard, plus...</span>
                                    </li>
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Advanced MediaPipe Smart Tracking</span>
                                    </li>
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Real-time form scoring & posture heatmaps</span>
                                    </li>
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Advanced analytics & exportable data</span>
                                    </li>
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Pro Badge & exclusive features</span>
                                    </li>
                                </ul>
                                <button @click="selectPlan('pro')" :class="{
                                    'w-full py-3 rounded-lg font-medium transition-colors': true,
                                    'bg-gradient-to-r from-orange-500 to-orange-600 text-white hover:from-orange-600 hover:to-orange-700': form.subscription_tier !== 'pro',
                                    'bg-gray-800 text-gray-300 cursor-default': form.subscription_tier === 'pro'
                                }">
                                    {{ form.subscription_tier === 'pro' ? 'Current Plan' : 'Select Pro' }}
                                </button>
                            </div>
                        </div>
                        
                        <!-- Payment Method Section (Can be expanded) -->
                        <div v-if="selectedPlanForPurchase && selectedPlanForPurchase !== 'free'" class="mt-8 p-6 border border-gray-800 rounded-xl bg-gray-900/50">
                            <h4 class="text-lg font-semibold text-white mb-4">Payment Details</h4>
                            <div class="text-center py-4 text-gray-400">
                                <!-- Payment integration would go here -->
                                <p>To complete your subscription, you'll be redirected to our secure payment gateway.</p>
                                <div class="mt-4 flex items-center justify-center gap-2 text-sm">
                                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Secure payment powered by Stripe</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="p-6 border-t border-gray-800 bg-gray-900/30">
                        <div class="flex justify-end gap-3">
                            <button @click="closeSubscriptionModal" class="px-5 py-2.5 border border-gray-700 text-gray-300 rounded-lg hover:bg-gray-800 transition-colors">
                                Cancel
                            </button>
                            <button 
                                v-if="selectedPlanForPurchase && selectedPlanForPurchase !== form.subscription_tier"
                                @click="processPlanChange"
                                class="px-5 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-colors">
                                {{ selectedPlanForPurchase === 'free' ? 'Switch to Free' : 'Confirm & Upgrade' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

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
const showSubscriptionModal = ref(false);
const selectedPlanForPurchase = ref(null);

const planDetails = {
    free: {
        name: "Basic",
        description: "For casual users & beginners. Access preset workouts, basic tracking, and community feed.",
        price: 0,
        color: "gray"
    },
    standard: {
        name: "Standard",
        description: "For regular users. Custom workouts, better analytics, and full community features.",
        price: 9.99,
        color: "green"
    },
    pro: {
        name: "Pro",
        description: "For dedicated athletes. Advanced tracking, exclusive features, and priority support.",
        price: 19.99,
        color: "orange"
    }
};

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

// Modal control functions
const openSubscriptionModal = () => {
    showSubscriptionModal.value = true;
    selectedPlanForPurchase.value = null;
};

const closeSubscriptionModal = () => {
    showSubscriptionModal.value = false;
    selectedPlanForPurchase.value = null;
};

const selectPlan = (plan) => {
    selectedPlanForPurchase.value = plan;
};

const processPlanChange = async () => {
    if (!selectedPlanForPurchase.value) return;
    
    // Update the form with the selected plan
    form.subscription_tier = selectedPlanForPurchase.value;
    
    // For paid plans, you would typically:
    // 1. Redirect to a payment gateway (Stripe, etc.)
    // 2. Process the payment
    // 3. Update the user's subscription on your backend
    
    if (selectedPlanForPurchase.value === 'free') {
        // For free plan, just update directly
        form.patch(route('profile.update'), {
            preserveScroll: true,
            onSuccess: () => {
                if (window.toast) {
                    window.toast.success('Successfully switched to Free plan!');
                }
                closeSubscriptionModal();
            },
            onError: (errors) => {
                console.error('Failed to update plan:', errors);
                if (window.toast) {
                    window.toast.error('Failed to update plan. Please try again.');
                }
            },
        });
    } else {
        // For paid plans, redirect to payment or show payment form
        if (window.toast) {
            window.toast.info(`Redirecting to payment for ${selectedPlanForPurchase.value.toUpperCase()} plan...`);
        }
        
        // Example: Redirect to a payment route
        // window.location.href = route('subscription.checkout', {
        //     plan: selectedPlanForPurchase.value
        // });
        
        // For now, just update locally
        closeSubscriptionModal();
    }
};

const submit = () => {
    // Final check: ensure all required fields are set before submission
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
    
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
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
</script>