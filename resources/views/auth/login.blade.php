<x-auth-layout title="Login" bodyClass="page-login" :showHeader="false">
    <form action="{{ route("login") }}" method="post">
        @csrf
        <div class="form-group">
            <input type="email" name="email" placeholder="Your Email" />
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Your Password" />
        </div>
        <button class="btn btn-primary btn-login w-full">Login</button>

        <div class="login-text-dont-have-account">
            Don't have an account? -
            <a href="/signup">Click here to create one</a>
        </div>
    </form>
</x-auth-layout>
