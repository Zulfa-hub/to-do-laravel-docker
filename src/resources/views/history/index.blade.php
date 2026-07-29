@extends('layouts.app', ['title' => 'Riwayat Tugas'])

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-xs uppercase tracking-[0.2em] text-astra-600 mb-1">Riwayat</p>
        <h2 class="text-2xl font-serif font-semibold">Tugas Selesai</h2>
    </div>

    <div class="card divide-y divide-astra-50 dark:divide-gray-800">
        @forelse ($tasks as $task)
            <div class="p-5 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <a href="{{ route('tasks.show', $task) }}" class="font-medium hover:text-astra-600 line-through text-gray-400">{{ $task->judul }}</a>
                    <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs">
                        <span class="badge bg-astra-50 text-astra-600 dark:bg-gray-800">{{ $task->category->nama_kategori ?? 'Tanpa kategori' }}</span>
                        <span class="badge bg-{{ $task->priorityColor() }}-100 text-{{ $task->priorityColor() }}-700">{{ $task->priorityLabel() }}</span>
                        <span class="text-gray-400">Selesai: {{ $task->completed_at?->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
                <span class="badge bg-green-100 text-green-600 shrink-0">&#10003; Selesai</span>
            </div>
        @empty
            <div class="p-10 text-center text-gray-400 text-sm">Belum ada tugas yang selesai.</div>
        @endforelse
    </div>

    <div>{{ $tasks->links() }}</div>
</div>
@endsection
