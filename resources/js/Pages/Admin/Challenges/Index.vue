<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    challenges: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const searchForm = useForm({
    search: props.filters.search || '',
    filter_active: props.filters.filter_active || '',
});

const deleteForm = useForm({});

const searchInput = ref(props.filters.search || '');
const filterActive = ref(props.filters.filter_active || '');

let searchTimeout = null;

// Debounced search
const performSearch = () => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    searchTimeout = setTimeout(() => {
        searchForm.search = searchInput.value;
        searchForm.filter_active = filterActive.value;
        searchForm.get(route('admin.challenges.index'), {
            preserveState: true,
            preserveScroll: true,
        });
    }, 300);
};

watch([searchInput, filterActive], () => {
    performSearch();
});

const deleteChallenge = (challengeId) => {
    if (confirm('Are you sure you want to delete this challenge? This action cannot be undone.')) {
        deleteForm.delete(route('admin.challenges.destroy', challengeId), {
            preserveScroll: true,
        });
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Challenge Management" />

    <AdminLayout>
        <div>
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">Challenge Management</h1>
                        <p class="text-xl text-gray-400">
                            Manage all challenges in the system
                        </p>
                    </div>
                    <Link
                        :href="route('admin.challenges.create')"
                        class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors"
                    >
                        + Create Challenge
                    </Link>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-gray-950 rounded-xl border border-gray-900 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Search Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Search Challenges</label>
                        <input
                            v-model="searchInput"
                            type="text"
                            placeholder="Search by title or description..."
                            class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 focus:outline-none"
                        />
                    </div>

                    <!-- Filter by Active Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Filter by Status</label>
                        <select
                            v-model="filterActive"
                            class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-2.5 text-white focus:border-orange-500 focus:ring-orange-500 focus:outline-none"
                        >
                            <option value="">All Challenges</option>
                            <option value="active">Active Only</option>
                            <option value="inactive">Inactive Only</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Challenges Table -->
            <div class="bg-gray-950 rounded-xl border border-gray-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-900 border-b border-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Challenge</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Target</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Participants</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            <tr
                                v-for="challenge in challenges.data"
                                :key="challenge.id"
                                class="hover:bg-gray-900/50 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-white font-medium">{{ challenge.title }}</div>
                                        <div class="text-gray-400 text-sm line-clamp-1">{{ challenge.description }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-300 capitalize">{{ challenge.type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-300">{{ challenge.target_count }} {{ challenge.target_unit }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-300">{{ challenge.users_count || 0 }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        v-if="challenge.is_active"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30"
                                    >
                                        Active
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600"
                                    >
                                        Inactive
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-400 text-sm">{{ formatDate(challenge.created_at) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="route('admin.challenges.show', challenge.id)"
                                            class="text-orange-400 hover:text-orange-300 transition-colors"
                                        >
                                            View
                                        </Link>
                                        <Link
                                            :href="route('admin.challenges.edit', challenge.id)"
                                            class="text-blue-400 hover:text-blue-300 transition-colors"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            @click="deleteChallenge(challenge.id)"
                                            class="text-red-400 hover:text-red-300 transition-colors"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="challenges.data.length === 0">
                                <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                    No challenges found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="challenges.links && challenges.links.length > 3" class="px-6 py-4 border-t border-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-400">
                            Showing {{ challenges.from }} to {{ challenges.to }} of {{ challenges.total }} challenges
                        </div>
                        <div class="flex gap-2">
                            <Link
                                v-for="link in challenges.links"
                                :key="link.label"
                                :href="link.url || '#'"
                                :class="[
                                    'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                                    link.active
                                        ? 'bg-orange-500 text-white'
                                        : 'bg-gray-900 text-gray-300 hover:bg-gray-800',
                                    !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                ]"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

