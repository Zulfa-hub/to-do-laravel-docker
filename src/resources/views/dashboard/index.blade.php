@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
<div class="space-y-8">
    <div>
        <p class="text-xs uppercase tracking-[0.2em] text-astra-600 mb-1">Ringkasan</p>
        <h2 class="text-2xl font-serif font-semibold">Statistik Tugas Anda</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="card p-6">
            <p class="text-sm text-gray-500 mb-2">Total Tugas</p>
            <p class="text-3xl font-serif font-semibold">{{ $total }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm text-gray-500 mb-2">Selesai</p>
            <p class="text-3xl font-serif font-semibold text-green-600">{{ $selesai }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm text-gray-500 mb-2">Belum Selesai</p>
            <p class="text-3xl font-serif font-semibold text-astra-600">{{ $belum }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm text-gray-500 mb-2">Terlambat</p>
            <p class="text-3xl font-serif font-semibold text-red-500">{{ $terlambat }}</p>
        </div>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-600">Progress Penyelesaian</p>
            <span class="text-sm font-semibold text-astra-600">{{ $progress }}%</span>
        </div>
        <div class="w-full h-3 rounded-full bg-astra-100 overflow-hidden">
            <div class="h-full bg-astra-button rounded-full transition-all" style="width: {{ $progress }}%"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-serif font-semibold text-lg">Tugas Terbaru</h3>
                <a href="{{ route('tasks.index') }}" class="text-xs text-astra-600 hover:underline">Lihat semua &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse ($tugasTerbaru as $task)
                    <div class="flex items-center justify-between border-b border-astra-50 dark:border-gray-800 pb-3 last:border-0 last:pb-0">
                        <div>
                            <a href="{{ route('tasks.show', $task) }}" class="text-sm font-medium hover:text-astra-600">{{ $task->judul }}</a>
                            <p class="text-xs text-gray-400">{{ $task->category->nama_kategori ?? 'Tanpa kategori' }}</p>
                        </div>
                        <span class="badge bg-{{ $task->priorityColor() }}-100 text-{{ $task->priorityColor() }}-700">{{ $task->priorityLabel() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada tugas. <a href="{{ route('tasks.create') }}" class="text-astra-600 hover:underline">Tambah tugas baru</a>.</p>
                @endforelse
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-serif font-semibold text-lg">Deadline Mendatang</h3>
            </div>
            <div class="space-y-3">
                @forelse ($tugasMendatang as $task)
                    <div class="flex items-center justify-between border-b border-astra-50 dark:border-gray-800 pb-3 last:border-0 last:pb-0">
                        <div>
                            <a href="{{ route('tasks.show', $task) }}" class="text-sm font-medium hover:text-astra-600">{{ $task->judul }}</a>
                            <p class="text-xs text-gray-400">{{ $task->deadline?->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <span class="badge bg-{{ $task->priorityColor() }}-100 text-{{ $task->priorityColor() }}-700">{{ $task->priorityLabel() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Tidak ada deadline mendatang.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
