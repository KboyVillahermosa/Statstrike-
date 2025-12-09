<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import { ref } from 'vue';

const processing = ref(false);

const logout = async () => {
    if (processing.value) return;
    processing.value = true;

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
        await fetch('/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
            },
            credentials: 'same-origin',
            body: JSON.stringify({}),
        });

        // On success redirect to homepage (or reload to clear session)
        window.location.href = '/';
    } catch (e) {
        // fallback: reload page
        window.location.reload();
    } finally {
        processing.value = false;
    }
};
</script>

<template>
    <section class="space-y-6">
        <header class="mb-6">
            <h2 class="text-xl font-semibold text-white">Logout</h2>
            <p class="mt-1 text-sm text-gray-400">Sign out of your account on this device. You will remain logged in on other devices unless you revoke sessions.</p>
        </header>

        <DangerButton :disabled="processing" @click.prevent="logout">Logout</DangerButton>
    </section>
</template>
