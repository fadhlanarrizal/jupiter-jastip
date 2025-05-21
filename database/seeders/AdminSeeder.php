<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@jastip.test'],
            [
                'name' => 'Admin Jastip',
                'email' => 'admin@jastip.test',
                'password' => Hash::make('admin123'), // bisa kamu ubah nanti
            ]
        );
    }
}
