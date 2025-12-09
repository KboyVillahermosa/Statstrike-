<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

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
</script>

<template>
    <Head title="Profile" />

    <DashboardLayout>
        <div class="space-y-6">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Profile Settings</h1>
                <p class="text-gray-400">Manage your account information and preferences</p>
            </div>

            <div class="bg-gray-950 border border-gray-900 rounded-lg p-6 shadow-xl">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                    :user="user"
                    />
                </div>

            <div class="bg-gray-950 border border-gray-900 rounded-lg p-6 shadow-xl">
                <UpdatePasswordForm />
                </div>

            <div class="bg-gray-950 border border-gray-900 rounded-lg p-6 shadow-xl">
                <DeleteUserForm />
            </div>
        </div>
    </DashboardLayout>
</template>
