@extends('layouts.dashboard')

@section('title', 'Manajemen User')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow text-green">ADMIN</p>
            <h1>Manajemen User</h1>
            <p>Hanya admin yang bisa membuat akun baru.</p>
        </div>
    </div>

    <section class="panel filter-panel">
        <div class="panel-head">
            <div>
                <h2>Buat User Baru</h2>
                <p>Isi data user dan pilih role yang sesuai.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="flash-message flash-message--success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="flash-message flash-message--error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('users.store') }}" class="filter-form">
            @csrf
            <label>
                <span>Nama</span>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </label>
            <label>
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </label>
            <label>
                <span>Role</span>
                <select name="role" required>
                    <option value="user" @selected(old('role') === 'user')>User</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                </select>
            </label>
            <label>
                <span>Password</span>
                <input type="password" name="password" required>
            </label>
            <label>
                <span>Konfirmasi Password</span>
                <input type="password" name="password_confirmation" required>
            </label>
            <div class="filter-actions">
                <button type="submit" class="primary-button filter-submit">Create User</button>
            </div>
        </form>
    </section>

    <section class="panel activity-panel">
        <div class="panel-head">
            <div>
                <h2>Daftar User</h2>
                <p>Daftar akun yang terdaftar di sistem.</p>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>NAMA</th>
                        <th>EMAIL</th>
                        <th>ROLE</th>
                        <th>DIBUAT</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="role-pill role-pill--{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                            <td>{{ $user->created_at?->format('d M Y H:i:s') ?? '-' }}</td>
                            <td>
                                @if(auth()->id() === $user->id)
                                    <span class="table-note">Akun aktif</span>
                                @else
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="danger-button">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">Belum ada user yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
