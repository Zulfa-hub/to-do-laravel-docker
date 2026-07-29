<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::where('user_id', Auth::id())->with('category');

        $query->search($request->input('q'));

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $sort = $request->input('sort', 'deadline_asc');
        match ($sort) {
            'deadline_desc' => $query->orderByDesc('deadline'),
            'terbaru' => $query->orderByDesc('created_at'),
            default => $query->orderBy('deadline'),
        };

        $tasks = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('nama_kategori')->get();

        return view('tasks.index', compact('tasks', 'categories', 'sort'));
    }

    public function create()
    {
        $categories = Category::orderBy('nama_kategori')->get();
        $tags = Tag::orderBy('nama_tag')->get();

        return view('tasks.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateTask($request);
        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);
        $validated['user_id'] = Auth::id();
        $validated['status'] = Task::STATUS_BELUM;

        $task = Task::create($validated);
        $task->tags()->sync($tagIds);
        ActivityLog::catat($task, 'Membuat tugas');

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function show(Task $task)
    {
        $this->authorizeTask($task);
        $task->load(['subtasks', 'comments.user', 'attachments.user', 'activityLogs.user', 'tags', 'category']);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorizeTask($task);
        $categories = Category::orderBy('nama_kategori')->get();
        $tags = Tag::orderBy('nama_tag')->get();
        $task->load('tags');

        return view('tasks.edit', compact('task', 'categories', 'tags'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task);
        $validated = $this->validateTask($request);
        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $task->update($validated);
        $task->tags()->sync($tagIds);
        ActivityLog::catat($task, 'Memperbarui tugas');

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Task $task)
    {
        $this->authorizeTask($task);
        $task->delete();

        return back()->with('success', 'Tugas berhasil dihapus.');
    }

    public function toggleStatus(Task $task)
    {
        $this->authorizeTask($task);

        if ($task->isSelesai()) {
            $task->update(['status' => Task::STATUS_BELUM, 'completed_at' => null]);
            ActivityLog::catat($task, 'Menandai belum selesai');
        } else {
            $task->update(['status' => Task::STATUS_SELESAI, 'completed_at' => now()]);
            ActivityLog::catat($task, 'Menandai selesai');
        }

        return back()->with('success', 'Status tugas berhasil diperbarui.');
    }

    public function history(Request $request)
    {
        $tasks = Task::where('user_id', Auth::id())
            ->where('status', Task::STATUS_SELESAI)
            ->with('category')
            ->orderByDesc('completed_at')
            ->paginate(10);

        return view('history.index', compact('tasks'));
    }

    private function validateTask(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'deadline' => ['nullable', 'date'],
            'priority' => ['required', 'in:tinggi,sedang,rendah'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);
    }

    private function authorizeTask(Task $task): void
    {
        abort_if($task->user_id !== Auth::id(), 403);
    }
}
