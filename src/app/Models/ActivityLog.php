<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'user_id', 'aksi', 'keterangan'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function catat(Task $task, string $aksi, ?string $keterangan = null): void
    {
        static::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'keterangan' => $keterangan,
        ]);
    }
}
