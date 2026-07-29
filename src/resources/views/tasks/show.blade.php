@extends('layouts.app', ['title' => 'Detail Tugas'])

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-astra-600 mb-1">Detail</p>
            <h2 class="text-2xl font-serif font-semibold {{ $task->isSelesai() ? 'line-through text-gray-400' : '' }}">{{ $task->judul }}</h2>
        </div>
        <a href="{{ route('tasks.index') }}" class="text-sm text-astra-600 hover:underline">&larr; Kembali</a>
    </div>

    <div class="card p-6 space-y-5">
        <div class="flex flex-wrap gap-2">
            <span class="badge bg-astra-50 text-astra-600">{{ $task->category->nama_kategori ?? 'Tanpa kategori' }}</span>
            <span class="badge bg-{{ $task->priorityColor() }}-100 text-{{ $task->priorityColor() }}-700">Prioritas {{ $task->priorityLabel() }}</span>
            @if ($task->isTerlambat())
                <span class="badge bg-red-100 text-red-600">Terlambat</span>
            @elseif ($task->isSelesai())
                <span class="badge bg-green-100 text-green-600">Selesai</span>
            @else
                <span class="badge bg-yellow-100 text-yellow-700">Belum Selesai</span>
            @endif
        </div>

        <div>
            <p class="label-field">Deskripsi</p>
            <p class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $task->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="label-field">Tanggal Dibuat</p>
                <p class="text-gray-600 dark:text-gray-300">{{ $task->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="label-field">Deadline</p>
                <p class="text-gray-600 dark:text-gray-300">{{ $task->deadline?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
            </div>
            <div>
                <p class="label-field">Tanggal Selesai</p>
                <p class="text-gray-600 dark:text-gray-300">{{ $task->completed_at?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 pt-2 border-t border-astra-50 dark:border-gray-800">
            <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-primary">
                    {{ $task->isSelesai() ? 'Tandai Belum Selesai' : 'Tandai Selesai' }}
                </button>
            </form>
            <a href="{{ route('tasks.edit', $task) }}" class="btn-outline">Edit Tugas</a>
            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Hapus tugas ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-outline !border-red-200 !text-red-500">Hapus Tugas</button>
            </form>
        </div>
    </div>

    @if ($task->tags->isNotEmpty())
        <div class="flex flex-wrap gap-2">
            @foreach ($task->tags as $tag)
                <span class="badge bg-astra-50 text-astra-600">#{{ $tag->nama_tag }}</span>
            @endforeach
        </div>
    @endif

    <!-- Sub-tugas -->
    <div class="card p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-serif font-semibold text-lg">Sub-Tugas</h3>
            <span class="text-xs text-gray-400">{{ $task->subtaskProgress() }}% selesai</span>
        </div>
        <div class="w-full h-2 rounded-full bg-astra-100 overflow-hidden">
            <div class="h-full bg-astra-button rounded-full" style="width: {{ $task->subtaskProgress() }}%"></div>
        </div>
        <div class="space-y-2">
            @forelse ($task->subtasks as $subtask)
                <div class="flex items-center justify-between gap-3">
                    <form method="POST" action="{{ route('subtasks.toggle', [$task, $subtask]) }}" class="flex items-center gap-3 flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-5 h-5 rounded-full border-2 flex items-center justify-center text-[10px] shrink-0
                            {{ $subtask->is_done ? 'bg-astra-500 border-astra-500 text-white' : 'border-astra-300 text-transparent' }}">
                            &#10003;
                        </button>
                        <span class="text-sm {{ $subtask->is_done ? 'line-through text-gray-400' : '' }}">{{ $subtask->judul }}</span>
                    </form>
                    <form method="POST" action="{{ route('subtasks.destroy', [$task, $subtask]) }}" onsubmit="return confirm('Hapus sub-tugas ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-400">Belum ada sub-tugas.</p>
            @endforelse
        </div>
        <form method="POST" action="{{ route('subtasks.store', $task) }}" class="flex gap-2 pt-2">
            @csrf
            <input type="text" name="judul" placeholder="Tambah sub-tugas baru..." class="input-field flex-1" required>
            <button type="submit" class="btn-outline !px-4 text-sm">+ Tambah</button>
        </form>
    </div>

    <!-- Lampiran -->
    <div class="card p-6 space-y-4">
        <h3 class="font-serif font-semibold text-lg">Lampiran</h3>
        <div class="space-y-2">
            @forelse ($task->attachments as $attachment)
                <div class="flex items-center justify-between gap-3 text-sm">
                    <a href="{{ asset('storage/'.$attachment->path_file) }}" target="_blank" class="text-astra-600 hover:underline truncate">
                        &#128206; {{ $attachment->nama_file }} <span class="text-gray-400">({{ $attachment->ukuranHuman() }})</span>
                    </a>
                    <form method="POST" action="{{ route('attachments.destroy', [$task, $attachment]) }}" onsubmit="return confirm('Hapus lampiran ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 shrink-0">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-400">Belum ada lampiran.</p>
            @endforelse
        </div>
        <form method="POST" action="{{ route('attachments.store', $task) }}" enctype="multipart/form-data" class="flex gap-2 pt-2">
            @csrf
            <input type="file" name="file" class="input-field flex-1 text-sm" required>
            <button type="submit" class="btn-outline !px-4 text-sm">Unggah</button>
        </form>
    </div>

    <!-- Komentar -->
    <div class="card p-6 space-y-4">
        <h3 class="font-serif font-semibold text-lg">Komentar</h3>
        <div class="space-y-3">
            @forelse ($task->comments as $comment)
                <div class="flex items-start justify-between gap-3 border-b border-astra-50 dark:border-gray-800 pb-3 last:border-0">
                    <div>
                        <p class="text-sm font-medium">{{ $comment->user->name }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $comment->komentar }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                    <form method="POST" action="{{ route('comments.destroy', [$task, $comment]) }}" onsubmit="return confirm('Hapus komentar ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 shrink-0">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-400">Belum ada komentar.</p>
            @endforelse
        </div>
        <form method="POST" action="{{ route('comments.store', $task) }}" class="flex gap-2 pt-2">
            @csrf
            <input type="text" name="komentar" placeholder="Tulis komentar..." class="input-field flex-1" required>
            <button type="submit" class="btn-outline !px-4 text-sm">Kirim</button>
        </form>
    </div>

    <!-- Log Aktivitas -->
    <div class="card p-6 space-y-3">
        <h3 class="font-serif font-semibold text-lg">Riwayat Aktivitas</h3>
        <div class="space-y-2">
            @forelse ($task->activityLogs as $log)
                <div class="text-xs text-gray-500 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-astra-400"></span>
                    <span class="font-medium text-gray-600 dark:text-gray-300">{{ $log->user->name ?? 'Sistem' }}</span>
                    <span>{{ $log->aksi }}{{ $log->keterangan ? ' — '.$log->keterangan : '' }}</span>
                    <span class="text-gray-400">&middot; {{ $log->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
