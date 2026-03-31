@extends('templates.login')
@section('container')
    <style>
        /* Modern responsive typography */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* Clean single-screen layout */
        .clean-qr-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* QR Scanner Card */
        .qr-scanner-card {
            background: #fefefe;
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            box-shadow: 
                0 10px 25px -5px rgba(0, 0, 0, 0.08),
                0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(240, 240, 240, 0.8);
            width: 100%;
            text-align: center;
        }

        /* Clean typography */
        .clean-title {
            font-size: clamp(1.25rem, 3vw, 1.5rem);
            font-weight: 700;
            color: #1f2937;
            text-align: center;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .clean-subtitle {
            font-size: clamp(0.875rem, 2.5vw, 0.9375rem);
            color: #6b7280;
            text-align: center;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        /* QR Reader Styling */
        #reader {
            margin: 1rem auto;
            max-width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Override QR Scanner default styles */
        #reader video {
            border-radius: 12px;
        }

        #reader__camera_selection {
            margin-bottom: 1rem;
        }

        #reader__camera_selection select {
            padding: 0.5rem;
            border-radius: 8px;
            border: 1.5px solid #e8e8e8;
            background-color: #fafafa;
            font-size: 0.875rem;
            color: #374151;
        }

        /* QR Scanner Camera Permission Button Styling */
        #reader__dashboard_section_csr button,
        #reader__dashboard_section_csr input[type="button"],
        #reader__dashboard_section_csr input[type="submit"],
        .html5-qrcode-button,
        button[onclick*="start"],
        button[onclick*="stop"] {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            color: white !important;
            border: none !important;
            padding: 0.75rem 1.25rem !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 3px 6px -1px rgba(59, 130, 246, 0.25) !important;
            margin: 0.5rem !important;
        }
        
        #reader__dashboard_section_csr button:hover,
        #reader__dashboard_section_csr input[type="button"]:hover,
        #reader__dashboard_section_csr input[type="submit"]:hover,
        .html5-qrcode-button:hover,
        button[onclick*="start"]:hover,
        button[onclick*="stop"]:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px -1px rgba(59, 130, 246, 0.35) !important;
        }
        
        /* File input styling - make it more visible but avoid duplicates */
        #reader__dashboard_section_csr input[type="file"] {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
            border: 2px solid #059669 !important;
            padding: 0.75rem 1.25rem !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 3px 6px -1px rgba(16, 185, 129, 0.25) !important;
            margin: 0.5rem !important;
            width: auto !important;
            display: inline-block !important;
            position: relative !important;
        }
        
        #reader__dashboard_section_csr input[type="file"]:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px -1px rgba(16, 185, 129, 0.35) !important;
        }
        
        /* Style the file upload button for the QR scanner */
        #reader__dashboard_section_csr input[type="file"]::-webkit-file-upload-button {
            background: transparent !important;
            border: none !important;
            color: white !important;
            font-weight: 600 !important;
            padding: 0 !important;
            margin: 0 !important;
            font-size: 0.875rem !important;
        }
        
        /* Style for Firefox */
        #reader__dashboard_section_csr input[type="file"]::-moz-file-upload-button {
            background: transparent !important;
            border: none !important;
            color: white !important;
            font-weight: 600 !important;
            padding: 0 !important;
            margin: 0 !important;
            font-size: 0.875rem !important;
        }
        
        /* Make the "No file chosen" text visible */
        #reader__dashboard_section_csr input[type="file"]::file-selector-button {
            background: transparent !important;
            border: none !important;
            color: white !important;
            font-weight: 600 !important;
            padding: 0 !important;
            margin: 0 !important;
            font-size: 0.875rem !important;
        }
        
        /* Ensure the file input text is visible */
        #reader__dashboard_section_csr input[type="file"] {
            color: #374151 !important;
            font-size: 0.875rem !important;
        }
        
        /* Hide any duplicate file inputs that might be created by the library */
        #reader input[type="file"]:not(#reader__dashboard_section_csr input[type="file"]) {
            display: none !important;
        }
        
        /* Ensure only one file input is visible in the dashboard section */
        #reader__dashboard_section_csr {
            position: relative !important;
        }
        
        /* Make QR scanner text more visible */
        #reader__dashboard_section_csr,
        #reader__dashboard_section_csr * {
            color: #374151 !important;
        }
        
        #reader__dashboard_section_csr p,
        #reader__dashboard_section_csr span,
        #reader__dashboard_section_csr div:not(input):not(button):not(select) {
            color: #374151 !important;
            font-weight: 500 !important;
        }
        
        /* Style the "Or drop an image to scan" text */
        #reader__dashboard_section_csr p,
        #reader__dashboard_section_csr .html5-qrcode-element,
        #reader__dashboard_section_csr [class*="html5-qrcode"] {
            color: #374151 !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
        }
        
        /* Style the "Scan using camera directly" link */
        #reader__dashboard_section_csr a,
        #reader__dashboard_section_csr [href] {
            color: #3b82f6 !important;
            text-decoration: underline !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
        }
        
        #reader__dashboard_section_csr a:hover,
        #reader__dashboard_section_csr [href]:hover {
            color: #1d4ed8 !important;
        }
        
        /* Ensure all text in the scanner area is visible */
        #reader * {
            color: #374151 !important;
        }
        
        #reader p,
        #reader span,
        #reader div:not(input):not(button):not(select) {
            color: #374151 !important;
            font-weight: 500 !important;
        }
        
        #reader a,
        #reader [href] {
            color: #3b82f6 !important;
            text-decoration: underline !important;
            font-weight: 500 !important;
        }
        #reader__dashboard_section_csr select {
            background-color: #fafafa !important;
            border: 1.5px solid #e8e8e8 !important;
            color: #374151 !important;
            padding: 0.5rem !important;
            border-radius: 8px !important;
            font-size: 0.875rem !important;
        }
        
        /* QR Scanner dashboard section */
        #reader__dashboard_section_csr {
            background: rgba(255, 255, 255, 0.9) !important;
            border-radius: 12px !important;
            padding: 1rem !important;
            margin-bottom: 1rem !important;
            border: 1px solid rgba(240, 240, 240, 0.8) !important;
        }

        /* Status indicator */
        .status-indicator {
            background: rgba(59, 130, 246, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.1);
            border-radius: 10px;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .status-text {
            color: #3b82f6;
            font-size: clamp(0.8125rem, 2vw, 0.875rem);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }

        /* Clean button */
        .clean-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.25rem;
            font-size: clamp(0.875rem, 2.2vw, 0.9375rem);
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 3px 6px -1px rgba(107, 114, 128, 0.25);
            text-decoration: none;
            margin-top: 1rem;
        }

        .clean-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px -1px rgba(107, 114, 128, 0.35);
            color: white;
            text-decoration: none;
        }

        /* Instructions */
        .instructions {
            background: rgba(16, 185, 129, 0.05);
            border: 1px solid rgba(16, 185, 129, 0.1);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .instruction-title {
            color: #059669;
            font-size: clamp(0.875rem, 2vw, 0.9375rem);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .instruction-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .instruction-item {
            color: #047857;
            font-size: clamp(0.8125rem, 2vw, 0.875rem);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        /* Loading state */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .loading-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
        }

        /* Mobile optimization */
        @media (max-width: 480px) {
            .clean-qr-container {
                padding: 0.75rem;
                max-width: 100%;
            }
            
            .qr-scanner-card {
                padding: 1.5rem 1.25rem;
                border-radius: 16px;
            }
            
            #reader {
                margin: 0.75rem auto;
            }
        }

        /* Landscape phone optimization */
        @media (max-height: 600px) and (orientation: landscape) {
            .clean-qr-container {
                padding: 0.75rem;
                justify-content: flex-start;
                padding-top: 1rem;
            }
            
            .qr-scanner-card {
                padding: 1.25rem;
            }
            
            .clean-title {
                margin-bottom: 0.5rem;
            }
            
            .clean-subtitle {
                margin-bottom: 1rem;
            }
            
            .instructions,
            .status-indicator {
                padding: 0.75rem;
                margin-bottom: 1rem;
            }
        }

        /* Consistent white theme - no dark mode */
    </style>

    <div class="clean-qr-container">
        <div class="qr-scanner-card">
            <h1 class="clean-title">
                <i class="fas fa-qrcode" style="margin-right: 0.5rem; color: #8b5cf6;"></i>
                {{ $title }}
            </h1>
            <p class="clean-subtitle">
                Arahkan kamera ke QR code untuk absen masuk
            </p>
            
            <div class="instructions">
                <div class="instruction-title">
                    <i class="fas fa-info-circle"></i>
                    Cara Penggunaan:
                </div>
                <ul class="instruction-list">
                    <li class="instruction-item">
                        <i class="fas fa-dot-circle" style="margin-top: 0.125rem; font-size: 0.5rem;"></i>
                        Pastikan QR code terlihat jelas dalam frame
                    </li>
                    <li class="instruction-item">
                        <i class="fas fa-dot-circle" style="margin-top: 0.125rem; font-size: 0.5rem;"></i>
                        Jaga jarak yang tepat dengan QR code
                    </li>
                    <li class="instruction-item">
                        <i class="fas fa-dot-circle" style="margin-top: 0.125rem; font-size: 0.5rem;"></i>
                        Tunggu hingga scanning berhasil
                    </li>
                </ul>
            </div>
            
            <div class="status-indicator">
                <p class="status-text">
                    <i class="fas fa-camera"></i>
                    Scanner siap digunakan
                </p>
            </div>
            
            <div id="reader"></div>
            
            <a href="{{ url('/') }}" class="clean-btn">
                <i class="fas fa-arrow-left" style="margin-right: 0.375rem;"></i>
                Kembali ke Login
            </a>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status" style="margin-bottom: 1rem;">
                <span class="sr-only">Loading...</span>
            </div>
            <p>Memproses absen masuk...</p>
        </div>
    </div>

    @push('script')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Variables untuk menyimpan lokasi
            let userLat = null;
            let userLong = null;

            function showLoading() {
                document.getElementById('loadingOverlay').style.display = 'flex';
            }

            function hideLoading() {
                document.getElementById('loadingOverlay').style.display = 'none';
            }

            function updateStatus(message, type = 'info') {
                const statusElement = document.querySelector('.status-text');
                const icons = {
                    'info': 'fas fa-camera',
                    'success': 'fas fa-check-circle',
                    'error': 'fas fa-exclamation-triangle',
                    'processing': 'fas fa-spinner fa-spin'
                };
                
                statusElement.innerHTML = `<i class="${icons[type]}"></i> ${message}`;
            }

            // Fungsi untuk mendapatkan lokasi
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            userLat = position.coords.latitude;
                            userLong = position.coords.longitude;
                            console.log('Location obtained:', userLat, userLong);
                        },
                        function(error) {
                            console.error('Geolocation error:', error);
                            // Tetap lanjutkan meskipun gagal mendapat lokasi
                            // Backend akan menolak jika lock_location aktif
                        }
                    );
                } else {
                    console.error('Geolocation not supported');
                }
            }

            // Dapatkan lokasi saat halaman dimuat
            getLocation();
            // Update lokasi setiap 5 detik
            setInterval(getLocation, 5000);

            function onScanSuccess(decodedText, decodedResult) {
                let username = decodedText;
                updateStatus('QR code terdeteksi, memproses...', 'processing');
                showLoading();
                
                html5QrcodeScanner.clear().then(_ => {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url('/qr-masuk/store') }}",
                        type: 'POST',
                        data: {
                            _methode : "POST",
                            _token: CSRF_TOKEN,
                            username : username,
                            lat: userLat,
                            long: userLong
                        },
                        success: function (response) {
                            hideLoading();
                            console.log(response);
                            
                            if(response == 'masuk'){
                                updateStatus('Absen masuk berhasil!', 'success');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Absen masuk berhasil dicatat',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                setTimeout(function() {
                                    location.reload();
                                }, 2000);
                            } else if (response == 'selesai'){
                                updateStatus('Sudah absen masuk', 'error');
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Perhatian',
                                    text: 'Anda sudah melakukan absen masuk hari ini',
                                    timer: 3000
                                });
                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
                            } else if (response == 'noMs'){
                                updateStatus('Shift belum diatur', 'error');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Shift Belum Diatur',
                                    text: 'Hubungi Admin untuk mengatur shift Anda',
                                    timer: 3000
                                });
                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
                            } else if (response == 'outlocation'){
                                updateStatus('Di luar radius kantor', 'error');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Di Luar Lokasi',
                                    text: 'Anda berada di luar radius kantor. Absensi tidak dapat dilakukan.',
                                    timer: 3000
                                });
                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
                            } else {
                                updateStatus('QR code tidak valid', 'error');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'QR Code Tidak Valid',
                                    text: 'Data QR atau pegawai tidak ditemukan dalam sistem',
                                    timer: 3000
                                });
                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
                            }
                        },
                        error: function() {
                            hideLoading();
                            updateStatus('Terjadi kesalahan', 'error');
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Silakan coba lagi atau hubungi admin',
                                timer: 3000
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                        }
                    });
                }).catch(error => {
                    hideLoading();
                    updateStatus('Scanner error', 'error');
                    Swal.fire({
                        icon: 'error',
                        title: 'Scanner Error',
                        text: 'Terjadi kesalahan pada scanner',
                        timer: 3000
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                });
            }

            function onScanFailure(error) {
                // Handle scan failure silently for better UX
            }

            // Initialize QR Scanner
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { 
                    fps: 10, 
                    qrbox: {width: 250, height: 250},
                    aspectRatio: 1.0
                },
                /* verbose= */ false
            );
            
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        </script>
    @endpush
@endsection
