<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Froni Wahyudi',
            'email' => 'froniwahyudi@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin', // jika kolom 'role' ada
            'photo_url' => 'default.png', // jika kolom 'photo_url' ada
            'bio' => 'Admin utama', // jika kolom 'bio' ada
        ]);
    }
}
