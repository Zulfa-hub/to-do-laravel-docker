<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        $request->validate([
            'file' => ['required', 'file', 'max:5120'],
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments', 'public');

        $task->attachments()->create([
            'user_id' => Auth::id(),
            'nama_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'ukuran' => $file->getSize(),
        ]);

        ActivityLog::catat($task, 'Mengunggah lampiran', $file->getClientOriginalName());

        return back()->with('success', 'File berhasil diunggah.');
    }

    public function destroy(Task $task, Attachment $attachment)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        Storage::disk('public')->delete($attachment->path_file);
        $nama = $attachment->nama_file;
        $attachment->delete();

        ActivityLog::catat($task, 'Menghapus lampiran', $nama);

        return back()->with('success', 'Lampiran berhasil dihapus.');
    }
}
