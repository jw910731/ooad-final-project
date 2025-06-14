<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'score_id',
        'order',
        'title',
        'description',
        'deadline',
        'file_set_id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function score(): BelongsTo
    {
        return $this->belongsTo(Score::class);
    }

    public function userAssignment(): HasMany
    {
        return $this->hasMany(UserAssignment::class);
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'file_set', 'uuid', 'file_id', 'file_set_id');
    }
}
