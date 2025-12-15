<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create multiple admin users
        $admins = [
            [
                'name' => 'Admin User',
                'email' => 'admin@statstrike.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'fitness_goal' => 'General Fitness',
                'experience_level' => 'expert',
                'subscription_tier' => 'pro',
                'points' => 1000,
            ],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@statstrike.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'fitness_goal' => 'Performance',
                'experience_level' => 'expert',
                'subscription_tier' => 'pro',
                'points' => 2000,
            ],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                $admin
            );
        }

        $this->command->info('Admin users seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Email: admin@statstrike.com | Password: password');
        $this->command->info('Email: superadmin@statstrike.com | Password: password');
    }
}
