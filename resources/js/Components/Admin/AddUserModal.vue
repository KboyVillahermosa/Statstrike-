<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const emit = defineEmits(['close']);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    is_admin: false,
    subscription_tier: 'free',
    fitness_goal: '',
    experience_level: '',
});

const submit = () => {
    form.post(route('admin.users.store'), {
        preserveScroll: true,
        onSuccess: () => {
            emit('close');
            form.reset();
            // The page will refresh automatically via Inertia
        },
    });
};
</script>

<template>
    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white">Add New User</h2>
            <button
                @click="emit('close')"
                class="text-gray-400 hover:text-white transition-colors"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Name -->
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                    required
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Email -->
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

            <!-- Password -->
            <div>
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                    required
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Password Confirmation -->
            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                    required
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <!-- Admin Role -->
            <div>
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        v-model="form.is_admin"
                        class="rounded border-gray-800 bg-gray-900 text-orange-500 focus:ring-orange-500"
                    />
                    <span class="text-gray-300">Make this user an admin</span>
                </label>
                <InputError class="mt-2" :message="form.errors.is_admin" />
            </div>

            <!-- Subscription Tier -->
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

            <!-- Experience Level -->
            <div>
                <InputLabel for="experience_level" value="Experience Level (Optional)" />
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

            <!-- Fitness Goal -->
            <div>
                <InputLabel for="fitness_goal" value="Fitness Goal (Optional)" />
                <TextInput
                    id="fitness_goal"
                    v-model="form.fitness_goal"
                    type="text"
                    class="mt-1 block w-full bg-gray-900 border-gray-800 text-white"
                />
                <InputError class="mt-2" :message="form.errors.fitness_goal" />
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
                <PrimaryButton :disabled="form.processing" class="flex-1">
                    {{ form.processing ? 'Creating...' : 'Create User' }}
                </PrimaryButton>
                <button
                    type="button"
                    @click="emit('close')"
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>

