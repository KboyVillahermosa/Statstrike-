<template>
  <Head title="Welcome to Stat Strike" />
  
  <div class="min-h-screen bg-black text-white">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-black/80 backdrop-blur-md border-b border-gray-900/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <!-- Logo and Tagline -->
          <div class="flex items-center gap-6">
            <Link href="/" class="flex items-center gap-3">
              <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg shadow-orange-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <span class="text-xl font-bold text-white">Stat Strike</span>
            </Link>
            <p class="hidden md:block text-sm text-gray-400">
              The Ultimate Training Companion for Martial Artists
            </p>
          </div>

          <!-- Navigation -->
          <nav v-if="canLogin" class="flex items-center gap-4">
            <Link
              v-if="$page.props.auth.user"
              :href="route('dashboard')"
              class="px-4 py-2 text-gray-300 hover:text-white transition-colors"
            >
              Dashboard
            </Link>
            <template v-else>
              <Link
                :href="route('login')"
                class="px-4 py-2 text-gray-300 hover:text-white transition-colors"
              >
                Log in
              </Link>
              <Link
                v-if="canRegister"
                :href="route('register')"
                class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30"
              >
                Sign In
              </Link>
            </template>
          </nav>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="pt-32 pb-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-10 leading-tight text-white">
          Track your workouts, compete in challenges, and get AI-powered
          insights to level up your skills.
        </h1>
        <div class="flex flex-col items-center gap-6">
          <Link
            v-if="canRegister"
            :href="route('register')"
            class="inline-block px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold text-lg rounded-lg transition-all duration-200 shadow-xl shadow-orange-500/30 hover:shadow-2xl hover:shadow-orange-500/40 transform hover:scale-105"
          >
            Join the Fight
          </Link>
          <Link
            v-else-if="$page.props.auth.user"
            :href="route('dashboard')"
            class="inline-block px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold text-lg rounded-lg transition-all duration-200 shadow-xl shadow-orange-500/30 hover:shadow-2xl hover:shadow-orange-500/40 transform hover:scale-105"
          >
            Go to Dashboard
          </Link>
          <h2 class="text-3xl md:text-4xl font-bold text-white mt-8">Features</h2>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-12 px-4 sm:px-6 lg:px-8 bg-gray-950/50">
      <div class="max-w-5xl mx-auto">
        <p class="text-center text-xl text-gray-300 mb-12">
          Stat Strike is packed with features to help you become a better martial artist.
        </p>

        <div class="space-y-6">
          <!-- Feature Card 1 -->
          <div class="bg-gray-900/70 border border-gray-800 rounded-xl overflow-hidden hover:border-orange-500/50 transition-all duration-300">
            <div class="h-2 bg-gradient-to-r from-orange-500 to-orange-600"></div>
            <div class="p-8 flex items-start gap-6">
              <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <div class="flex-1">
                <h3 class="text-3xl font-bold mb-3 text-white">Workout Logging</h3>
                <p class="text-gray-300 leading-relaxed text-lg">
                  Log your rounds, drills, and RPE to track your effort and consistency.
                </p>
              </div>
            </div>
          </div>

          <!-- Feature Card 2 -->
          <div class="bg-gray-900/70 border border-gray-800 rounded-xl overflow-hidden hover:border-orange-500/50 transition-all duration-300">
            <div class="h-2 bg-gradient-to-r from-orange-500 to-orange-600"></div>
            <div class="p-8 flex items-start gap-6">
              <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
              </div>
              <div class="flex-1">
                <h3 class="text-3xl font-bold mb-3 text-white">Community Challenges</h3>
                <p class="text-gray-300 leading-relaxed text-lg">
                  Join monthly challenges like '500 Kicks' to push your limits.
                </p>
              </div>
            </div>
          </div>

          <!-- Feature Card 3 -->
          <div class="bg-gray-900/70 border border-gray-800 rounded-xl overflow-hidden hover:border-orange-500/50 transition-all duration-300">
            <div class="h-2 bg-gradient-to-r from-orange-500 to-orange-600"></div>
            <div class="p-8 flex items-start gap-6">
              <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
              </div>
              <div class="flex-1">
                <h3 class="text-3xl font-bold mb-3 text-white">AI Coaching</h3>
                <p class="text-gray-300 leading-relaxed text-lg">
                  Get personalized workout plans and progress insights from our AI.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8">
      <div class="max-w-4xl mx-auto text-center">
        <div class="bg-gradient-to-r from-orange-500/10 to-orange-600/10 border border-orange-500/20 rounded-2xl p-12">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Ready to Transform Your Training?
          </h2>
          <p class="text-xl text-gray-300 mb-8">
            Join thousands of martial artists tracking their progress with Stat Strike.
          </p>
          <Link
            v-if="canRegister"
            :href="route('register')"
            class="inline-block px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold text-lg rounded-lg transition-all duration-200 shadow-xl shadow-orange-500/30 hover:shadow-2xl hover:shadow-orange-500/40 transform hover:scale-105"
          >
            Get Started Free
          </Link>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 px-4 sm:px-6 lg:px-8 border-t border-gray-900">
      <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row items-center justify-between">
          <div class="flex items-center gap-3 mb-4 md:mb-0">
            <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <span class="text-lg font-bold text-white">Stat Strike</span>
          </div>
          <p class="text-gray-400 text-sm">
            &copy; {{ new Date().getFullYear() }} Stat Strike. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
});
</script>
