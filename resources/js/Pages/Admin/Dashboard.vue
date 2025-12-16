<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import WeeklyActivityChart from '@/Components/WeeklyActivityChart.vue';
import AddUserModal from '@/Components/Admin/AddUserModal.vue';
import { ref } from 'vue';

const props = defineProps({
    stats: {
        type: Object,
        required: true,
    },
    topUsers: {
        type: Array,
        default: () => [],
    },
    recentChallenges: {
        type: Array,
        default: () => [],
    },
    recentPosts: {
        type: Array,
        default: () => [],
    },
    recentUsers: {
        type: Array,
        default: () => [],
    },
    charts: {
        type: Object,
        default: () => ({
            userGrowth: [],
            activityTrends: [],
            monthlyRegistrations: [],
        }),
    },
});

const showAddUserModal = ref(false);

// Format activity trends for WeeklyActivityChart
const activityBars = props.charts.activityTrends.map((day, index) => {
    const maxActivity = Math.max(
        ...props.charts.activityTrends.map(d => d.workouts + d.posts + d.challenges)
    );
    const totalActivity = day.workouts + day.posts + day.challenges;
    return {
        label: day.date,
        height: maxActivity > 0 ? Math.round((totalActivity / maxActivity) * 100) : 0,
        workouts: day.workouts,
        posts: day.posts,
        challenges: day.challenges,
    };
});

// Format user growth for chart
const maxUserTotal = props.charts.userGrowth.length
    ? Math.max(...props.charts.userGrowth.map(d => d.total))
    : 0;

