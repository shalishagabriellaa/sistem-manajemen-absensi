@extends('templates.login')
@section('container')
    <style>
        /* Modern responsive typography */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* Clean single-screen layout */
        .clean-face-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Face Recognition Card */
        .face-recognition-card {
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
            position: relative;
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

        /* Video container */
        .video-container {
            position: relative;
            margin: 1rem auto 1.5rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            background: #000;
        }

        #video {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 12px;
        }

        /* Canvas overlay - positioned relative to video container */
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none; /* Allow clicks to pass through */
            border-radius: 12px;
        }

        /* Status indicator */
        .status-indicator {
            background: rgba(16, 185, 129, 0.05);
            border: 1px solid rgba(16, 185, 129, 0.1);
            border-radius: 10px;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .status-text {
            color: #059669;
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
            position: relative;
            z-index: 10; /* Ensure button is above canvas */
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

        /* Location info */
        .location-info {
            background: rgba(59, 130, 246, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.1);
            border-radius: 10px;
            padding: 0.75rem;
            margin-bottom: 1rem;
            font-size: clamp(0.8125rem, 2vw, 0.875rem);
            color: #3b82f6;
        }

        /* Loading overlay */
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
            .clean-face-container {
                padding: 0.75rem;
                max-width: 100%;
            }
            
            .face-recognition-card {
                padding: 1.5rem 1.25rem;
                border-radius: 16px;
            }
            
            .video-container {
                margin: 0.75rem auto 1rem;
            }
        }

        /* Landscape phone optimization */
        @media (max-height: 700px) and (orientation: landscape) {
            .clean-face-container {
                padding: 0.75rem;
                justify-content: flex-start;
                padding-top: 1rem;
            }
            
            .face-recognition-card {
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
            
            .video-container {
                margin: 0.75rem auto 1rem;
            }
        }

        /* Consistent white theme - no dark mode */
    </style>

    <div class="clean-face-container">
        <div class="face-recognition-card">
            <h1 class="clean-title">
                <i class="fas fa-user-circle" style="margin-right: 0.5rem; color: #10b981;"></i>
                {{ $title }}
            </h1>
            <p class="clean-subtitle">
                Posisikan wajah Anda di dalam frame untuk absen masuk
            </p>
            
            <div class="instructions">
                <div class="instruction-title">
                    <i class="fas fa-info-circle"></i>
                    Cara Penggunaan:
                </div>
                <ul class="instruction-list">
                    <li class="instruction-item">
                        <i class="fas fa-dot-circle" style="margin-top: 0.125rem; font-size: 0.5rem;"></i>
                        Pastikan wajah terlihat jelas dalam frame
                    </li>
                    <li class="instruction-item">
                        <i class="fas fa-dot-circle" style="margin-top: 0.125rem; font-size: 0.5rem;"></i>
                        Jaga jarak yang tepat dengan kamera
                    </li>
                    <li class="instruction-item">
                        <i class="fas fa-dot-circle" style="margin-top: 0.125rem; font-size: 0.5rem;"></i>
                        Tunggu hingga sistem mengenali wajah Anda
                    </li>
                </ul>
            </div>
            
            <div class="status-indicator">
                <p class="status-text">
                    <i class="fas fa-camera"></i>
                    <span id="status-message">Memuat sistem pengenalan wajah...</span>
                </p>
            </div>
            
            <div class="location-info">
                <i class="fas fa-map-marker-alt" style="margin-right: 0.25rem;"></i>
                Mendeteksi lokasi Anda...
            </div>
            
            <div class="video-container">
                <video id="video" autoplay playsinline></video>
            </div>
            
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="long" id="long">
            
            <button type="button" class="clean-btn" id="captureBtn" style="background: linear-gradient(135deg,#10b981,#059669);">
                <i class="fas fa-camera" style="margin-right:0.375rem;"></i>
                Ambil Foto & Absen
            </button>
            
            <a href="{{ url('/') }}" class="clean-btn">
                <i class="fas fa-arrow-left" style="margin-right: 0.375rem;"></i>
                Kembali ke Login
            </a>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner-border text-success" role="status" style="margin-bottom: 1rem;">
                <span class="sr-only">Loading...</span>
            </div>
            <p>Memproses pengenalan wajah...</p>
        </div>
    </div>

    @push('script')
        <script src="{{ url('/face/dist/face-api.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function updateStatus(message, type = 'info') {
                const statusElement = document.getElementById('status-message');
                const statusContainer = document.querySelector('.status-indicator');
                const icons = {
                    'info': 'fas fa-camera',
                    'success': 'fas fa-check-circle',
                    'error': 'fas fa-exclamation-triangle',
                    'processing': 'fas fa-spinner fa-spin',
                    'loading': 'fas fa-cog fa-spin'
                };
                
                const colors = {
                    'info': '#059669',
                    'success': '#059669', 
                    'error': '#dc2626',
                    'processing': '#3b82f6',
                    'loading': '#6b7280'
                };
                
                statusElement.textContent = message;
                statusContainer.querySelector('i').className = icons[type];
                statusContainer.style.borderColor = colors[type] + '30';
                statusContainer.style.backgroundColor = colors[type] + '10';
                statusContainer.querySelector('.status-text').style.color = colors[type];
            }

            function showLoading() {
                document.getElementById('loadingOverlay').style.display = 'flex';
            }

            function hideLoading() {
                document.getElementById('loadingOverlay').style.display = 'none';
            }

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition, showLocationError);
                } else {
                    document.querySelector('.location-info').innerHTML = 
                        '<i class="fas fa-exclamation-triangle" style="margin-right: 0.25rem;"></i>Geolocation tidak didukung browser ini';
                }
            }

            function showPosition(position) {
                $('#lat').val(position.coords.latitude);
                $('#long').val(position.coords.longitude);
                document.querySelector('.location-info').innerHTML = 
                    '<i class="fas fa-map-marker-alt" style="margin-right: 0.25rem;"></i>Lokasi terdeteksi ✓';
            }

            function showLocationError(error) {
                document.querySelector('.location-info').innerHTML = 
                    '<i class="fas fa-exclamation-triangle" style="margin-right: 0.25rem;"></i>Gagal mendeteksi lokasi';
            }

            // Get location every 5 seconds
            setInterval(getLocation, 5000);
            getLocation(); // Initial call

            let faceMatcher = undefined;
            let video = document.getElementById("video");
            let canvas = document.createElement("canvas");
            let ctx = canvas.getContext("2d");

            let isProcessing = false;
            
            // Append canvas to video container instead of body
            document.querySelector('.video-container').appendChild(canvas);

            let width = 640; // Increased resolution for better face detection
            let height = 480;

            const startStream = async () => {
                try {
                    updateStatus('Mengakses kamera...', 'loading');
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: { 
                            facingMode: "user", 
                            width: { ideal: 640 }, 
                            height: { ideal: 480 },
                            frameRate: { ideal: 30 }
                        },
                        audio: false
                    });
                    video.srcObject = stream;
                    updateStatus('Kamera aktif, memuat model AI...', 'loading');
                } catch (error) {
                    console.error('Error accessing camera:', error);
                    updateStatus('Gagal mengakses kamera', 'error');
                }
            }

            // Load required models
            Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri("{{ url('/face/weights') }}"),
                faceapi.nets.faceLandmark68Net.loadFromUri("{{ url('/face/weights') }}"),
                faceapi.nets.faceRecognitionNet.loadFromUri("{{ url('/face/weights') }}")
            ]).then(startStream);

            video.onloadedmetadata = () => {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                start();
            };

