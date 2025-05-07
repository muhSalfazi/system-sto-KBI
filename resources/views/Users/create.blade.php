@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="pagetitle">
        <h1>Create User</h1>

        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Data User</a></li>
                <li class="breadcrumb-item active">Create User</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Create User</h5>
                <form class="row g-3 needs-validation" novalidate method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="col-md-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username"
                            class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username') }}" placeholder="Silahkan inputkan username">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" name="first_name" id="first_name"
                            class="form-control @error('first_name') is-invalid @enderror"
                            value="{{ old('first_name') }}" placeholder="Input nama depan">
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" name="last_name" id="last_name"
                            class="form-control @error('last_name') is-invalid @enderror"
                            value="{{ old('last_name') }}" placeholder="Input nama belakang">
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="ID-card" class="form-label">ID Card</label>
                        <input type="text" name="ID-card" id="ID-card"
                            class="form-control @error('ID-card') is-invalid @enderror"
                            value="{{ old('ID-card') }}" placeholder="Silahkan inputkan ID-card">
                        @error('ID-card')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select mb-3 @error('role') is-invalid @enderror"
                            required>
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="yourPassword" class="form-label">Password</label>
                        <div class="input-group has-validation">
                            <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="yourPassword" value="{{ old('password') }}">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                            </button>
                            <small class="text-muted">*Password default otomatis sesuai ID Card</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="col-6">
                        <button class="btn btn-primary" type="submit">Create User</button>
                    </div>
                </form>

            </div>
        </div>
    </section>

    {{-- js hidden+show PW --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const idCardInput = document.getElementById('ID-card');
            const passwordInput = document.getElementById('yourPassword');

            idCardInput.addEventListener('input', function() {
                passwordInput.value = idCardInput.value;
            });

            // Toggle Password Visibility
            document.getElementById('togglePassword').addEventListener('click', function() {
                const passwordIcon = document.getElementById('togglePasswordIcon');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordIcon.classList.remove('bi-eye-slash');
                    passwordIcon.classList.add('bi-eye');
                } else {
                    passwordInput.type = 'password';
                    passwordIcon.classList.remove('bi-eye');
                    passwordIcon.classList.add('bi-eye-slash');
                }
            });
        });
    </script>


@endsection
