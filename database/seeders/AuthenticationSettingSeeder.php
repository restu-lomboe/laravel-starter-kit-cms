<?php

namespace Database\Seeders;

use App\Models\AuthenticationSetting;
use Illuminate\Database\Seeder;

class AuthenticationSettingSeeder extends Seeder
{
    public function run(): void
    {
        AuthenticationSetting::firstOrCreate(
            ['id' => 1],
            [
                'default_method' => 'email',
                'passkey_enabled' => false,
                'google_sso_enabled' => true,
            ]
        );
    }
}
