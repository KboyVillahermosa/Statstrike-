<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import WeeklyActivityChart from '@/Components/WeeklyActivityChart.vue';
import ActiveChallengesList from '@/Components/ActiveChallengesList.vue';
import RecentWorkoutsTable from '@/Components/RecentWorkoutsTable.vue';

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            totalWorkouts: { metric: '0', change: 'No workouts yet', changePositive: false },
            timeTrained: { metric: '0h 0m', change: 'No training yet', changePositive: false },
            followers: { metric: '0', change: '+0 this month', changePositive: true },
            leaderboardRank: { metric: '#0', change: 'Top 0%', changePositive: true },
        }),
    },
    weeklyActivity: {
        type: Array,
        default: () => [],
    },
    activeChallenges: {
        type: Array,
        default: () => [],
    },
    recentWorkouts: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <DashboardLayout>
        <!-- Header Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <StatCard
                title="Total Workouts"
                :metric="stats.totalWorkouts.metric"
                :change="stats.totalWorkouts.change"
                :change-positive="stats.totalWorkouts.changePositive"
                icon="📊"
            />
            <StatCard
                title="Time Trained"
                :metric="stats.timeTrained.metric"
                :change="stats.timeTrained.change"
                :change-positive="stats.timeTrained.changePositive"
                icon="⏱️"
            />
            <StatCard
                title="Followers"
                :metric="stats.followers.metric"
                :change="stats.followers.change"
                :change-positive="stats.followers.changePositive"
                icon="👥"
            />
            <StatCard
                title="Leaderboard Rank"
                :metric="stats.leaderboardRank.metric"
                :change="stats.leaderboardRank.change"
                :change-positive="stats.leaderboardRank.changePositive"
                icon="📈"
            />
        </div>

        <!-- Mid Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
            <!-- Weekly Activity Chart (2/3 width) -->
            <div class="col-span-1 lg:col-span-2">
                <WeeklyActivityChart
                    title="Weekly Activity"
                    subtitle="Your workout frequency for the last 7 days."
                    :bars="weeklyActivity"
                />
            </div>

            <!-- Active Challenges List (1/3 width) -->
            <div class="col-span-1">
                <ActiveChallengesList
                    title="Active Challenges"
                    subtitle="Your current progress."
                    :challenges="activeChallenges"
                />
            </div>
        </div>

        <!-- Recent Workouts Table -->
        <RecentWorkoutsTable
            title="Recent Workouts"
            subtitle="A log of your most recent training sessions."
            :workouts="recentWorkouts"
        />
    </DashboardLayout>
</template>
