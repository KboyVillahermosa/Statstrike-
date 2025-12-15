<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    title: '',
    description: '',
    type: 'kicks',
    target_count: 0,
    target_unit: '',
    duration_days: 30,
    is_active: true,
    starts_at: '',
    ends_at: '',
    image_url: '',
});

const submit = () => {
    form.post(route('admin.challenges.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Create Challenge" />

    <AdminLayout>
        <div>
            <!-- Header -->
            <div class="mb-8">
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
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">Create Challenge</h1>
                        <p class="text-xl text-gray-400">
                            Add a new challenge to the system
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-gray-950 rounded-xl border border-gray-900 p-6 max-w-3xl">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Title -->
                    <div>
                        <InputLabel for="title" value="Title" />
                        <TextInput
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>

                    <!-- Description -->
                    <div>
                        <InputLabel for="description" value="Description" />
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="mt-1 block w-full bg-gray-900 border-gray-800 rounded-lg px-4 py-2.5 text-white focus:border-orange-500 focus:ring-orange-500 focus:outline-none"
                            required
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>

                    <!-- Type and Target -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="type" value="Type" />
                            <select
                                id="type"
                                v-model="form.type"
                                class="mt-1 block w-full bg-gray-900 border-gray-800 rounded-lg px-4 py-2.5 text-white focus:border-orange-500 focus:ring-orange-500 focus:outline-none"
                                required
                            >
                                <option value="kicks">Kicks</option>
                                <option value="core">Core</option>
                                <option value="endurance">Endurance</option>
                                <option value="strength">Strength</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.type" />
                        </div>

                        <div>
                            <InputLabel for="target_count" value="Target Count" />
                            <TextInput
                                id="target_count"
                                v-model="form.target_count"
                                type="number"
                                min="1"
                                class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.target_count" />
                        </div>
                    </div>

                    <!-- Target Unit and Duration -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="target_unit" value="Target Unit" />
                            <TextInput
                                id="target_unit"
                                v-model="form.target_unit"
                                type="text"
                                placeholder="e.g., kicks, rounds, days"
                                class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.target_unit" />
                        </div>

                        <div>
                            <InputLabel for="duration_days" value="Duration (Days)" />
                            <TextInput
                                id="duration_days"
                                v-model="form.duration_days"
                                type="number"
                                min="1"
                                class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.duration_days" />
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="starts_at" value="Starts At (Optional)" />
                            <TextInput
                                id="starts_at"
                                v-model="form.starts_at"
                                type="datetime-local"
                                class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                            />
                            <InputError class="mt-2" :message="form.errors.starts_at" />
                        </div>

                        <div>
                            <InputLabel for="ends_at" value="Ends At (Optional)" />
                            <TextInput
                                id="ends_at"
                                v-model="form.ends_at"
                                type="datetime-local"
                                class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                            />
                            <InputError class="mt-2" :message="form.errors.ends_at" />
                        </div>
                    </div>

                    <!-- Image URL -->
                    <div>
                        <InputLabel for="image_url" value="Image URL (Optional)" />
                        <TextInput
                            id="image_url"
                            v-model="form.image_url"
                            type="url"
                            class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                        />
                        <InputError class="mt-2" :message="form.errors.image_url" />
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                v-model="form.is_active"
                                class="rounded border-gray-800 bg-gray-900 text-orange-500 focus:ring-orange-500"
                            />
                            <span class="text-gray-300">Make this challenge active</span>
                        </label>
                        <InputError class="mt-2" :message="form.errors.is_active" />
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4">
                        <PrimaryButton :disabled="form.processing">
                            {{ form.processing ? 'Creating...' : 'Create Challenge' }}
                        </PrimaryButton>
                        <Link
                            :href="route('admin.challenges.index')"
                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

