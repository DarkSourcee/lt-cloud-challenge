<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    /** @use HasFactory<\Database\Factories\DeveloperFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'seniority',
        'skills',
    ];

    protected $casts = [
        'skills' => 'array',
    ];

    /**
     * Get the user that owns the developer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The articles that belong to the developer.
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
