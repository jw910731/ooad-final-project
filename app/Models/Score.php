<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'title',
        'description',
        'course_id',
        'max_point',
    ];

    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function userScores() : HasMany
    {
        return $this->hasMany(UserScore::class);
    }
}
