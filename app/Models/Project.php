<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /** @use HasFactory<Project> */
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'description',
    ];

    /**
     * Owner of the project.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Members of the project (many-to-many via project_members).
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }

    /**
     * Alias for members() to match belongsToMany(User::class) expectation.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }

    /**
     * Tasks inside the project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
