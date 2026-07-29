<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubtaskController extends Controller
{
    public function store(Request $request, Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
        ]);

        $task->subtasks()->create($validated);
        ActivityLog::catat($task, 'Menambahkan sub-tugas', $validated['judul']);

        return back()->with('success', 'Sub-tugas berhasil ditambahkan.');
    }

    public function toggle(Task $task, Subtask $subtask)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        $subtask->update(['is_done' => ! $subtask->is_done]);
        ActivityLog::catat($task, $subtask->is_done ? 'Menyelesaikan sub-tugas' : 'Membatalkan sub-tugas', $subtask->judul);

        return back()->with('success', 'Status sub-tugas diperbarui.');
    }

    public function destroy(Task $task, Subtask $subtask)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        $subtask->delete();
        ActivityLog::catat($task, 'Menghapus sub-tugas', $subtask->judul);

        return back()->with('success', 'Sub-tugas berhasil dihapus.');
    }
}
