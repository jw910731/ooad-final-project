<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Info extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'title',
        'description',
        'course_id',
        'file_set_id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'file_set', 'uuid', 'file_id', 'file_set_id');
    }
}
