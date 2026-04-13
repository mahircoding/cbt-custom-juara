<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'app_name' => 'Pelajarin',
            'app_url' => 'https://pelajarin.com',
            'logo' => 'pelajarin.png',
            'favicon' => 'favicon.png',
            'signed_name' => 'Pelajarin',
            'signed_image' => 'signed.png',
            'address' => 'Jl Cigebar No 4 RT02/RW02 Desa Bojongsari Kec. Bojongsoang, Kab Bandung',
            'whatsapp_number' => '6281212126043',
            'add_activation_user' => 1,
            'login_type' => [
                ['type' => 'email'],
            ],
            'authentication_field' => [
                ['name' => 'email', 'translate' => 'Email', 'is_active' => '1', 'is_required' => '1'],
                ['name' => 'password', 'translate' => 'Password', 'is_active' => '1', 'is_required' => '1'],
                ['name' => 'name', 'translate' => 'Nama Lengkap', 'is_active' => '1', 'is_required' => '1'],
                ['name' => 'username', 'translate' => 'Username', 'is_active' => '1', 'is_required' => '1'],
                ['name' => 'phone_number', 'translate' => 'Nomor WhatsApp', 'is_active' => '1', 'is_required' => '1'],
                ['name' => 'province_id', 'translate' => 'Provinsi', 'is_active' => '1', 'is_required' => '1'],
                ['name' => 'city_id', 'translate' => 'Kota', 'is_active' => '1', 'is_required' => '1'],
                ['name' => 'district_id', 'translate' => 'Kecamatan', 'is_active' => '1', 'is_required' => '1'],
                ['name' => 'village_id', 'translate' => 'Desa', 'is_active' => '1', 'is_required' => '1'],
                ['name' => 'address', 'translate' => 'Alamat', 'is_active' => '1', 'is_required' => '1'],
                ['name' => 'photo', 'translate' => 'Foto', 'is_active' => '1', 'is_required' => '0'],
                ['name' => 'gender', 'translate' => 'Jenis Kelamin', 'is_active' => '1', 'is_required' => '1'],
            ],
            'activation_page_background' => 'activation_bg.png',
            'forgot_password_page_background' => 'forgot_password_bg.png',
            'login_page_background' => 'login_bg.png',
            'register_page_background' => 'register_page_background.png',
            'commission' => 0,
            'minimum_withdrawal' => 50000,
            'admin_fee' => 0,
        ]);
    }
}
