<template>
  <Head title="Community Hub" />

  <DashboardLayout>
    <div>
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">Community Hub</h1>
            <p class="text-xl text-gray-400">
              Connect, compete, and grow with fellow martial artists.
            </p>
          </div>
        </div>
      </div>


      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Section - Social Feed (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Challenges Section -->
          <div v-if="challenges.length > 0" class="space-y-4">
            <h2 class="text-2xl font-bold text-white mb-4">Active Challenges</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div
                v-for="challenge in challenges"
                :key="challenge.id"
                class="bg-gray-950 rounded-xl border border-gray-900 overflow-hidden hover:border-orange-500/50 transition-all duration-300"
              >
                <!-- Challenge Content -->
                <div class="p-4">
                  <h3 class="text-lg font-bold text-white mb-2">{{ challenge.title }}</h3>
                  <p class="text-gray-400 text-sm mb-3 leading-relaxed">
                    {{ challenge.description }}
                  </p>

                  <!-- Participants -->
                  <div class="flex items-center gap-2 text-gray-400 text-xs mb-3">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>{{ formatParticipants(challenge.participants_count) }} participants</span>
                  </div>

                  <!-- Progress Bar (only show if joined) -->
                  <div v-if="challenge.joined" class="mb-3">
                    <div class="flex items-center justify-between mb-1">
                      <span class="text-xs text-gray-400">Progress</span>
                      <span class="text-xs font-semibold text-orange-400">{{ Math.round(challenge.progress_percentage) }}%</span>
                    </div>
                    <div class="w-full bg-gray-900 rounded-full h-1.5">
                      <div 
                        class="bg-gradient-to-r from-orange-500 to-orange-600 h-1.5 rounded-full transition-all duration-300" 
                        :style="{ width: `${Math.min(100, challenge.progress_percentage)}%` }"
                      ></div>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="space-y-2">
                    <Link
                      v-if="challenge.joined"
                      :href="route('challenges.session.start', challenge.id)"
                      class="block w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-2 px-3 rounded-lg transition-all duration-200 text-center text-sm"
                    >
                      Start Workout Session
                    </Link>
                    <form
                      v-else
                      @submit.prevent="joinChallenge(challenge.id)"
                      class="w-full"
                    >
                      <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-2 px-3 rounded-lg transition-all duration-200 text-sm"
                      >
                        Join Challenge
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Create Post Form -->
          <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
            <h2 class="text-xl font-bold text-white mb-4">Create a Post</h2>
            <form @submit.prevent="submitPost">
              <div class="space-y-4">
                <textarea
                  v-model="postForm.content"
                  rows="4"
                  class="w-full bg-gray-900 border border-gray-800 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 focus:outline-none resize-none"
                  placeholder="Share your workout, achievement, or motivation with the community..."
                ></textarea>
                
                <!-- Image Preview -->
                <div v-if="postImagePreview" class="relative">
                  <img :src="postImagePreview" alt="Preview" class="w-full h-64 object-cover rounded-lg" />
                  <button
                    type="button"
                    @click="clearPostImage"
                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 transition-colors"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>

                <div class="flex items-center justify-between">
                  <label class="flex items-center gap-2 text-gray-400 hover:text-orange-400 cursor-pointer transition-colors">
                    <input
                      type="file"
                      ref="postImageInput"
                      @change="handlePostImageSelect"
                      accept="image/*"
                      class="hidden"
                    />
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm">Add Photo</span>
                  </label>
                  <button
                    type="submit"
                    :disabled="postForm.processing || !postForm.content.trim()"
                    class="px-6 py-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    Post
                  </button>
                </div>
              </div>
            </form>
          </div>

          <!-- Posts Feed -->
          <div v-if="formattedPosts.length > 0" class="space-y-6">
            <div
              v-for="post in formattedPosts"
              :key="post.id"
              class="bg-gray-950 rounded-xl border border-gray-900 p-6"
            >
              <!-- User Header -->
              <div class="flex items-center gap-3 mb-4">
                <div
                  v-if="post.user.profile_photo"
                  class="w-12 h-12 rounded-full overflow-hidden bg-gradient-to-br from-orange-500 to-orange-600 flex-shrink-0"
                >
                  <img :src="post.user.profile_photo" :alt="post.user.name" class="w-full h-full object-cover" />
                </div>
                <div
                  v-else
                  class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-semibold flex-shrink-0"
                >
                  {{ post.user.initials }}
                </div>
                <div class="flex-1">
                  <h3 class="text-white font-semibold">{{ post.user.name }}</h3>
                  <p class="text-gray-400 text-sm">{{ post.created_at }}</p>
                </div>
              </div>

              <!-- Post Content -->
              <p class="text-gray-300 mb-4 whitespace-pre-wrap">{{ post.content }}</p>

              <!-- Post Image -->
              <div v-if="post.image_url" class="rounded-lg overflow-hidden mb-4">
                <img :src="post.image_url" :alt="post.user.name" class="w-full h-auto object-cover" />
              </div>

              <!-- Engagement Metrics -->
              <div class="flex items-center gap-6 pt-4 border-t border-gray-900">
                <button
                  @click="toggleLike(post)"
                  :class="[
                    'flex items-center gap-2 transition-colors',
                    post.is_liked ? 'text-red-400' : 'text-gray-400 hover:text-red-400'
                  ]"
                >
                  <svg
                    v-if="post.is_liked"
                    class="w-5 h-5 fill-current"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                  <svg
                    v-else
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                  <span class="text-sm">{{ post.likes_count }}</span>
                </button>
                <button
                  @click="toggleComments(post)"
                  class="flex items-center gap-2 text-gray-400 hover:text-orange-400 transition-colors"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                  <span class="text-sm">{{ post.comments_count }}</span>
                </button>
                <button
                  @click="sharePost(post)"
                  class="flex items-center gap-2 text-gray-400 hover:text-orange-400 transition-colors"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                  </svg>
                  <span class="text-sm">Share</span>
                </button>
              </div>

              <!-- Comments Section -->
              <div v-if="expandedComments[post.id]" class="mt-4 pt-4 border-t border-gray-900">
                <!-- Comments List -->
                <div v-if="post.comments && post.comments.length > 0" class="space-y-4 mb-4">
                  <div
                    v-for="comment in post.comments"
                    :key="comment.id"
                    class="flex gap-3"
                  >
                    <div
                      v-if="comment.user.profile_photo"
                      class="w-8 h-8 rounded-full overflow-hidden bg-gradient-to-br from-orange-500 to-orange-600 flex-shrink-0"
                    >
                      <img :src="comment.user.profile_photo" :alt="comment.user.name" class="w-full h-full object-cover" />
                    </div>
                    <div
                      v-else
                      class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0"
                    >
                      {{ comment.user.initials }}
                    </div>
                    <div class="flex-1">
                      <div class="bg-gray-900 rounded-lg p-3">
                        <p class="text-white font-semibold text-sm mb-1">{{ comment.user.name }}</p>
                        <p class="text-gray-300 text-sm">{{ comment.content }}</p>
                      </div>
                      <p class="text-gray-500 text-xs mt-1">{{ comment.created_at }}</p>
                    </div>
                  </div>
                </div>

                <!-- Add Comment Form -->
                <form @submit.prevent="submitComment(post)" class="flex gap-2">
                  <input
                    v-model="commentForms[post.id]"
                    type="text"
                    class="flex-1 bg-gray-900 border border-gray-800 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:border-orange-500 focus:ring-orange-500 focus:outline-none text-sm"
                    placeholder="Write a comment..."
                  />
                  <button
                    type="submit"
                    :disabled="!commentForms[post.id] || commentForms[post.id].trim() === ''"
                    class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    Post
                  </button>
                </form>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="bg-gray-950 rounded-xl border border-gray-900 p-12 text-center">
            <svg class="w-16 h-16 text-gray-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <p class="text-gray-400 text-lg">No posts yet. Be the first to share!</p>
          </div>
        </div>

        <!-- Right Section - Leaderboard (1/3 width) -->
        <div class="lg:col-span-1">
          <div class="bg-gray-950 rounded-xl border border-gray-900 p-6 sticky top-6">
            <h2 class="text-2xl font-bold text-white mb-6">Leaderboard</h2>

            <!-- Leaderboard Table -->
            <div class="space-y-4">
              <div
                v-for="user in leaderboard"
                :key="user.id"
                :class="[
                  'flex items-center gap-4 p-3 rounded-lg transition-colors',
                  user.rank === 1
                    ? 'bg-gray-900/50 border border-orange-500/20'
                    : 'bg-gray-900/30 hover:bg-gray-900/50'
                ]"
              >
                <div
                  :class="[
                    'flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm',
                    user.rank === 1
                      ? 'bg-gradient-to-br from-yellow-500 to-yellow-600'
                      : user.rank === 2
                      ? 'bg-gradient-to-br from-gray-500 to-gray-600'
                      : user.rank === 3
                      ? 'bg-gradient-to-br from-orange-700 to-orange-800'
                      : 'bg-gray-800 text-gray-400'
                  ]"
                >
                  {{ user.rank }}
                </div>
                <div
                  v-if="user.profile_photo"
                  class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-gray-600 to-gray-700 flex-shrink-0"
                >
                  <img :src="user.profile_photo" :alt="user.name" class="w-full h-full object-cover" />
                </div>
                <div
                  v-else
                  class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0"
                >
                  {{ user.initials }}
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-white font-medium truncate">{{ user.name }}</p>
                  <div class="flex items-center gap-2 mt-1">
                    <span class="px-2 py-0.5 bg-gray-800 text-gray-300 text-xs rounded-full capitalize">
                      {{ user.experience_level }}
                    </span>
                    <span class="text-orange-400 font-semibold text-sm">{{ user.points.toLocaleString() }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="leaderboard.length === 0" class="text-center py-8">
              <p class="text-gray-400 text-sm">No leaderboard data yet.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { ref, computed } from 'vue';


const props = defineProps({
  posts: {
    type: Array,
    default: () => [],
  },
  leaderboard: {
    type: Array,
    default: () => [],
  },
  challenges: {
    type: Array,
    default: () => [],
  },
});

const postForm = useForm({
  content: '',
  image: null,
});

const postImageInput = ref(null);
const postImagePreview = ref(null);
const expandedComments = ref({});
const commentForms = ref({});

const handlePostImageSelect = (event) => {
  const file = event.target.files[0];
  if (file) {
    postForm.image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      postImagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const clearPostImage = () => {
  postForm.image = null;
  postImagePreview.value = null;
  if (postImageInput.value) {
    postImageInput.value.value = '';
  }
};

const submitPost = () => {
  // Use FormData to properly send file data
  const formData = new FormData();
  formData.append('content', postForm.content);
  if (postForm.image) {
    formData.append('image', postForm.image);
  }

  // Use router.post with FormData
  router.post(route('community.posts.store'), formData, {
    preserveScroll: true,
    onSuccess: () => {
      // Only reset after successful submission
      postForm.content = '';
      postForm.image = null;
      clearPostImage();
      if (window.toast) {
        window.toast.success('Post created successfully!');
      }
      // Force reload to get fresh data including the new post
      router.reload();
    },
    onError: () => {
      if (window.toast) {
        window.toast.error('Failed to create post. Please try again.');
      }
    },
  });
};

// Add a computed property to ensure image URLs are properly formatted
const formattedPosts = computed(() => {
  return props.posts.map(post => ({
    ...post,
    // Ensure image URL is properly formatted
    image_url: post.image
  }));
});

const toggleLike = (post) => {
  router.post(route('community.posts.like', post.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      // Update local state
      post.is_liked = !post.is_liked;
      post.likes_count += post.is_liked ? 1 : -1;
    },
  });
};

const toggleComments = (post) => {
  expandedComments.value[post.id] = !expandedComments.value[post.id];
  if (!commentForms.value[post.id]) {
    commentForms.value[post.id] = '';
  }
};

const submitComment = (post) => {
  const commentContent = commentForms.value[post.id];
  if (!commentContent || commentContent.trim() === '') {
    return;
  }

  router.post(route('community.posts.comments.store', post.id), {
    content: commentContent,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      commentForms.value[post.id] = '';
      if (window.toast) {
        window.toast.success('Comment added successfully!');
      }
    },
    onError: () => {
      if (window.toast) {
        window.toast.error('Failed to add comment. Please try again.');
      }
    },
  });
};


const sharePost = async (post) => {
  const url = `${window.location.origin}${route('community')}#post-${post.id}`;
  
  try {
    await navigator.clipboard.writeText(url);
    if (window.toast) {
      window.toast.success('Post link copied to clipboard!');
    }
  } catch (err) {
    // Fallback for browsers that don't support clipboard API
    const textArea = document.createElement('textarea');
    textArea.value = url;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    if (window.toast) {
      window.toast.success('Post link copied to clipboard!');
    }
  }
};

const formatParticipants = (count) => {
  if (count >= 1000) {
    return (count / 1000).toFixed(1) + 'k';
  }
  return count.toString();
};

const joinChallenge = (challengeId) => {
  router.post(route('challenges.join', challengeId), {}, {
    preserveScroll: true,
  });
};
</script>
