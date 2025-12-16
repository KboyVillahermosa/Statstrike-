<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
});

const deleteForm = useForm({});

const deletePost = () => {
    if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        deleteForm.delete(route('admin.community.posts.destroy', props.post.id));
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
    <Head title="Post Details" />

    <AdminLayout>
        <div>
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <Link
                            :href="route('admin.community.posts.index')"
                            class="text-gray-400 hover:text-white transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </Link>
                        <div>
                            <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">Post Details</h1>
                            <p class="text-xl text-gray-400">
                                View and manage community post
                            </p>
                        </div>
                    </div>
                    <button
                        @click="deletePost"
                        :disabled="deleteForm.processing"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-colors disabled:opacity-50"
                    >
                        Delete Post
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Post Content -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-semibold">
                                {{ post.user?.name?.charAt(0).toUpperCase() || 'U' }}
                            </div>
                            <div>
                                <div class="text-white font-medium">{{ post.user?.name || 'Unknown' }}</div>
                                <div class="text-gray-400 text-sm">{{ post.user?.email || '' }}</div>
                            </div>
                            <div class="ml-auto text-gray-500 text-sm">{{ formatDate(post.created_at) }}</div>
                        </div>

                        <div class="text-gray-300 mb-4 whitespace-pre-wrap">{{ post.content }}</div>

                        <div v-if="post.image" class="mb-4 rounded-lg overflow-hidden">
                            <img :src="post.image" alt="Post image" class="w-full h-auto object-cover" />
                        </div>

                        <div class="flex items-center gap-4 text-sm text-gray-400 pt-4 border-t border-gray-800">
                            <span>❤️ {{ post.likes_count || 0 }} likes</span>
                            <span>💬 {{ post.comments_count || 0 }} comments</span>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-2xl font-bold text-white mb-6">Comments ({{ post.comments?.length || 0 }})</h2>
                        <div class="space-y-4">
                            <div
                                v-for="comment in post.comments"
                                :key="comment.id"
                                class="p-4 bg-gray-900 rounded-lg"
                            >
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-semibold text-sm">
                                        {{ comment.user?.name?.charAt(0).toUpperCase() || 'U' }}
                                    </div>
                                    <div>
                                        <div class="text-white font-medium text-sm">{{ comment.user?.name || 'Unknown' }}</div>
                                        <div class="text-gray-400 text-xs">{{ formatDate(comment.created_at) }}</div>
                                    </div>
                                </div>
                                <div class="text-gray-300 text-sm">{{ comment.content }}</div>
                            </div>
                            <div v-if="!post.comments || post.comments.length === 0" class="text-center text-gray-400 py-8">
                                No comments yet
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- User Info -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-xl font-bold text-white mb-4">Author</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Name</label>
                                <p class="text-white">{{ post.user?.name || 'Unknown' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                                <p class="text-white">{{ post.user?.email || 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">User ID</label>
                                <p class="text-white">{{ post.user?.id || 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Post Stats -->
                    <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-xl font-bold text-white mb-4">Statistics</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Likes</label>
                                <p class="text-white">{{ post.likes_count || 0 }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Comments</label>
                                <p class="text-white">{{ post.comments_count || 0 }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Created</label>
                                <p class="text-white">{{ formatDate(post.created_at) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Likes -->
                    <div v-if="post.likes && post.likes.length > 0" class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                        <h2 class="text-xl font-bold text-white mb-4">Likes ({{ post.likes.length }})</h2>
                        <div class="space-y-2">
                            <div
                                v-for="like in post.likes"
                                :key="like.id"
                                class="text-sm text-gray-300"
                            >
                                {{ like.user?.name || 'Unknown' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

