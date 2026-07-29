@extends('layouts.app', ['title' => 'Edit Tugas'])

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <p class="text-xs uppercase tracking-[0.2em] text-astra-600 mb-1">Perbarui</p>
        <h2 class="text-2xl font-serif font-semibold">Edit Tugas</h2>
    </div>

    <form method="POST" action="{{ route('tasks.update', $task) }}" class="card p-6 space-y-5">
        @method('PUT')
        @include('tasks._form')
        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="{{ route('tasks.index') }}" class="btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
