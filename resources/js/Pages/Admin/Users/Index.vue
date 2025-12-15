<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    users: {
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
    filter_admin: props.filters.filter_admin || '',
});

const deleteForm = useForm({});

const searchInput = ref(props.filters.search || '');
const filterAdmin = ref(props.filters.filter_admin || '');

let searchTimeout = null;

// Debounced search
const performSearch = () => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    searchTimeout = setTimeout(() => {
        searchForm.search = searchInput.value;
        searchForm.filter_admin = filterAdmin.value;
        searchForm.get(route('admin.users.index'), {
            preserveState: true,
            preserveScroll: true,
        });
    }, 300);
};

watch([searchInput, filterAdmin], () => {
    performSearch();
});

const deleteUser = (userId) => {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        deleteForm.delete(route('admin.users.destroy', userId), {
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
    <Head title="User Management" />

    <AdminLayout>
        <div>
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">User Management</h1>
                        <p class="text-xl text-gray-400">
                            Manage all users in the system
                        </p>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-gray-950 rounded-xl border border-gray-900 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Search Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Search Users</label>
                        <input
                            v-model="searchInput"
                            type="text"
                            placeholder="Search by name or email..."
                            class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 focus:outline-none"
                        />
                    </div>

                    <!-- Filter by Admin Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Filter by Role</label>
                        <select
                            v-model="filterAdmin"
                            class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-2.5 text-white focus:border-orange-500 focus:ring-orange-500 focus:outline-none"
                        >
                            <option value="">All Users</option>
                            <option value="admin">Admins Only</option>
                            <option value="regular">Regular Users</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-gray-950 rounded-xl border border-gray-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-900 border-b border-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Points</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Joined</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            <tr
                                v-for="user in users.data"
                                :key="user.id"
                                class="hover:bg-gray-900/50 transition-colors"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-semibold">
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <div class="text-white font-medium">{{ user.name }}</div>
                                            <div class="text-gray-400 text-sm">{{ user.experience_level || 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-300">{{ user.email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        v-if="user.is_admin"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-500/20 text-orange-400 border border-orange-500/30"
                                    >
                                        Admin
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600"
                                    >
                                        User
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-300">{{ user.points || 0 }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-400 text-sm">{{ formatDate(user.created_at) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="route('admin.users.show', user.id)"
                                            class="text-orange-400 hover:text-orange-300 transition-colors"
                                        >
                                            View
                                        </Link>
                                        <button
                                            @click="deleteUser(user.id)"
                                            class="text-red-400 hover:text-red-300 transition-colors"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="users.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                    No users found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="users.links && users.links.length > 3" class="px-6 py-4 border-t border-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-400">
                            Showing {{ users.from }} to {{ users.to }} of {{ users.total }} users
                        </div>
                        <div class="flex gap-2">
                            <Link
                                v-for="link in users.links"
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

