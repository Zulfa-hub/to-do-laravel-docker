@extends('layouts.app', ['title' => 'Profil Saya'])

@section('content')
<div class="max-w-2xl space-y-6">
    <div>
        <p class="text-xs uppercase tracking-[0.2em] text-astra-600 mb-1">Akun</p>
        <h2 class="text-2xl font-serif font-semibold">Profil Saya</h2>
    </div>

    <div class="card p-6">
        <h3 class="font-serif font-semibold text-lg mb-4">Informasi Profil</h3>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="label-field" for="name">Nama Lengkap</label>
                <input class="input-field" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div>
                <label class="label-field" for="email">Email</label>
                <input class="input-field" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <div class="card p-6">
        <h3 class="font-serif font-semibold text-lg mb-4">Ubah Password</h3>
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="label-field" for="current_password">Password Saat Ini</label>
                <input class="input-field" type="password" name="current_password" id="current_password" required>
            </div>
            <div>
                <label class="label-field" for="password">Password Baru</label>
                <input class="input-field" type="password" name="password" id="password" required>
            </div>
            <div>
                <label class="label-field" for="password_confirmation">Konfirmasi Password Baru</label>
                <input class="input-field" type="password" name="password_confirmation" id="password_confirmation" required>
            </div>
            <button type="submit" class="btn-primary">Ubah Password</button>
        </form>
    </div>
</div>
@endsection
