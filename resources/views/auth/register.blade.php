@extends('templates.login')
@section('container')
    <style>
        /* Modern responsive typography */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* Clean single-screen layout */
        .clean-register-container {
            max-width: 440px;
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
            margin-bottom: 0.875rem;
        }

        .clean-label {
            display: block;
            font-size: clamp(0.8rem, 2vw, 0.875rem);
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.375rem;
        }

        .clean-input,
        .clean-select {
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

        .clean-input:focus,
        .clean-select:focus {
            outline: none;
            border-color: #3b82f6;
            background-color: #ffffff;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }

        .clean-input.is-invalid,
        .clean-select.is-invalid {
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 3px 6px -1px rgba(16, 185, 129, 0.25);
            margin-top: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .clean-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px -1px rgba(16, 185, 129, 0.35);
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

        /* Grid layout for better space utilization */
        .input-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.875rem;
        }

        /* Single screen optimization */
        @media (max-height: 750px) {
            .clean-register-container {
                padding: 0.75rem;
                justify-content: flex-start;
                padding-top: 1.5rem;
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
            
            .input-grid {
                gap: 0.75rem;
            }
        }

        /* Mobile optimization */
        @media (max-width: 480px) {
            .clean-register-container {
                padding: 0.75rem;
                max-width: 100%;
            }
            
            .clean-form {
                padding: 1.5rem 1.25rem;
                border-radius: 16px;
            }
            
            .clean-input,
            .clean-select {
                padding: 0.6875rem;
            }
            
            .clean-btn {
                padding: 0.6875rem 1rem;
            }
            
            .input-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
        }

        /* Very small screens */
        @media (max-width: 360px) {
            .clean-input-group {
                margin-bottom: 0.625rem;
            }
        }

        /* Landscape phone optimization */
        @media (max-height: 500px) and (orientation: landscape) {
            .clean-register-container {
                padding: 0.75rem;
                justify-content: flex-start;
                padding-top: 1rem;
            }
            
            .clean-form {
                padding: 1.25rem;
            }
            
            .clean-title {
                margin-bottom: 0.75rem;
            }
            
            .clean-input-group {
                margin-bottom: 0.625rem;
            }
            
            .input-grid {
                gap: 0.625rem;
            }
        }

        /* Consistent white theme - no dark mode */
    </style>

    <div class="clean-register-container">
        <form class="clean-form" action="{{ url('/register-proses') }}" method="POST">
            @csrf
            <h1 class="clean-title">{{ $title }}</h1>
            
            <div class="clean-input-group">
                <label class="clean-label">Nama Lengkap</label>
                <input type="text" 
                       placeholder="Masukkan nama lengkap" 
                       class="clean-input @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" 
                       name="name"
                       autocomplete="name">
                @error('name')
                <div class="clean-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="input-grid">
                <div class="clean-input-group">
                    <label class="clean-label">Username</label>
                    <input type="text" 
                           placeholder="Username" 
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
                    <label class="clean-label">Email</label>
                    <input type="email" 
                           placeholder="email@domain.com" 
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
            </div>

            <div class="input-grid">
                <div class="clean-input-group">
                    <label class="clean-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" 
                               class="clean-input @error('password') is-invalid @enderror" 
                               placeholder="Buat password" 
                               name="password"
                               id="password-input"
                               autocomplete="new-password">
                        <i class="fas fa-eye password-toggle" id="password-toggle"></i>
                    </div>
                    @error('password')
                    <div class="clean-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="clean-input-group">
                    <label class="clean-label">Konfirmasi Password</label>
                    <div class="password-wrapper">
                        <input type="password" 
                               class="clean-input @error('password_confirmation') is-invalid @enderror" 
                               placeholder="Ulangi password" 
                               name="password_confirmation"
                               id="password-confirm-input"
                               autocomplete="new-password">
                        <i class="fas fa-eye password-toggle" id="password-confirm-toggle"></i>
                    </div>
                    @error('password_confirmation')
                    <div class="clean-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="input-grid">
                <div class="clean-input-group">
                    <label class="clean-label">Jabatan</label>
                    <select name="jabatan_id" class="clean-select @error('jabatan_id') is-invalid @enderror">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach ($data_jabatan as $dj)
                            @if(old('jabatan_id') == $dj->id)
                                <option value="{{ $dj->id }}" selected>{{ $dj->nama_jabatan }}</option>
                            @else
                                <option value="{{ $dj->id }}">{{ $dj->nama_jabatan }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('jabatan_id')
                    <div class="clean-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="clean-input-group">
                    <label class="clean-label">Lokasi</label>
                    <select name="lokasi_id" class="clean-select @error('lokasi_id') is-invalid @enderror">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach ($data_lokasi as $dl)
                            @if(old('lokasi_id') == $dl->id)
                                <option value="{{ $dl->id }}" selected>{{ $dl->nama_lokasi }}</option>
                            @else
                                <option value="{{ $dl->id }}">{{ $dl->nama_lokasi }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('lokasi_id')
                    <div class="clean-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="clean-btn">
                <i class="fas fa-user-plus" style="margin-right: 0.375rem;"></i>
                Daftar Akun
            </button>
            
            <div class="text-center">
                <p class="login-prompt">
                    Sudah punya akun? 
                    <a href="{{ url('/') }}" class="clean-link">Masuk di sini</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        // Password toggle functionality for both password fields
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

        document.getElementById('password-confirm-toggle').addEventListener('click', function() {
            const passwordInput = document.getElementById('password-confirm-input');
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
        document.querySelectorAll('.clean-input, .clean-select').forEach(input => {
            input.addEventListener('focus', function() {
                this.previousElementSibling.style.color = '#3b82f6';
            });
            
            input.addEventListener('blur', function() {
                this.previousElementSibling.style.color = '#374151';
            });
        });
    </script>
@endsection
