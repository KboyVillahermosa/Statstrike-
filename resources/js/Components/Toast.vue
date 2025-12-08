<template>
  <Teleport to="body">
    <TransitionGroup
      name="toast"
      tag="div"
      class="fixed top-4 right-4 z-50 flex flex-col gap-3 max-w-md w-full pointer-events-none"
    >
      <div
        v-for="toast in toasts"
        :key="toast.id"
        :class="[
          'pointer-events-auto rounded-lg shadow-xl p-4 border transition-all duration-300',
          toast.type === 'success' 
            ? 'bg-green-500/10 border-green-500/20 backdrop-blur-sm' 
            : toast.type === 'error'
            ? 'bg-red-500/10 border-red-500/20 backdrop-blur-sm'
            : toast.type === 'warning'
            ? 'bg-yellow-500/10 border-yellow-500/20 backdrop-blur-sm'
            : 'bg-blue-500/10 border-blue-500/20 backdrop-blur-sm'
        ]"
      >
        <div class="flex items-start gap-3">
          <!-- Icon -->
          <div
            :class="[
              'flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center',
              toast.type === 'success'
                ? 'bg-green-500/20 text-green-400'
                : toast.type === 'error'
                ? 'bg-red-500/20 text-red-400'
                : toast.type === 'warning'
                ? 'bg-yellow-500/20 text-yellow-400'
                : 'bg-blue-500/20 text-blue-400'
            ]"
          >
            <!-- Success Icon -->
            <svg v-if="toast.type === 'success'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <!-- Error Icon -->
            <svg v-else-if="toast.type === 'error'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <!-- Warning Icon -->
            <svg v-else-if="toast.type === 'warning'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <!-- Info Icon -->
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>

          <!-- Content -->
          <div class="flex-1 min-w-0">
            <p
              :class="[
                'text-sm font-semibold',
                toast.type === 'success'
                  ? 'text-green-400'
                  : toast.type === 'error'
                  ? 'text-red-400'
                  : toast.type === 'warning'
                  ? 'text-yellow-400'
                  : 'text-blue-400'
              ]"
            >
              {{ toast.message }}
            </p>
          </div>

          <!-- Close Button -->
          <button
            @click="removeToast(toast.id)"
            class="flex-shrink-0 text-gray-400 hover:text-gray-300 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </TransitionGroup>
  </Teleport>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const toasts = ref([]);
let toastIdCounter = 0;

const addToast = (message, type = 'success', duration = 5000) => {
  const id = ++toastIdCounter;
  const toast = { id, message, type };
  
  toasts.value.push(toast);

  if (duration > 0) {
    setTimeout(() => {
      removeToast(id);
    }, duration);
  }

  return id;
};

const removeToast = (id) => {
  const index = toasts.value.findIndex(toast => toast.id === id);
  if (index > -1) {
    toasts.value.splice(index, 1);
  }
};

// Make functions available globally
onMounted(() => {
  window.toast = {
    success: (message, duration) => {
      console.log('Toast.success called with:', message);
      addToast(message, 'success', duration);
    },
    error: (message, duration) => {
      console.log('Toast.error called with:', message);
      addToast(message, 'error', duration);
    },
    warning: (message, duration) => {
      console.log('Toast.warning called with:', message);
      addToast(message, 'warning', duration);
    },
    info: (message, duration) => {
      console.log('Toast.info called with:', message);
      addToast(message, 'info', duration);
    },
  };
  console.log('Toast component mounted, window.toast initialized');
});

onUnmounted(() => {
  delete window.toast;
});

// Expose methods for parent components if needed
defineExpose({
  addToast,
  removeToast,
});
</script>

<style scoped>
.toast-enter-active {
  transition: all 0.3s ease-out;
}

.toast-leave-active {
  transition: all 0.3s ease-in;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

.toast-move {
  transition: transform 0.3s ease;
}
</style>

