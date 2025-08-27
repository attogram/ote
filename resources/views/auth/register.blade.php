@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Register</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
            @error('password')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <div>
            <button type="submit">
                Register
            </button>
        </div>
    </form>
</div>
@endsection
