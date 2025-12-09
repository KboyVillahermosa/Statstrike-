<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const processing = ref(false);

const logout = () => {
    if (processing.value) return;
    processing.value = true;

    router.post(route('logout'), {}, {
        preserveState: false,
        replace: true,
        onSuccess: () => {
            // Ensure a fresh visit so $page.props.auth.user is updated
            router.visit('/', { replace: true, preserveState: false });
        },
        onError: () => {
            // In case of any error, still force a fresh visit
            router.visit('/', { replace: true, preserveState: false });
        },
        onFinish: () => {
            processing.value = false;
        },
    });
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
