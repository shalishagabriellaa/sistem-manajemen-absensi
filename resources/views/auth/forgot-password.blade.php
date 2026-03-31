@extends('templates.login')
@section('container')
    <style>
        /* Modern responsive typography */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* Clean single-screen layout */
        .clean-forgot-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Compact form styling */
        .clean-form {
            background: #fefefe;
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            box-shadow: 
                0 10px 25px -5px rgba(0, 0, 0, 0.08),
                0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(240, 240, 240, 0.8);
            width: 100%;
        }

        /* Clean typography */
        .clean-title {
            font-size: clamp(1.5rem, 3.5vw, 1.875rem);
            font-weight: 700;
            color: #1f2937;
            text-align: center;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .clean-subtitle {
            font-size: clamp(0.875rem, 2.5vw, 0.9375rem);
            color: #6b7280;
            text-align: center;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        /* Compact input styling */
        .clean-input-group {
            margin-bottom: 1rem;
        }

        .clean-label {
            display: block;
            font-size: clamp(0.8rem, 2vw, 0.875rem);
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.375rem;
        }

        .clean-input {
            width: 100%;
            padding: 0.75rem;
            font-size: clamp(0.875rem, 2.5vw, 0.9375rem);
            line-height: 1.4;
            color: #1f2937;
            background-color: #fafafa;
            border: 1.5px solid #e8e8e8;
            border-radius: 10px;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .clean-input:focus {
            outline: none;
            border-color: #3b82f6;
            background-color: #ffffff;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }

        .clean-input.is-invalid {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        /* Compact error styling */
        .clean-error {
            color: #ef4444;
            font-size: clamp(0.75rem, 1.8vw, 0.8125rem);
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Clean button */
        .clean-btn {
            width: 100%;
            padding: 0.75rem 1.25rem;
            font-size: clamp(0.875rem, 2.2vw, 0.9375rem);
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 3px 6px -1px rgba(245, 158, 11, 0.25);
            margin-bottom: 1rem;
        }

        .clean-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px -1px rgba(245, 158, 11, 0.35);
        }

        /* Compact links */
        .clean-link {
            color: #3b82f6;
            text-decoration: none;
            font-size: clamp(0.8125rem, 2vw, 0.875rem);
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .clean-link:hover {
            color: #1d4ed8;
            text-decoration: none;
        }

        .login-prompt {
            text-align: center;
            color: #6b7280;
            font-size: clamp(0.875rem, 2vw, 0.9375rem);
        }

        /* Info box */
        .info-box {
            background: rgba(59, 130, 246, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.1);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-text {
            color: #3b82f6;
            font-size: clamp(0.8125rem, 2vw, 0.875rem);
            text-align: center;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }

        /* Mobile optimization */
        @media (max-width: 480px) {
            .clean-forgot-container {
                padding: 0.75rem;
                max-width: 100%;
            }
            
            .clean-form {
                padding: 1.5rem 1.25rem;
                border-radius: 16px;
            }
            
            .clean-input {
                padding: 0.6875rem;
            }
            
            .clean-btn {
                padding: 0.6875rem 1rem;
            }
        }

        /* Landscape phone optimization */
        @media (max-height: 500px) and (orientation: landscape) {
            .clean-forgot-container {
                padding: 0.75rem;
                justify-content: flex-start;
                padding-top: 2rem;
            }
            
            .clean-form {
                padding: 1.25rem;
            }
            
            .clean-title {
                margin-bottom: 0.75rem;
            }
            
            .clean-subtitle {
                margin-bottom: 1rem;
            }
        }

        /* Consistent white theme - no dark mode */
    </style>

    <div class="clean-forgot-container">
        <form class="clean-form" action="{{ url('/forgot-password/link') }}" method="POST">
            @csrf
            <h1 class="clean-title">{{ $title }}</h1>
            <p class="clean-subtitle">
                Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
            </p>
            
            <div class="info-box">
                <p class="info-text">
                    <i class="fas fa-info-circle"></i>
                    Link reset akan dikirim ke email yang terdaftar dalam sistem
                </p>
            </div>
            
            <div class="clean-input-group">
                <label class="clean-label">Email Address</label>
                <input type="email" 
                       placeholder="Masukkan alamat email Anda" 
                       class="clean-input @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       name="email"
                       autocomplete="email">
                @error('email')
                <div class="clean-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <button type="submit" class="clean-btn">
                <i class="fas fa-paper-plane" style="margin-right: 0.375rem;"></i>
                Kirim Link Reset
            </button>
            
            <div class="text-center">
                <p class="login-prompt">
                    Ingat password Anda? 
                    <a href="{{ url('/') }}" class="clean-link">
                        <i class="fas fa-arrow-left" style="margin-right: 0.25rem;"></i>
                        Kembali ke Login
                    </a>
                </p>
            </div>
        </form>
    </div>

    <script>
        // Form submission handling
        document.querySelector('.clean-form').addEventListener('submit', function() {
            const submitBtn = this.querySelector('.clean-btn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.375rem;"></i>Mengirim...';
            submitBtn.disabled = true;
        });

        // Input focus animations
        document.querySelectorAll('.clean-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.previousElementSibling.style.color = '#3b82f6';
            });
            
            input.addEventListener('blur', function() {
                this.previousElementSibling.style.color = '#374151';
            });
        });
    </script>
@endsection
