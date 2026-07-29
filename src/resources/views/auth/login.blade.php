@extends('layouts.guest', ['title' => 'Masuk', 'subtitle' => 'Selamat datang kembali! Silakan masuk ke akun Anda.'])

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf
    <div>
        <label class="label-field" for="email">Email</label>
        <input class="input-field" type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
    </div>
    <div>
        <label class="label-field" for="password">Password</label>
        <input class="input-field" type="password" name="password" id="password" required>
    </div>
    <div class="flex items-center justify-between text-sm">
        <label class="flex items-center gap-2 text-gray-500">
            <input type="checkbox" name="remember" class="rounded border-astra-300 text-astra-600 focus:ring-astra-400">
            Ingat saya
        </label>
    </div>
    <button type="submit" class="btn-primary w-full">Masuk &rarr;</button>
</form>
<p class="text-sm text-gray-500 text-center mt-6">
    Belum punya akun?
    <a href="{{ route('register') }}" class="text-astra-600 font-medium hover:underline">Daftar sekarang</a>
</p>
@endsection
