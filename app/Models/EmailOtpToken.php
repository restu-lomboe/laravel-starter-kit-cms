<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EmailOtpToken extends Model
{
    protected $fillable = ['email', 'token', 'expires_at', 'attempts'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && Carbon::now()->isAfter($this->expires_at);
    }
}
