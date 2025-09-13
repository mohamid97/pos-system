{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            margin: 20px;
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-header p {
            opacity: 0.9;
            font-size: 16px;
        }
        
        .login-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group.has-error input {
            border-color: #e74c3c;
            background-color: #fdf2f2;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .remember-me input[type="checkbox"] {
            width: auto;
        }
        
        .remember-me label {
            margin-bottom: 0;
            font-weight: 400;
            font-size: 14px;
        }
        
        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        .forgot-password:hover {
            color: #764ba2;
        }
        
        .login-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .login-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .loading {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        .loading::after {
            content: '';
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e1e5e9;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            color: #764ba2;
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 16px;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #888;
            cursor: pointer;
            font-size: 16px;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
            }
            
            .login-header, .login-form {
                padding: 20px;
            }
            
            .remember-forgot {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Sign in to your account</p>
        </div>
        
        <div class="login-form">
            {{-- Display Success Messages --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            {{-- Display Error Messages --}}
            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                {{-- Email Field --}}
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email">Email Address</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email" 
                        autofocus
                        placeholder="Enter your email address"
                    >
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                {{-- Password Field --}}
                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password">Password</label>
                    <div style="position: relative;">
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword()">👁️</button>
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                {{-- Remember & Forgot --}}
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Remember me</label>
                    </div>
                    
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>
                
                {{-- Login Button --}}
                <button type="submit" class="login-button" id="loginButton">
                    <span class="button-text">Sign In</span>
                    <div class="loading"></div>
                </button>
            </form>
            
            {{-- Register Link --}}
            <div class="register-link">
                <p>Don't have an account? <a href="#">Create one here</a></p>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = '👁️';
            }
        }

        // Form Submission with Loading State
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = document.getElementById('loginButton');
            const buttonText = button.querySelector('.button-text');
            const loading = button.querySelector('.loading');
            
            // Show loading state
            button.disabled = true;
            buttonText.style.visibility = 'hidden';
            loading.style.display = 'block';
            
            // Re-enable button after 3 seconds if form doesn't submit
            setTimeout(() => {
                button.disabled = false;
                buttonText.style.visibility = 'visible';
                loading.style.display = 'none';
            }, 3000);
        });

        // Input Focus Effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentNode.classList.remove('focused');
            });
        });

        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add floating label effect
            document.querySelectorAll('input').forEach(input => {
                if (input.value) {
                    input.parentNode.classList.add('has-content');
                }
                
                input.addEventListener('input', function() {
                    if (this.value) {
                        this.parentNode.classList.add('has-content');
                    } else {
                        this.parentNode.classList.remove('has-content');
                    }
                });
            });
        });
    </script>
</body>
</html>