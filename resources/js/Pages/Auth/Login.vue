<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
            <p class="text-gray-400">Sign in to continue your training journey</p>
        </div>

        <!-- Status Message -->
        <div v-if="status" class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-lg">
            <p class="text-green-400 text-sm font-medium">{{ status }}</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Email Field -->
            <div>
                <InputLabel for="email" value="Email" class="text-gray-300 font-semibold" />

                <TextInput
                    id="email"
                    type="email"
                    class="bg-gray-900/50 border-gray-800 text-white placeholder-gray-600 focus:border-orange-500 focus:ring-orange-500/50"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="your.email@example.com"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Password Field -->
            <div>
                <InputLabel for="password" value="Password" class="text-gray-300 font-semibold" />

                <TextInput
                    id="password"
                    type="password"
                    class="bg-gray-900/50 border-gray-800 text-white placeholder-gray-600 focus:border-orange-500 focus:ring-orange-500/50"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-400">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-orange-500 hover:text-orange-400 transition-colors"
                >
                    Forgot password?
                </Link>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="form.processing">Signing in...</span>
                <span v-else>Sign In</span>
            </button>

            <!-- Register Link -->
            <div class="text-center pt-4 border-t border-gray-900">
                <p class="text-gray-400 text-sm">
                    Don't have an account?
                    <Link :href="route('register')" class="text-orange-500 hover:text-orange-400 font-semibold transition-colors">
                        Sign up
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
