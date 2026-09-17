<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /** @use HasFactory<Task> */
    use HasFactory;

    /**
     * FR-14 (domain task): whitelist eksplisit.
     * Satu-satunya nilai status/priority yang sah — dipakai oleh
     * validasi store/update DAN filter index. Nilai di luar ini ditolak/diabaikan.
     */
    public const STATUSES = ['pending', 'completed'];

    public const PRIORITIES = ['low', 'medium', 'high'];

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'priority',
        'deadline',
        'status',
    ];

    protected $attributes = [
        'priority' => 'medium',
        'status' => 'pending',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
        ];
    }

    /**
     * Project this task belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
