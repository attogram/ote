@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Edit User</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('name')
                <span class="text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('email')
                <span class="text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700">New Password (optional)</label>
            <input id="password" type="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('password')
                <span class="text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="role" class="block text-gray-700">Role</label>
            <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="{{ \App\Models\User::ROLE_USER }}" @if($user->role === \App\Models\User::ROLE_USER) selected @endif>User</option>
                <option value="{{ \App\Models\User::ROLE_WORKER }}" @if($user->role === \App\Models\User::ROLE_WORKER) selected @endif>Worker</option>
                <option value="{{ \App\Models\User::ROLE_ADMIN }}" @if($user->role === \App\Models\User::ROLE_ADMIN) selected @endif>Admin</option>
            </select>
            @error('role')
                <span class="text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update User</button>
        </div>
    </form>
</div>
@endsection
