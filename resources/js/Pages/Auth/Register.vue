<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-white mb-2">Join Stat Strike</h1>
            <p class="text-gray-400">Create your account and start tracking your progress</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Name Field -->
            <div>
                <InputLabel for="name" value="Name" class="text-gray-300 font-semibold" />

                <TextInput
                    id="name"
                    type="text"
                    class="bg-gray-900/50 border-gray-800 text-white placeholder-gray-600 focus:border-orange-500 focus:ring-orange-500/50"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="John Doe"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Email Field -->
            <div>
                <InputLabel for="email" value="Email" class="text-gray-300 font-semibold" />

                <TextInput
                    id="email"
                    type="email"
                    class="bg-gray-900/50 border-gray-800 text-white placeholder-gray-600 focus:border-orange-500 focus:ring-orange-500/50"
                    v-model="form.email"
                    required
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
                    autocomplete="new-password"
                    placeholder="••••••••"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Confirm Password Field -->
            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                    class="text-gray-300 font-semibold"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="bg-gray-900/50 border-gray-800 text-white placeholder-gray-600 focus:border-orange-500 focus:ring-orange-500/50"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="form.processing">Creating account...</span>
                <span v-else>Create Account</span>
            </button>

            <!-- Login Link -->
            <div class="text-center pt-4 border-t border-gray-900">
                <p class="text-gray-400 text-sm">
                    Already have an account?
                    <Link :href="route('login')" class="text-orange-500 hover:text-orange-400 font-semibold transition-colors">
                        Sign in
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
