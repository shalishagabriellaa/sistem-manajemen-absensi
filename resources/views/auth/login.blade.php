@extends('templates.login')
@section('container')
    <style>
        /* Modern responsive typography */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* Clean single-screen layout */
        .clean-login-container {
            max-width: 420px;
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
            margin-bottom: 1rem;
        }

        /* Clean typography */
        .clean-title {
            font-size: clamp(1.5rem, 3.5vw, 1.875rem);
            font-weight: 700;
            color: #1f2937;
            text-align: center;
            margin-bottom: 1.5rem;
            line-height: 1.2;
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

        /* Password wrapper */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.2s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            color: #374151;
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
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 3px 6px -1px rgba(59, 130, 246, 0.25);
            margin-bottom: 0.75rem;
        }

        .clean-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px -1px rgba(59, 130, 246, 0.35);
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

        /* Compact quick access */
        .quick-access {
            background: #fdfdfd;
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(235, 235, 235, 0.8);
            width: 100%;
        }

        .access-header {
            text-align: center;
            margin-bottom: 0.75rem;
        }

        .access-title {
            font-size: clamp(0.9375rem, 2.5vw, 1.0625rem);
            font-weight: 600;
            color: #374151;
            margin: 0;
        }

        .access-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .access-section {
            text-align: center;
        }

        .section-label {
            font-size: clamp(0.75rem, 2vw, 0.8125rem);
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 0.5rem;
            padding-bottom: 0.25rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .access-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
        }

        .access-btn {
            padding: 0.5rem 0.75rem;
            font-size: clamp(0.75rem, 1.8vw, 0.8125rem);
            font-weight: 500;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .face-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .face-btn:hover {
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px -1px rgba(16, 185, 129, 0.25);
        }

        .qr-btn {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }

        .qr-btn:hover {
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px -1px rgba(139, 92, 246, 0.25);
        }

        /* Single screen optimization */
        @media (max-height: 700px) {
            .clean-login-container {
                padding: 0.75rem;
                justify-content: flex-start;
                padding-top: 2rem;
            }
            
            .clean-form {
                padding: 1.5rem 1.25rem;
                margin-bottom: 0.75rem;
            }
            
            .clean-title {
                margin-bottom: 1rem;
            }
            
            .clean-input-group {
                margin-bottom: 0.75rem;
            }
            
            .quick-access {
                padding: 1rem;
            }
            
            .access-grid {
                gap: 0.5rem;
            }
            
            .access-btn {
                padding: 0.4375rem 0.625rem;
            }
        }

        /* Mobile optimization */
        @media (max-width: 480px) {
            .clean-login-container {
                padding: 0.75rem;
                max-width: 100%;
            }
            
            .clean-form {
                padding: 1.5rem 1.25rem;
                border-radius: 16px;
            }
            
            .quick-access {
                padding: 1rem;
                border-radius: 12px;
            }
            
            .clean-input {
                padding: 0.6875rem;
            }
            
            .clean-btn {
                padding: 0.6875rem 1rem;
            }
        }

        /* Very small screens */
        @media (max-width: 360px) {
            .access-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .access-buttons {
                flex-direction: row;
                gap: 0.375rem;
            }
            
            .access-btn {
                flex: 1;
                padding: 0.4375rem 0.5rem;
            }
        }

        /* Landscape phone optimization */
        @media (max-height: 500px) and (orientation: landscape) {
            .clean-login-container {
                flex-direction: row;
                align-items: stretch;
                gap: 1rem;
                padding: 1rem;
            }
            
            .clean-form {
                flex: 1;
                margin-bottom: 0;
                margin-right: 0.5rem;
            }
            
            .quick-access {
                flex: 1;
                margin-left: 0.5rem;
            }
            
            .access-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
        }

        /* Consistent white theme - no dark mode */
    </style>

    <div class="clean-login-container">
        <form class="clean-form" action="{{ url('/login-proses') }}" method="POST">
            @csrf
            <h1 class="clean-title">{{ $title }}</h1>
            <div class="clean-input-group">
                <label class="clean-label">Username</label>
                <input type="text" 
                       placeholder="Masukkan username" 
                       class="clean-input @error('username') is-invalid @enderror" 
                       value="{{ old('username') }}" 
                       name="username"
                       autocomplete="username">
                @error('username')
                <div class="clean-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>
            
            <div class="clean-input-group">
                <label class="clean-label">Password</label>
                <div class="password-wrapper">
                    <input type="password" 
                           class="clean-input @error('password') is-invalid @enderror" 
                           placeholder="Masukkan password" 
                           name="password"
                           id="password-input"
                           autocomplete="current-password">
                    <i class="fas fa-eye password-toggle" id="password-toggle"></i>
                </div>
                @error('password')
                <div class="clean-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <button type="submit" class="clean-btn">
                <i class="fas fa-sign-in-alt" style="margin-right: 0.375rem;"></i>
                Masuk
            </button>
            
            <div class="text-center">
                <a href="{{ url('/forgot-password') }}" class="clean-link">
                    <i class="fas fa-key" style="margin-right: 0.25rem;"></i>
                    Lupa Password?
                </a>
            </div>
        </form>

        <div class="quick-access">
            <div class="access-header">
                <h3 class="access-title">
                    <i class="fas fa-clock" style="margin-right: 0.375rem; color: #3b82f6;"></i>
                    Akses Cepat Absensi
                </h3>
            </div>
            
            <div class="access-grid">
                <div class="access-section">
                    <div class="section-label">
                        <i class="fas fa-user-circle" style="margin-right: 0.25rem; color: #10b981;"></i>
                        Face ID
                    </div>
                    <div class="access-buttons">
                        <a href="{{ url('/presensi') }}" class="access-btn face-btn">
                            <i class="fas fa-sign-in-alt" style="margin-right: 0.25rem;"></i>
                            Masuk
                        </a>
                        <a href="{{ url('/presensi-pulang') }}" class="access-btn face-btn">
                            <i class="fas fa-sign-out-alt" style="margin-right: 0.25rem;"></i>
                            Pulang
                        </a>
                    </div>
                </div>
                
                <div class="access-section">
                    <div class="section-label">
                        <i class="fas fa-qrcode" style="margin-right: 0.25rem; color: #8b5cf6;"></i>
                        QR Code
                    </div>
                    <div class="access-buttons">
                        <a href="{{ url('/qr-masuk') }}" class="access-btn qr-btn">
                            <i class="fas fa-sign-in-alt" style="margin-right: 0.25rem;"></i>
                            Masuk
                        </a>
                        <a href="{{ url('/qr-pulang') }}" class="access-btn qr-btn">
                            <i class="fas fa-sign-out-alt" style="margin-right: 0.25rem;"></i>
                            Pulang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.getElementById('password-toggle').addEventListener('click', function() {
            const passwordInput = document.getElementById('password-input');
            const icon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Form submission handling
        document.querySelector('.clean-form').addEventListener('submit', function() {
            const submitBtn = this.querySelector('.clean-btn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.375rem;"></i>Memproses...';
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
