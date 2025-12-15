<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { ref } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const isEditing = ref(false);

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    fitness_goal: props.user.fitness_goal || '',
    experience_level: props.user.experience_level || '',
    subscription_tier: props.user.subscription_tier || 'free',
    points: props.user.points || 0,
});

const toggleAdminForm = useForm({});
const deleteForm = useForm({});

const submit = () => {
    form.put(route('admin.users.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            isEditing.value = false;
        },
    });
};

const toggleAdmin = () => {
    if (confirm(`Are you sure you want to ${props.user.is_admin ? 'remove' : 'grant'} admin status to this user?`)) {
        toggleAdminForm.patch(route('admin.users.toggle-admin', props.user.id), {
            preserveScroll: true,
        });
    }
};

const deleteUser = () => {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        deleteForm.delete(route('admin.users.destroy', props.user.id));
    }
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head :title="`User: ${user.name}`" />

    <DashboardLayout>
        <div>
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <Link
                            :href="route('admin.users.index')"
                            class="text-gray-400 hover:text-white transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </Link>
                        <div>
                            <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">{{ user.name }}</h1>
                            <p class="text-xl text-gray-400">
                                User Details & Management
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button
                            v-if="!isEditing"
                            @click="isEditing = true"
                            class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors"
                        >
                            Edit User
                        </button>
                        <button
                            v-else
                            @click="isEditing = false; form.reset()"
                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info Card -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- User Information -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-2xl font-bold text-white mb-6">User Information</h2>

                        <form v-if="isEditing" @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="name" value="Name" />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="email" value="Email" />
                                <TextInput
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div>
                                <InputLabel for="fitness_goal" value="Fitness Goal" />
                                <TextInput
                                    id="fitness_goal"
                                    v-model="form.fitness_goal"
                                    type="text"
                                    class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                                />
                                <InputError class="mt-2" :message="form.errors.fitness_goal" />
                            </div>

                            <div>
                                <InputLabel for="experience_level" value="Experience Level" />
                                <select
                                    id="experience_level"
                                    v-model="form.experience_level"
                                    class="mt-1 block w-full bg-gray-900 border-gray-800 rounded-lg px-4 py-2.5 text-white focus:border-orange-500 focus:ring-orange-500 focus:outline-none"
                                >
                                    <option value="">Select Level</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                    <option value="expert">Expert</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.experience_level" />
                            </div>

                            <div>
                                <InputLabel for="subscription_tier" value="Subscription Tier" />
                                <select
                                    id="subscription_tier"
                                    v-model="form.subscription_tier"
                                    class="mt-1 block w-full bg-gray-900 border-gray-800 rounded-lg px-4 py-2.5 text-white focus:border-orange-500 focus:ring-orange-500 focus:outline-none"
                                >
                                    <option value="free">Free</option>
                                    <option value="standard">Standard</option>
                                    <option value="pro">Pro</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.subscription_tier" />
                            </div>

                            <div>
                                <InputLabel for="points" value="Points" />
                                <TextInput
                                    id="points"
                                    v-model="form.points"
                                    type="number"
                                    min="0"
                                    class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                                />
                                <InputError class="mt-2" :message="form.errors.points" />
                            </div>

                            <div class="flex gap-3">
                                <PrimaryButton :disabled="form.processing">
                                    Save Changes
                                </PrimaryButton>
                                <button
                                    type="button"
                                    @click="isEditing = false; form.reset()"
                                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>

                        <div v-else class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Name</label>
                                <p class="text-white">{{ user.name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                                <p class="text-white">{{ user.email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Fitness Goal</label>
                                <p class="text-white">{{ user.fitness_goal || 'Not set' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Experience Level</label>
                                <p class="text-white capitalize">{{ user.experience_level || 'Not set' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Subscription Tier</label>
                                <p class="text-white capitalize">{{ user.subscription_tier || 'free' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Points</label>
                                <p class="text-white">{{ user.points || 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-2xl font-bold text-white mb-6">Recent Activity</h2>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-2">Workouts</h3>
                                <p class="text-gray-400">{{ user.workouts?.length || 0 }} recent workouts</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-2">Challenges</h3>
                                <p class="text-gray-400">{{ user.challenges?.length || 0 }} active challenges</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-2">Posts</h3>
                                <p class="text-gray-400">{{ user.posts?.length || 0 }} community posts</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-2">Workout Routines</h3>
                                <p class="text-gray-400">{{ user.workout_routines?.length || 0 }} routines created</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- User Status Card -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-xl font-bold text-white mb-4">Status</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Role</label>
                                <span
                                    v-if="user.is_admin"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-500/20 text-orange-400 border border-orange-500/30"
                                >
                                    Admin
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-700 text-gray-300 border border-gray-600"
                                >
                                    User
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Member Since</label>
                                <p class="text-white">{{ formatDate(user.created_at) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Last Updated</label>
                                <p class="text-white">{{ formatDate(user.updated_at) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-xl font-bold text-white mb-4">Actions</h2>
                        <div class="space-y-3">
                            <button
                                @click="toggleAdmin"
                                :disabled="toggleAdminForm.processing"
                                class="w-full px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors disabled:opacity-50"
                            >
                                {{ user.is_admin ? 'Remove Admin' : 'Make Admin' }}
                            </button>
                            <button
                                @click="deleteUser"
                                :disabled="deleteForm.processing"
                                class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-colors disabled:opacity-50"
                            >
                                Delete User
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

