<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    const PRIORITY_TINGGI = 'tinggi';
    const PRIORITY_SEDANG = 'sedang';
    const PRIORITY_RENDAH = 'rendah';

    const STATUS_SELESAI = 'selesai';
    const STATUS_BELUM = 'belum_selesai';

    protected $fillable = [
        'user_id',
        'category_id',
        'judul',
        'deskripsi',
        'deadline',
        'priority',
        'status',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'task_tag')->withTimestamps();
    }

    public function subtasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskComment::class)->latest();
    }

    public function attachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Attachment::class)->latest();
    }

    public function activityLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    public function isSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function isTerlambat(): bool
    {
        return ! $this->isSelesai()
            && $this->deadline
            && $this->deadline->isPast();
    }

    public function scopeSearch($query, ?string $term)
    {
        if ($term) {
            $query->where('judul', 'like', "%{$term}%");
        }
        return $query;
    }

    public function subtaskProgress(): int
    {
        $total = $this->subtasks->count();
        if ($total === 0) {
            return 0;
        }
        return (int) round(($this->subtasks->where('is_done', true)->count() / $total) * 100);
    }

    public function priorityLabel(): string
    {
        return match ($this->priority) {
            self::PRIORITY_TINGGI => 'Tinggi',
            self::PRIORITY_SEDANG => 'Sedang',
            self::PRIORITY_RENDAH => 'Rendah',
            default => ucfirst($this->priority),
        };
    }

    public function priorityColor(): string
    {
        return match ($this->priority) {
            self::PRIORITY_TINGGI => 'red',
            self::PRIORITY_SEDANG => 'yellow',
            self::PRIORITY_RENDAH => 'green',
            default => 'gray',
        };
    }
}
