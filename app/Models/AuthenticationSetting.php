<?php

namespace App\Models;

use Database\Factories\AuthenticationSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationSetting extends Model
{
    /** @use HasFactory<AuthenticationSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'default_method',
        'passkey_enabled',
        'google_sso_enabled',
        'google_client_id',
        'google_client_secret',
    ];

    protected function casts(): array
    {
        return [
            'passkey_enabled' => 'boolean',
            'google_sso_enabled' => 'boolean',
            'google_client_secret' => 'encrypted',
        ];
    }

    /**
     * Get the singleton settings row, creating it if missing.
     */
    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'default_method' => 'email',
                'passkey_enabled' => false,
                'google_sso_enabled' => true,
            ]
        );
    }
}
