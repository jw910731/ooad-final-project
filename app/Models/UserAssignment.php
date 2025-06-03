<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assignment_id',
        'file_set_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'file_set', 'uuid', 'file_id', 'file_set_id');
    }
}
