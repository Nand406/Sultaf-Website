<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            [
                'username' => 'kasir@sultaf.com', // Diubah dari 'email' ke 'username'
                'password' => 'kasir123',
                'role'     => 'kasir',
                'no_hp'    => '0812', // Tambahkan no_hp
            ],
            [
                'username' => 'dapur@sultaf.com',
                'password' => 'dapur123',
                'role'     => 'dapur',
                'no_hp'    => '0819',
            ],
            [
                'username' => 'admin@sultaf.com',
                'password' => 'admin123',
                'role'     => 'admin',
                'no_hp'    => '0811',
            ],
            [
                'username' => 'owner@sultaf.com',
                'password' => 'owner123',
                'role'     => 'owner',
                'no_hp'    => '0818',
            ],
        ];

        foreach ($staff as $s) {
            User::updateOrCreate(
                ['username' => $s['username']], // Kunci pencarian diubah ke 'username'
                [
                    'password' => Hash::make($s['password']),
                    'role'     => $s['role'],
                    'no_hp'    => $s['no_hp'],
                ]
            );
        }
    }
}
