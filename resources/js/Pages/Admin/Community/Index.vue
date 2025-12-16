<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    posts: {
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
});

const deleteForm = useForm({});

const searchInput = ref(props.filters.search || '');

let searchTimeout = null;

// Debounced search
const performSearch = () => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    searchTimeout = setTimeout(() => {
        searchForm.search = searchInput.value;
        searchForm.get(route('admin.community.posts.index'), {
            preserveState: true,
            preserveScroll: true,
        });
    }, 300);
};

watch([searchInput], () => {
    performSearch();
});

const deletePost = (postId) => {
    if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        deleteForm.delete(route('admin.community.posts.destroy', postId), {
            preserveScroll: true,
        });
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Community Posts Management" />

    <AdminLayout>
        <div>
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">Community Posts</h1>
                        <p class="text-xl text-gray-400">
                            Moderate and manage community posts
                        </p>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="bg-gray-950 rounded-xl border border-gray-900 p-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Search Posts</label>
                    <input
                        v-model="searchInput"
                        type="text"
                        placeholder="Search by content or user name/email..."
                        class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 focus:outline-none"
                    />
                </div>
            </div>

            <!-- Posts List -->
            <div class="space-y-4">
                <div
                    v-for="post in posts.data"
                    :key="post.id"
                    class="bg-gray-950 rounded-xl border border-gray-900 p-6"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <!-- User Info -->
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-semibold">
                                    {{ post.user?.name?.charAt(0).toUpperCase() || 'U' }}
                                </div>
                                <div>
                                    <div class="text-white font-medium">{{ post.user?.name || 'Unknown' }}</div>
                                    <div class="text-gray-400 text-sm">{{ post.user?.email || '' }}</div>
                                </div>
                                <div class="text-gray-500 text-sm">{{ formatDate(post.created_at) }}</div>
                            </div>

                            <!-- Post Content -->
                            <div class="text-gray-300 mb-3">{{ post.content }}</div>

                            <!-- Post Image -->
                            <div v-if="post.image" class="mb-3 rounded-lg overflow-hidden">
                                <img :src="post.image" alt="Post image" class="w-full max-w-md h-auto object-cover" />
                            </div>

                            <!-- Stats -->
                            <div class="flex items-center gap-4 text-sm text-gray-400">
                                <span>❤️ {{ post.likes_count || 0 }} likes</span>
                                <span>💬 {{ post.comments_count || 0 }} comments</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-2">
                            <Link
                                :href="route('admin.community.posts.show', post.id)"
                                class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors"
                            >
                                View
                            </Link>
                            <button
                                @click="deletePost(post.id)"
                                class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 text-sm font-medium rounded-lg transition-colors border border-red-500/30"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="posts.data.length === 0" class="bg-gray-950 rounded-xl border border-gray-900 p-8 text-center text-gray-400">
                    No posts found
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="posts.links && posts.links.length > 3" class="mt-6 bg-gray-950 rounded-xl border border-gray-900 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-400">
                        Showing {{ posts.from }} to {{ posts.to }} of {{ posts.total }} posts
                    </div>
                    <div class="flex gap-2">
                        <Link
                            v-for="link in posts.links"
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
    </AdminLayout>
</template>

