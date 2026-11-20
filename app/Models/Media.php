<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'title',
        'type',
        'genre',
        'status',
        'episodes',
        'score',
    ];

    protected $casts = [
        'episodes' => 'integer',
        'score' => 'float',
    ];

    /**
     * Relasi Many-to-Many ke User (kolektor media ini)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'media_user')->withTimestamps();
    }
}