@extends('layouts.app', ['title' => 'Tag'])

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-xs uppercase tracking-[0.2em] text-astra-600 mb-1">Kelola</p>
        <h2 class="text-2xl font-serif font-semibold">Tag Tugas</h2>
    </div>

    <div class="card p-6">
        <h3 class="font-medium mb-3 text-sm text-gray-600 dark:text-gray-300">Tambah Tag Baru</h3>
        <form method="POST" action="{{ route('tags.store') }}" class="flex gap-3">
            @csrf
            <input type="text" name="nama_tag" placeholder="Contoh: Urgent, Meeting, Revisi" class="input-field flex-1" required>
            <button type="submit" class="btn-primary">+ Tambah</button>
        </form>
    </div>

    <div class="card divide-y divide-astra-50 dark:divide-gray-800">
        @forelse ($tags as $tag)
            <div class="p-5 flex items-center justify-between gap-4" x-data="{ editing: false }">
                <form method="POST" action="{{ route('tags.update', $tag) }}" class="flex-1 flex items-center gap-3" x-show="editing" x-cloak>
                    @csrf
                    @method('PUT')
                    <input type="text" name="nama_tag" value="{{ $tag->nama_tag }}" class="input-field flex-1">
                    <button type="submit" class="btn-primary !px-4 !py-2 text-xs">Simpan</button>
                    <button type="button" @click="editing = false" class="btn-outline !px-4 !py-2 text-xs">Batal</button>
                </form>

                <div class="flex-1" x-show="!editing">
                    <p class="font-medium">#{{ $tag->nama_tag }}</p>
                    <p class="text-xs text-gray-400">{{ $tag->tasks_count }} tugas</p>
                </div>

                <div class="flex items-center gap-2 shrink-0" x-show="!editing">
                    <button type="button" @click="editing = true" class="btn-outline !px-3 !py-1.5 text-xs">Edit</button>
                    <form method="POST" action="{{ route('tags.destroy', $tag) }}" onsubmit="return confirm('Hapus tag ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="!px-3 !py-1.5 text-xs rounded-full border border-red-200 text-red-500 hover:bg-red-50">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-400 text-sm">Belum ada tag.</div>
        @endforelse
    </div>

    <div>{{ $tags->links() }}</div>
</div>
@endsection
