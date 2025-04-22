<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'score_id',
        'score_point',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function score(): BelongsTo
    {
        return $this->belongsTo(Score::class);
    }
}
