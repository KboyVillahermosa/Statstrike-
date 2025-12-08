import { watch, onMounted, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';

let processedFlash = null;

export function useToast() {
  const page = usePage();

  // Watch for flash messages and show toasts
  watch(
    () => page.props.flash,
    async (flash) => {
      if (!flash) return;

      // Check if any flash message exists
      const hasFlash = flash.success || flash.error || flash.warning || flash.info;
      if (!hasFlash) return;

      // Create a unique identifier for this flash message
      const flashId = JSON.stringify(flash);
      
      // Skip if we've already processed this flash message
      if (processedFlash === flashId) return;
      processedFlash = flashId;

      // Wait for next tick and then try to show toast
      await nextTick();

      // Wait for Toast component to be ready (with more attempts)
      const tryShowToast = (attempts = 0) => {
        if (window.toast && typeof window.toast.success === 'function') {
          if (flash.success) {
            console.log('Showing success toast:', flash.success);
            window.toast.success(flash.success);
          }
          if (flash.error) {
            console.log('Showing error toast:', flash.error);
            window.toast.error(flash.error);
          }
          if (flash.warning) {
            console.log('Showing warning toast:', flash.warning);
            window.toast.warning(flash.warning);
          }
          if (flash.info) {
            console.log('Showing info toast:', flash.info);
            window.toast.info(flash.info);
          }
        } else if (attempts < 50) {
          // Try up to 50 times (2.5 seconds total)
          setTimeout(() => tryShowToast(attempts + 1), 50);
        } else {
          // Fallback: log to console if toast system isn't ready
          console.warn('Toast system not ready after 50 attempts. Flash messages:', flash);
          console.warn('window.toast:', window.toast);
        }
      };

      tryShowToast();
    },
    { deep: true, immediate: true }
  );

  // Reset processed flash on mount (new page load)
  onMounted(() => {
    processedFlash = null;
  });

  return {
    toast: window.toast || {
      success: (msg) => {
        console.log('Toast success:', msg);
        if (window.toast) window.toast.success(msg);
      },
      error: (msg) => {
        console.log('Toast error:', msg);
        if (window.toast) window.toast.error(msg);
      },
      warning: (msg) => {
        console.log('Toast warning:', msg);
        if (window.toast) window.toast.warning(msg);
      },
      info: (msg) => {
        console.log('Toast info:', msg);
        if (window.toast) window.toast.info(msg);
      },
    },
  };
}

