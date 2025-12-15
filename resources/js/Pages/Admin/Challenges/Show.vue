<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    challenge: {
        type: Object,
        required: true,
    },
});

const toggleActiveForm = useForm({});
const deleteForm = useForm({});

const toggleActive = () => {
    toggleActiveForm.patch(route('admin.challenges.toggle-active', props.challenge.id), {
        preserveScroll: true,
    });
};

const deleteChallenge = () => {
    if (confirm('Are you sure you want to delete this challenge? This action cannot be undone.')) {
        deleteForm.delete(route('admin.challenges.destroy', props.challenge.id));
    }
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <Head :title="`Challenge: ${challenge.title}`" />

    <AdminLayout>
        <div>
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <Link
                            :href="route('admin.challenges.index')"
                            class="text-gray-400 hover:text-white transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </Link>
                        <div>
                            <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">{{ challenge.title }}</h1>
                            <p class="text-xl text-gray-400">
                                Challenge Details & Management
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <Link
                            :href="route('admin.challenges.edit', challenge.id)"
                            class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors"
                        >
                            Edit Challenge
                        </Link>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info Card -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Challenge Information -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-2xl font-bold text-white mb-6">Challenge Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
                                <p class="text-white">{{ challenge.description }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Type</label>
                                    <p class="text-white capitalize">{{ challenge.type }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Target</label>
                                    <p class="text-white">{{ challenge.target_count }} {{ challenge.target_unit }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Duration</label>
                                    <p class="text-white">{{ challenge.duration_days }} days</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Status</label>
                                    <span
                                        v-if="challenge.is_active"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500/20 text-green-400 border border-green-500/30"
                                    >
                                        Active
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-700 text-gray-300 border border-gray-600"
                                    >
                                        Inactive
                                    </span>
                                </div>
                            </div>
                            <div v-if="challenge.starts_at || challenge.ends_at" class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Starts At</label>
                                    <p class="text-white">{{ formatDate(challenge.starts_at) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Ends At</label>
                                    <p class="text-white">{{ formatDate(challenge.ends_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Participants -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-2xl font-bold text-white mb-6">Top Participants</h2>
                        <div class="space-y-3">
                            <div
                                v-for="(user, index) in challenge.users"
                                :key="user.id"
                                class="flex items-center justify-between p-3 bg-gray-900 rounded-lg"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ index + 1 }}
                                    </div>
                                    <div>
                                        <div class="text-white font-medium">{{ user.name }}</div>
                                        <div class="text-gray-400 text-sm">{{ user.email }}</div>
                                    </div>
                                </div>
                                <div class="text-orange-400 font-bold">{{ user.pivot?.progress || 0 }} / {{ challenge.target_count }}</div>
                            </div>
                            <div v-if="!challenge.users || challenge.users.length === 0" class="text-center text-gray-400 py-8">
                                No participants yet
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-xl font-bold text-white mb-4">Status</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Active Status</label>
                                <span
                                    v-if="challenge.is_active"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500/20 text-green-400 border border-green-500/30"
                                >
                                    Active
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-700 text-gray-300 border border-gray-600"
                                >
                                    Inactive
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Participants</label>
                                <p class="text-white">{{ challenge.users?.length || 0 }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Created</label>
                                <p class="text-white">{{ formatDate(challenge.created_at) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-xl font-bold text-white mb-4">Actions</h2>
                        <div class="space-y-3">
                            <button
                                @click="toggleActive"
                                :disabled="toggleActiveForm.processing"
                                class="w-full px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors disabled:opacity-50"
                            >
                                {{ challenge.is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                            <button
                                @click="deleteChallenge"
                                :disabled="deleteForm.processing"
                                class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-colors disabled:opacity-50"
                            >
                                Delete Challenge
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

