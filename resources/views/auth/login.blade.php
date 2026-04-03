<x-auth-layout title="Login" bodyClass="page-login" :showHeader="false">
    <form action="{{ route("login") }}" method="post">
        @csrf
        
        @if(session('success'))
            <div class="text-success mb-medium">{{ session('success') }}</div>
        @endif
        
        <div class="form-group">
            <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" />
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Your Password" />
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-primary btn-login w-full">Login</button>

        <div class="login-text-dont-have-account">
            Don't have an account? -
            <a href="/signup">Click here to create one</a>
        </div>
    </form>
</x-auth-layout>
