<template>
  <div class="flex h-screen bg-black overflow-hidden">
    <!-- Mobile Menu Button -->
    <button
      @click="sidebarOpen = !sidebarOpen"
      class="lg:hidden fixed top-4 left-4 z-50 p-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg shadow-lg transition-colors"
      aria-label="Toggle menu"
    >
      <svg v-if="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
      <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Sidebar Overlay (Mobile) -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
        class="lg:hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-40"
    ></div>
    </Transition>

    <!-- Sidebar -->
    <aside
      class="fixed lg:relative left-0 top-0 h-screen w-64 bg-gray-950 border-r border-gray-900/50 flex flex-col z-40 transform transition-transform duration-300 ease-in-out lg:translate-x-0 shadow-xl lg:shadow-none"
      :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']"
    >
      <!-- Logo -->
      <div class="p-6 border-b border-gray-900/50">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 flex items-center justify-center">
            <img src="/images/48pxLogo.png" alt="Stat Strike" class="w-full h-full object-cover rounded-full" />
          </div>
          <h1 class="text-xl font-bold text-white tracking-tight">Stat Strike</h1>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <NavLink href="/dashboard" :active="isActive('/dashboard')" icon="dashboard">
          Dashboard
        </NavLink>
        <NavDropdown
          title="Workouts"
          icon="workouts"
          :items="[
            { id: 1, label: 'Log Workout', href: '/workouts/log', icon: 'document' },
            { id: 2, label: 'Workout History', href: '/workouts/history', icon: 'chart' },
            { id: 3, label: 'Workout Templates', href: '/workouts/templates', icon: 'clipboard' },
            { id: 4, label: 'Motion Tracker', href: '/workouts/tracker', icon: 'camera' }
          ]"
        />
        <NavLink href="/challenges" :active="isActive('/challenges')" icon="trophy">
          Challenges
        </NavLink>
        <NavLink href="/community" :active="isActive('/community')" icon="community">
          Community
        </NavLink>
        <NavDropdown
          title="AI Tools"
          icon="ai"
          :items="[
            { id: 1, label: 'Workout Generator', href: '/ai-tools/workout-generator', icon: 'wrench' },
            { id: 2, label: 'Progress Insights', href: '/ai-tools/progress-insights', icon: 'chart' },
            { id: 3, label: 'Boxing Coach', href: '/ai-tools/boxing-coach', icon: 'boxing' }
          ]"
        />
      </nav>

      <!-- User Section -->
      <div class="p-4 border-t border-gray-900/50">
        <Link
          href="/profile"
          class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-300 hover:text-white hover:bg-gray-900/50 transition-colors duration-200 group"
        >
          <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
          <span class="font-medium truncate">User</span>
        </Link>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto w-full lg:w-auto pt-16 lg:pt-0 bg-black">
      <div class="p-4 md:p-6 lg:p-8 min-h-full">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import NavDropdown from '@/Components/NavDropdown.vue';
import { useToast } from '@/composables/useToast';

const page = usePage();
const sidebarOpen = ref(false);

// Initialize toast watcher for flash messages
useToast();

const isActive = (href) => {
  const url = page.url;
  if (href === '/dashboard') {
    return url === '/dashboard' || url === '/';
  }
  return url === href || url.startsWith(href + '/');
};
</script>