async function start() {
    updateStatus('Memuat data wajah terdaftar...', 'loading');

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url: "{{ url('/ajaxGetNeural') }}",
        method: 'GET'
    }).done(async function(data) {
        try {
            // Parse langsung — neural.json adalah plain array
            const content = (typeof data === 'string') ? JSON.parse(data) : data;

            if (!content || content.length === 0) {
                updateStatus('Tidak ada data wajah terdaftar', 'error');
                return;
            }

            const labeledDescriptors = content.map(person => {
                const descs = person.descriptors.map(d => new Float32Array(d));
                return new faceapi.LabeledFaceDescriptors(person.label, descs);
            });

            // Threshold 0.6 lebih toleran
            faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6);
            updateStatus('Sistem siap! Arahkan wajah ke kamera', 'info');
            onPlay();
        } catch(e) {
            console.error('Error parsing neural data:', e);
            updateStatus('Error memproses data wajah', 'error');
        }
    }).fail(function() {
        updateStatus('Gagal memuat data wajah', 'error');
    });
}

            async function createFaceMatcher(data) {
                const labeledFaceDescriptors = await Promise.all(data.parent.map(className => {
                    return new faceapi.LabeledFaceDescriptors(
                        className.label,
                        className.descriptors.map(d => new Float32Array(d))
                    );
                }));
                return new faceapi.FaceMatcher(labeledFaceDescriptors, 0.4); // Reduced threshold for better recognition
            }

            async function onPlay() {
                if (faceMatcher) {
                    const displaySize = { width: video.videoWidth, height: video.videoHeight };
                    faceapi.matchDimensions(canvas, displaySize);

                    const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions({
                        inputSize: 416, // Increased input size for better detection
                        scoreThreshold: 0.3 // Lower threshold for better detection
                    }))
                        .withFaceLandmarks()
                        .withFaceDescriptors();
                    const resizedDetections = faceapi.resizeResults(detections, displaySize);
                    const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));

                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    results.forEach((result, i) => {
                        const box = resizedDetections[i].detection.box;
                        const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() });
                        drawBox.draw(canvas);

                        let label = result.label;
                        let distance = result.distance;
                        
                        if (label !== "unknown" && distance < 0.6 && !isProcessing) {
                            isProcessing = true; // Increased distance threshold for better recognition
                            updateStatus('Wajah dikenali! Memproses absen...', 'processing');
                            showLoading();

                            let imageURL = canvas.toDataURL();
                            var canvas2 = document.createElement('canvas');
                            canvas2.width = 600;
                            canvas2.height = 600;
                            var ctx = canvas2.getContext('2d');
                            ctx.drawImage(video, 0, 0, 600, 600);
                            var new_image_url = canvas2.toDataURL();
                            var img = document.createElement('img');
                            img.src = new_image_url;
                            let lat = $('#lat').val();
                            let long = $('#long').val();
                            
                            $.ajax({
                                type: 'POST',
                                url: "{{ url('/presensi/store') }}",
                                data: { 
                                    username: label, 
                                    image: img.src, 
                                    lat: lat, 
                                    long: long,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                cache: false,
                                success: function(msg) {
                                    hideLoading();
                                    // isProcessing = false;
                                    let message = '';
                                    let icon = '';
                                    switch (msg) {
                                        case 'masuk':
                                            message = 'Absen masuk berhasil dicatat!';
                                            icon = 'success';
                                            updateStatus('Absen masuk berhasil!', 'success');
                                            break;
                                        case 'outlocation':
                                            message = 'Anda berada di luar radius kantor';
                                            icon = 'error';
                                            updateStatus('Di luar radius kantor', 'error');
                                            break;
                                        case 'selesai':
                                            message = 'Anda sudah melakukan absen masuk hari ini';
                                            icon = 'warning';
                                            updateStatus('Sudah absen hari ini', 'error');
                                            break;
                                        case 'noMs':
                                            message = 'Hubungi Admin untuk mengatur shift Anda';
                                            icon = 'error';
                                            updateStatus('Shift belum diatur', 'error');
                                            break;
                                        default:
                                            message = 'Tidak ada data user terdaftar';
                                            icon = 'error';
                                            updateStatus('User tidak terdaftar', 'error');
                                    }
                                    Swal.fire({
                                        icon: icon,
                                        title: icon === 'success' ? 'Berhasil!' : 'Perhatian',
                                        text: message,
                                        timer: 3000,
                                        showConfirmButton: false
                                    });
                                    setTimeout(() => {
                                        updateStatus('Sistem siap! Arahkan wajah ke kamera', 'info');
                                    }, 3000);
                                },
                                error: function(data) {
                                    hideLoading();
                                    updateStatus('Terjadi kesalahan sistem', 'error');
                                    console.error('Error:', data);
                                }
                            });
                        } else if (detections.length > 0) {
                            updateStatus(`Wajah terdeteksi, mengenali... (${detections.length} wajah)`, 'processing');
                        } else {
                            updateStatus('Tidak ada wajah terdeteksi', 'info');
                        }
                    });
                }

                setTimeout(() => onPlay(), 500); // Reduced interval for more responsive detection
            }

            document.getElementById("captureBtn").addEventListener("click", async function () {

                if(isProcessing) return;
                isProcessing = true;

                if (!faceMatcher) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sistem belum siap',
                        text: 'Model wajah masih dimuat'
                    });
                    return;
                }

                updateStatus('Memproses foto...', 'processing');
                showLoading();

                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                if (detections.length === 0) {
                    hideLoading();
                    updateStatus('Wajah tidak terdeteksi', 'error');
                    Swal.fire({
                        icon: 'error',
                        title: 'Wajah tidak terdeteksi',
                        text: 'Pastikan wajah terlihat jelas'
                    });
                    return;
                }

                const result = faceMatcher.findBestMatch(detections[0].descriptor);
                let label = result.label;

                if (label === "unknown") {
                    hideLoading();
                    updateStatus('Wajah tidak dikenal', 'error');
                    Swal.fire({
                        icon: 'error',
                        title: 'Wajah tidak dikenali'
                    });
                    return;
                }

                // capture frame dari video
                var canvas2 = document.createElement('canvas');
                canvas2.width = 600;
                canvas2.height = 600;
                var ctx2 = canvas2.getContext('2d');
                ctx2.drawImage(video, 0, 0, 600, 600);

                var image = canvas2.toDataURL();

                let lat = $('#lat').val();
                let long = $('#long').val();

                $.ajax({
                    type: 'POST',
                    url: "{{ url('/presensi/store') }}",
                    data: {
                        username: label,
                        image: image,
                        lat: lat,
                        long: long,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(msg) {
                        hideLoading();
                        isProcessing = false;

                        Swal.fire({
                            icon: 'success',
                            title: 'Absen berhasil',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        updateStatus('Absen berhasil!', 'success');
                    },
                    error: function() {
                        hideLoading();
                        isProcessing = false;
                        updateStatus('Terjadi kesalahan', 'error');
                    }
                });

            });

        </script>
    @endpush
@endsection
