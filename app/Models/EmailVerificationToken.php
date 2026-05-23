<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailVerificationToken extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'token', 'expires_at'];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (is_null($model->created_at)) {
                $model->created_at = now();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function isValid(): bool
    {
        return is_null($this->used_at) && $this->expires_at->isFuture();
    }

    public static function generateToken(): string
    {
        return bin2hex(random_bytes(32)); // 64-char hex string
    }
}
