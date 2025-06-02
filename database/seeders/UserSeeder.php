<?php

namespace Database\Seeders;

use App\Models\Barista;
use App\Models\Cashier;
use App\Models\Manager;
use App\Models\Owner;
use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner
        $ownerUser = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            ['password' => bcrypt('password'), 'role' => 'owner']
        );
    
        Owner::firstOrCreate(
            ['user_id' => $ownerUser->id],
            [
                'name' => 'Han Owner',
                'phone_number' => '1234567890',
                'address' => '123 Manager St',
                'monthly_wage' => 50000,
            ]
        );
    
        // Manager
        $managerUser = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            ['password' => bcrypt('password'), 'role' => 'manager']
        );
    
        Manager::firstOrCreate(
            ['user_id' => $managerUser->id],
            [
                'name' => 'Bryan Manager',
                'phone_number' => '1234567890',
                'address' => '123 Manager St',
                'monthly_wage' => 5000000,
            ]
        );
    
        // Cashier
        $cashierUser = User::firstOrCreate(
            ['email' => 'cashier@example.com'],
            ['password' => bcrypt('password'), 'role' => 'cashier']
        );
    
        Cashier::firstOrCreate(
            ['user_id' => $cashierUser->id],
            [
                'name' => 'Erlangga Cashier',
                'phone_number' => '0987654321',
                'address' => '456 Cashier Ave',
                'monthly_wage' => 3000000,
            ]
        );
    }
    
}
