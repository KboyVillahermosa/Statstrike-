<template>
  <div>
    <button
      @click="isOpen = !isOpen"
      class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200"
      :class="[
        isOpen || hasActiveChild
          ? 'bg-orange-500/10 text-orange-400 border-l-2 border-orange-500'
          : 'text-gray-400 hover:text-white hover:bg-gray-900/50'
      ]"
    >
      <!-- Workouts Icon -->
      <svg v-if="icon === 'workouts'" :class="['w-5 h-5', isOpen || hasActiveChild ? 'text-orange-400' : 'text-gray-400']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
      <!-- AI Icon -->
      <svg v-else-if="icon === 'ai'" :class="['w-5 h-5', isOpen || hasActiveChild ? 'text-orange-400' : 'text-gray-400']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
      </svg>
      <span v-else-if="icon" class="text-lg">{{ icon }}</span>
      <span class="font-medium flex-1 text-left">{{ title }}</span>
      <svg
        class="w-4 h-4 transition-transform duration-300 shrink-0"
        :class="{ 'rotate-180': isOpen || hasActiveChild }"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <!-- Dropdown Items -->
    <Transition
      enter-active-class="transition-all ease-out duration-200"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all ease-in duration-150"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div 
        v-if="isOpen || hasActiveChild" 
        class="mt-1.5 ml-4 space-y-0.5 border-l border-gray-800 pl-4"
      >
        <Link
          v-for="item in items"
          :key="item.id"
          :href="item.href"
          :class="[
            'flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 text-sm',
            isActive(item.href)
              ? 'bg-gray-900/70 text-orange-400 font-semibold border-l-2 border-orange-500 -ml-4 pl-6'
              : 'text-gray-400 hover:text-white hover:bg-gray-900/50 font-medium'
          ]"
        >
          <!-- Document Icon -->
          <svg v-if="item.icon === 'document'" :class="['w-4 h-4', isActive(item.href) ? 'text-orange-400' : 'text-gray-500']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <!-- Clipboard Icon -->
          <svg v-else-if="item.icon === 'clipboard'" :class="['w-4 h-4', isActive(item.href) ? 'text-orange-400' : 'text-gray-500']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <!-- Camera Icon -->
          <svg v-else-if="item.icon === 'camera'" :class="['w-4 h-4', isActive(item.href) ? 'text-orange-400' : 'text-gray-500']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
          </svg>
          <!-- Chart Icon -->
          <svg v-else-if="item.icon === 'chart'" :class="['w-4 h-4', isActive(item.href) ? 'text-orange-400' : 'text-gray-500']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <!-- Wrench Icon (for Workout Generator) -->
          <svg v-else-if="item.icon === 'wrench'" :class="['w-4 h-4', isActive(item.href) ? 'text-orange-400' : 'text-gray-500']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <!-- Boxing Glove Icon (for Boxing Coach) -->
          <svg v-else-if="item.icon === 'boxing'" :class="['w-4 h-4', isActive(item.href) ? 'text-orange-400' : 'text-gray-500']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
          </svg>
          <span v-else-if="item.icon" class="text-base">{{ item.icon }}</span>
          <span>{{ item.label }}</span>
        </Link>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  icon: {
    type: String,
    default: '',
  },
  items: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();
const isOpen = ref(false);

const currentUrl = computed(() => page.url);

const isActive = (href) => {
  return currentUrl.value === href || currentUrl.value.startsWith(href + '/');
};

const hasActiveChild = computed(() => {
  return props.items.some(item => isActive(item.href));
});

// Auto-expand if has active child
onMounted(() => {
  if (hasActiveChild.value) {
    isOpen.value = true;
  }
});
</script>
