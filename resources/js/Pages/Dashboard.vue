<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import WeeklyActivityChart from '@/Components/WeeklyActivityChart.vue';
import ActiveChallengesList from '@/Components/ActiveChallengesList.vue';
import RecentWorkoutsTable from '@/Components/RecentWorkoutsTable.vue';
import { ref } from 'vue';

// Mock data - In production, this would come from Inertia props
const weeklyActivityData = ref([
  { label: 'Mon', height: 45 },
  { label: 'Tue', height: 65 },
  { label: 'Wed', height: 40 },
  { label: 'Thu', height: 75 },
  { label: 'Fri', height: 60 },
  { label: 'Sat', height: 85 },
  { label: 'Sun', height: 35 },
]);

const activeChallenges = ref([
  { id: 1, title: '500 Kicks Challenge', current: 325, total: 500 },
  { id: 2, title: '20 Rounds of Sparring', current: 19, total: 20 },
]);

const recentWorkouts = ref([
  { id: 1, type: 'Boxing', duration: '45 min', rpe: '8/10', date: '2 days ago' },
  { id: 2, type: 'Kicking Drills', duration: '60 min', rpe: '7/10', date: '3 days ago' },
  { id: 3, type: 'Sparring', duration: '30 min', rpe: '9/10', date: '5 days ago' },
]);
</script>

<template>
    <Head title="Dashboard" />

    <DashboardLayout>
        <!-- Header Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <StatCard
                title="Total Workouts"
                metric="128"
                change="+16 since last month"
                :change-positive="true"
                icon="📊"
            />
            <StatCard
                title="Time Trained"
                metric="96h 30m"
                change="+8h this month"
                :change-positive="true"
                icon="⏱️"
            />
            <StatCard
                title="Followers"
                metric="482"
                change="+21 this month"
                :change-positive="true"
                icon="👥"
            />
            <StatCard
                title="Leaderboard Rank"
                metric="#12"
                change="Top 5%"
                :change-positive="true"
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
                    :bars="weeklyActivityData"
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
