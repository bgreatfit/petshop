<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JwtToken extends Model
{
    use HasFactory;
    protected $table = 'jwt_tokens';

    protected $fillable = [
        'unique_id',
        'user_id',
        'token_title',
        'restrictions',
        'permissions',
        'created_at',
        'updated_at',
        'expires_at',
        'last_used_at',
        'refreshed_at',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }


}
