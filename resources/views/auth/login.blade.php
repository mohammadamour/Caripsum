<x-auth-layout title="Login" bodyClass="page-login" :showHeader="false">
    <form action="{{ route("login") }}" method="post">
        @csrf
        
        @if(session('success'))
            <div class="text-success mb-medium">{{ session('success') }}</div>
        @endif

        <!-- Quick Demo Login Helper -->
        <div style="margin-bottom: 1.25rem; padding: 0.75rem 1rem; background: #fff7ed; border: 1px dashed #fdba74; border-radius: 0.75rem; text-align: center;">
            <p style="margin: 0 0 0.5rem 0; font-size: 0.8rem; color: #9a3412; font-weight: 600;">
                Demo Credentials: <span style="font-family: monospace; font-weight: 700;">demo@example.com</span> / <span style="font-family: monospace; font-weight: 700;">password</span>
            </p>
            <button
                type="button"
                onclick="fillDemoAccount()"
                class="btn btn-default"
                style="width: 100%; font-size: 0.85rem; padding: 0.45rem 0.75rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary-color);">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Auto-fill Demo Account
            </button>
        </div>
        
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

    <script>
        function fillDemoAccount() {
            const emailInput = document.querySelector('input[name="email"]');
            const passwordInput = document.querySelector('input[name="password"]');
            if (emailInput && passwordInput) {
                emailInput.value = 'demo@example.com';
                passwordInput.value = 'password';
            }
        }
    </script>
</x-auth-layout>