// Show labels only on some ticks so numbers don't visually run together
const userGrowthBars = props.charts.userGrowth.map((day, index, arr) => {
    const height = maxUserTotal > 0 ? Math.round((day.total / maxUserTotal) * 100) : 0;
    const showLabel = index === 0 || index === arr.length - 1 || index % 3 === 0;

    return {
        label: day.date,
        displayLabel: showLabel ? day.date : '',
        height,
        count: day.count,
        total: day.total,
    };
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <div>
            <!-- Header with Quick Actions -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">Admin Dashboard</h1>
                    <p class="text-xl text-gray-400">
                        System overview and management
                    </p>
                </div>
                <div class="flex gap-3">
                    <button
                        @click="showAddUserModal = true"
                        class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors"
                    >
                        + Add User
                    </button>
                    <Link
                        :href="route('admin.users.index')"
                        class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors"
                    >
                        Manage Users
                    </Link>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                <StatCard
                    title="Total Users"
                    :metric="stats.users.total"
                    :change="`+${stats.users.newThisWeek} this week`"
                    :change-positive="stats.users.newThisWeek > 0"
                    icon="👥"
                />
                <StatCard
                    title="Active Challenges"
                    :metric="stats.challenges.active"
                    :change="`${stats.challenges.total} total`"
                    :change-positive="true"
                    icon="🏆"
                />
                <StatCard
                    title="Community Posts"
                    :metric="stats.community.totalPosts"
                    :change="`+${stats.community.newThisWeek} this week`"
                    :change-positive="stats.community.newThisWeek > 0"
                    icon="💬"
                />
                <StatCard
                    title="Total Workouts"
                    :metric="stats.workouts.totalWorkouts"
                    :change="`+${stats.workouts.newThisWeek} this week`"
                    :change-positive="stats.workouts.newThisWeek > 0"
                    icon="💪"
                />
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- User Growth Chart -->
                <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                    <h3 class="text-xl font-bold text-white mb-2">User Growth (30 Days)</h3>
                    <p class="text-gray-400 text-sm mb-6">Total users over time</p>
                    <!-- Scrollable container so 30 days don't overflow the card -->
                    <div class="overflow-x-auto">
                        <div class="flex items-end gap-1 md:gap-2 h-48 min-w-[640px] pr-2">
                            <div
                                v-for="(bar, index) in userGrowthBars"
                                :key="index"
                                class="flex flex-col items-center gap-2 flex-none w-6 sm:w-7"
                                :title="`${bar.total} users on ${bar.label}`"
                            >
                                <div
                                    class="w-full bg-gradient-to-t from-orange-500 to-orange-600 rounded-t transition-all duration-300 hover:opacity-80 cursor-pointer"
                                    :style="{ height: bar.height + '%' }"
                                ></div>
                                <span class="text-xs text-gray-500 mt-2 whitespace-nowrap">{{ bar.displayLabel }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Trends Chart -->
                <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                    <h3 class="text-xl font-bold text-white mb-2">Activity Trends (7 Days)</h3>
                    <p class="text-gray-400 text-sm mb-6">Daily activity breakdown</p>
                    <WeeklyActivityChart
                        title=""
                        subtitle=""
                        :bars="activityBars"
                    />
                </div>
            </div>

            <!-- Data Tables Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Recent Users -->
                <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-white">Recent Users</h3>
                        <Link
                            :href="route('admin.users.index')"
                            class="text-sm text-orange-400 hover:text-orange-300"
                        >
                            View All →
                        </Link>
                    </div>
                    <div class="space-y-3">
                        <div
                            v-for="user in recentUsers"
                            :key="user.id"
                            class="flex items-center justify-between p-3 bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors"
                        >
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-semibold">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                                <div>
                                    <div class="text-white font-medium">{{ user.name }}</div>
                                    <div class="text-gray-400 text-sm">{{ user.email }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-white font-semibold">{{ user.points || 0 }} pts</div>
                                <div class="text-gray-400 text-xs">{{ formatDate(user.created_at) }}</div>
                            </div>
                        </div>
                        <div v-if="recentUsers.length === 0" class="text-center text-gray-400 py-8">
                            No users yet
                        </div>
                    </div>
                </div>

                <!-- Top Users -->
                <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Top Users by Points</h3>
                    <div class="space-y-3">
                        <div
                            v-for="(user, index) in topUsers"
                            :key="user.id"
                            class="flex items-center justify-between p-3 bg-gray-900 rounded-lg"
                        >
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ index + 1 }}
                                </div>
                                <div>
                                    <div class="text-white font-medium">{{ user.name }}</div>
                                    <div class="text-gray-400 text-sm">{{ user.email }}</div>
                                </div>
                            </div>
                            <div class="text-orange-400 font-bold">{{ user.points || 0 }} pts</div>
                        </div>
                        <div v-if="topUsers.length === 0" class="text-center text-gray-400 py-8">
                            No users yet
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Challenges and Posts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Challenges -->
                <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-white">Recent Challenges</h3>
                        <Link
                            :href="route('admin.challenges.index')"
                            class="text-sm text-orange-400 hover:text-orange-300"
                        >
                            View All →
                        </Link>
                    </div>
                    <div class="space-y-3">
                        <div
                            v-for="challenge in recentChallenges"
                            :key="challenge.id"
                            class="p-3 bg-gray-900 rounded-lg"
                        >
                            <div class="text-white font-medium mb-1">{{ challenge.title }}</div>
                            <div class="text-gray-400 text-sm">{{ challenge.users_count || 0 }} participants</div>
                        </div>
                        <div v-if="recentChallenges.length === 0" class="text-center text-gray-400 py-8">
                            No challenges yet
                        </div>
                    </div>
                </div>

                <!-- Recent Posts -->
                <div class="bg-gray-950 rounded-xl border border-gray-900 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-white">Recent Posts</h3>
                        <Link
                            :href="route('admin.community.posts.index')"
                            class="text-sm text-orange-400 hover:text-orange-300"
                        >
                            View All →
                        </Link>
                    </div>
                    <div class="space-y-3">
                        <div
                            v-for="post in recentPosts"
                            :key="post.id"
                            class="p-3 bg-gray-900 rounded-lg"
                        >
                            <div class="text-white font-medium mb-1">{{ post.user?.name || 'Unknown' }}</div>
                            <div class="text-gray-400 text-sm line-clamp-2">{{ post.content }}</div>
                        </div>
                        <div v-if="recentPosts.length === 0" class="text-center text-gray-400 py-8">
                            No posts yet
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add User Modal -->
        <div
            v-if="showAddUserModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="showAddUserModal = false"
        >
            <AddUserModal @close="showAddUserModal = false" />
        </div>
    </AdminLayout>
</template>

