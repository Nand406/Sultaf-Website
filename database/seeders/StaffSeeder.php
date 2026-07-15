<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Akun staff TIDAK dibuat lewat form register (yang hanya untuk role "pelanggan").
     * Staff dibuat langsung ke database lewat seeder ini, atau bisa juga
     * di-input manual lewat SQLyog dengan query yang sama strukturnya.
     */
    public function run(): void
    {
        $staff = [
            [
                'name'     => 'Kasir Sultaf',
                'email'    => 'kasir@sultaf.com',
                'password' => 'kasir123',
                'role'     => 'kasir',
            ],
            [
                'name'     => 'Chef Ibrahim',
                'email'    => 'dapur@sultaf.com',
                'password' => 'dapur123',
                'role'     => 'dapur',
            ],
            [
                'name'     => 'Admin Sultaf',
                'email'    => 'admin@sultaf.com',
                'password' => 'admin123',
                'role'     => 'admin',
            ],
            [
                'name'     => 'Owner Sultaf',
                'email'    => 'owner@sultaf.com',
                'password' => 'owner123',
                'role'     => 'owner',
            ],
        ];

        foreach ($staff as $s) {
            User::updateOrCreate(
                ['email' => $s['email']],
                [
                    'name'     => $s['name'],
                    'password' => Hash::make($s['password']),
                    'role'     => $s['role'],
                ]
            );
        }
    }
}
