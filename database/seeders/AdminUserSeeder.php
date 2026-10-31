<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('MARKETPLACE_ADMIN_EMAIL');
        $password = env('MARKETPLACE_ADMIN_PASSWORD');

        if (!$email || !$password) {
            throw new RuntimeException(
                'Marketplace admin email and password must be configured.'
            );
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Marketplace Admin',
                'password' => $password,
                'role' => 'admin',
                'status' => 'active',
            ]
        );
    }
}