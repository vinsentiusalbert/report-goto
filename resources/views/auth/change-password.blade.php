@extends('layouts.dashboard')

@section('title', 'Change Password')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow text-green">ACCOUNT</p>
            <h1>Change Password</h1>
            <p>Ubah password akun Anda dengan aman.</p>
        </div>
    </div>

    <section class="panel filter-panel">
        <div class="panel-head">
            <div>
                <h2>Update Password</h2>
                <p>Masukkan password lama lalu password baru Anda.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="flash-message flash-message--success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="flash-message flash-message--error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="filter-form password-form">
            @csrf
            @method('PUT')
            <label>
                <span>Password Lama</span>
                <input type="password" name="current_password" required>
            </label>
            <label>
                <span>Password Baru</span>
                <input type="password" name="password" required>
            </label>
            <label>
                <span>Konfirmasi Password Baru</span>
                <input type="password" name="password_confirmation" required>
            </label>
            <div class="filter-actions">
                <button type="submit" class="primary-button filter-submit">Update Password</button>
            </div>
        </form>
    </section>
@endsection
