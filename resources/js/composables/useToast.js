import { watch, onMounted, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';

let processedFlashIds = new Set();

export function useToast() {
  const page = usePage();

  // Function to show toast with retry logic
  const showToast = (message, type, attempts = 0) => {
    if (window.toast && typeof window.toast[type] === 'function') {
      console.log(`Showing ${type} toast:`, message);
      window.toast[type](message);
      return true;
    } else if (attempts < 100) {
      // Try up to 100 times (5 seconds total) for slower page loads
      setTimeout(() => showToast(message, type, attempts + 1), 50);
      return false;
    } else {
      console.warn(`Toast system not ready after ${attempts} attempts. Message:`, message);
      return false;
    }
  };

  // Watch for flash messages and show toasts
  watch(
    () => page.props.flash,
    async (flash) => {
      if (!flash) return;

      // Check if any flash message exists
      const hasFlash = flash.success || flash.error || flash.warning || flash.info;
      if (!hasFlash) return;

      // Create a unique identifier for this flash message
      const flashId = `${flash.success || ''}_${flash.error || ''}_${flash.warning || ''}_${flash.info || ''}`;
      
      // Skip if we've already processed this exact flash message
      if (processedFlashIds.has(flashId)) {
        return;
      }
      
      // Mark as processed
      processedFlashIds.add(flashId);

      // Wait for next tick and DOM updates
      await nextTick();
      
      // Small delay to ensure Toast component is ready
      setTimeout(() => {
        if (flash.success) {
          showToast(flash.success, 'success');
        }
        if (flash.error) {
          showToast(flash.error, 'error');
        }
        if (flash.warning) {
          showToast(flash.warning, 'warning');
        }
        if (flash.info) {
          showToast(flash.info, 'info');
        }
      }, 200);
    },
    { deep: true, immediate: true }
  );

  // Also check on mount (useful for page refreshes or direct navigation)
  onMounted(async () => {
    await nextTick();
    const flash = page.props.flash;
    
    if (flash) {
      setTimeout(() => {
        if (flash.success && window.toast) {
          showToast(flash.success, 'success');
        }
        if (flash.error && window.toast) {
          showToast(flash.error, 'error');
        }
        if (flash.warning && window.toast) {
          showToast(flash.warning, 'warning');
        }
        if (flash.info && window.toast) {
          showToast(flash.info, 'info');
        }
      }, 300);
    }
    
    // Clear processed IDs on new page mount (but keep current page's flash)
    // This allows the same flash message to show again on a fresh page load
    setTimeout(() => {
      processedFlashIds.clear();
    }, 1000);
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

