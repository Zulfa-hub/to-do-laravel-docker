@extends('layouts.guest', ['title' => 'Daftar Akun', 'subtitle' => 'Buat akun baru untuk mulai mengelola tugas Anda.'])

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf
    <div>
        <label class="label-field" for="name">Nama Lengkap</label>
        <input class="input-field" type="text" name="name" id="name" value="{{ old('name') }}" required autofocus>
    </div>
    <div>
        <label class="label-field" for="email">Email</label>
        <input class="input-field" type="email" name="email" id="email" value="{{ old('email') }}" required>
    </div>
    <div>
        <label class="label-field" for="password">Password</label>
        <input class="input-field" type="password" name="password" id="password" required>
    </div>
    <div>
        <label class="label-field" for="password_confirmation">Konfirmasi Password</label>
        <input class="input-field" type="password" name="password_confirmation" id="password_confirmation" required>
    </div>
    <button type="submit" class="btn-primary w-full">Daftar &rarr;</button>
</form>
<p class="text-sm text-gray-500 text-center mt-6">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="text-astra-600 font-medium hover:underline">Masuk di sini</a>
</p>
@endsection
