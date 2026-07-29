@extends('layouts.app', ['title' => 'Manajemen Tugas'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-astra-600 mb-1">Kelola</p>
            <h2 class="text-2xl font-serif font-semibold">Daftar Tugas</h2>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn-primary">+ Tambah Tugas</a>
    </div>

    <form method="GET" action="{{ route('tasks.index') }}" class="card p-5 grid grid-cols-1 md:grid-cols-5 gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul tugas..." class="input-field md:col-span-2">

        <select name="category_id" class="input-field">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->nama_kategori }}</option>
            @endforeach
        </select>

        <select name="priority" class="input-field">
            <option value="">Semua Prioritas</option>
            <option value="tinggi" @selected(request('priority') === 'tinggi')>Tinggi</option>
            <option value="sedang" @selected(request('priority') === 'sedang')>Sedang</option>
            <option value="rendah" @selected(request('priority') === 'rendah')>Rendah</option>
        </select>

        <select name="status" class="input-field">
            <option value="">Semua Status</option>
            <option value="belum_selesai" @selected(request('status') === 'belum_selesai')>Belum Selesai</option>
            <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
        </select>

        <select name="sort" class="input-field">
            <option value="deadline_asc" @selected(request('sort', 'deadline_asc') === 'deadline_asc')>Deadline Terdekat</option>
            <option value="deadline_desc" @selected(request('sort') === 'deadline_desc')>Deadline Terjauh</option>
            <option value="terbaru" @selected(request('sort') === 'terbaru')>Baru Ditambahkan</option>
        </select>

        <div class="md:col-span-2 flex gap-2">
            <button type="submit" class="btn-primary flex-1">Terapkan Filter</button>
            <a href="{{ route('tasks.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <div class="card divide-y divide-astra-50 dark:divide-gray-800">
        @forelse ($tasks as $task)
            <div class="p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0
                        {{ $task->isSelesai() ? 'bg-astra-500 border-astra-500 text-white' : 'border-astra-300 text-transparent hover:border-astra-500' }}">
                        &#10003;
                    </button>
                </form>

                <div class="flex-1 min-w-0">
                    <a href="{{ route('tasks.show', $task) }}" class="font-medium hover:text-astra-600 {{ $task->isSelesai() ? 'line-through text-gray-400' : '' }}">
                        {{ $task->judul }}
                    </a>
                    <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs">
                        <span class="badge bg-astra-50 text-astra-600 dark:bg-gray-800">{{ $task->category->nama_kategori ?? 'Tanpa kategori' }}</span>
                        <span class="badge bg-{{ $task->priorityColor() }}-100 text-{{ $task->priorityColor() }}-700">{{ $task->priorityLabel() }}</span>
                        @if ($task->deadline)
                            <span class="text-gray-400">&#128337; {{ $task->deadline->translatedFormat('d M Y, H:i') }}</span>
                        @endif
                        @if ($task->isTerlambat())
                            <span class="badge bg-red-100 text-red-600">Terlambat</span>
                        @elseif ($task->isSelesai())
                            <span class="badge bg-green-100 text-green-600">Selesai</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn-outline !px-3 !py-1.5 text-xs">Edit</a>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Hapus tugas ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="!px-3 !py-1.5 text-xs rounded-full border border-red-200 text-red-500 hover:bg-red-50">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-400 text-sm">
                Tidak ada tugas yang sesuai. <a href="{{ route('tasks.create') }}" class="text-astra-600 hover:underline">Tambah tugas baru</a>.
            </div>
        @endforelse
    </div>

    <div>{{ $tasks->links() }}</div>
</div>
@endsection
