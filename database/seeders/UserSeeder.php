<?php

namespace Database\Seeders;

use App\Enum\Gender;
use App\Enum\UserRole;
use App\Models\Intake;
use App\Models\Nutrition;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->state([
                'name' => 'Norhalisah',
                'email' => 'norhalisah673@gmail.com',
                'password' => bcrypt('Halisah.Zaka.2330'),
                'role' => UserRole::Admin,
                'gender' => Gender::Female,
                'date_of_birth' => Carbon::createFromDate(2003, 11, 30),
            ])
            ->create();

        User::factory()
            ->state([
                'name' => 'Qodli Zaka',
                'email' => 'qodlizaka513@gmail.com',
                'password' => bcrypt('Halisah.Zaka.2330'),
                'role' => UserRole::Admin,
                'gender' => Gender::Male,
                'date_of_birth' => Carbon::createFromDate(2004, 3, 23),
            ])
            ->create();

        User::factory()
            ->state([
                'name' => 'Test User',
                'email' => 'text@example.com',
                'password' => bcrypt('password'),
                'role' => UserRole::User,
                'gender' => Gender::Male,
                'date_of_birth' => Carbon::createFromDate(rand(1990, 2010), rand(1, 12), rand(1, 20)),
            ])
            ->has(UserDetail::factory(360))
            ->create();

        User::factory()
            ->state(['role' => UserRole::User])
            ->has(UserDetail::factory()->count(10))
            ->count(10)
            ->create();
    }
}
