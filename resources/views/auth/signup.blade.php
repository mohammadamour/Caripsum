<x-auth-layout title="Signup" bodyClass="page-signup" :showHeader="false">
    <form action="{{ route("signup") }}" method="post">
        @csrf

        <div class="form-group">
            <input
                type="email"
                name="email"
                placeholder="Your Email"
                value="{{ old("email") }}"
            />
            @error("email")
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input
                type="password"
                name="password"
                placeholder="Your Password"
            />
            @error("password")
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input
                type="password"
                name="password_confirmation"
                placeholder="Repeat Password"
            />
        </div>

        <hr />

        <div class="form-group">
            <input
                type="text"
                name="fname"
                placeholder="First Name"
                value="{{ old("first_name") }}"
            />
            @error("first_name")
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input
                type="text"
                name="lname"
                placeholder="Last Name"
                value="{{ old("last_name") }}"
            />
            @error("last_name")
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input
                type="text"
                name="phone"
                placeholder="Phone"
                value="{{ old("phone") }}"
            />
            @error("phone")
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary btn-login w-full">Register</button>

        <p class="text-center mt-3 text-muted">
            Already have an account?
            <a href="{{ route("login") }}">Click here to login</a>
        </p>
    </form>
</x-auth-layout>
