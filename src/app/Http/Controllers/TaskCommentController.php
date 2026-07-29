<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'komentar' => ['required', 'string', 'max:1000'],
        ]);

        $task->comments()->create([
            'user_id' => Auth::id(),
            'komentar' => $validated['komentar'],
        ]);
        ActivityLog::catat($task, 'Menambahkan komentar');

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function destroy(Task $task, \App\Models\TaskComment $comment)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        $comment->delete();
        ActivityLog::catat($task, 'Menghapus komentar');

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
