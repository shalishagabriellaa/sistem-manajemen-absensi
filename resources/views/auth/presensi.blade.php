@extends('templates.login')
@section('container')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

:root {
    --dash-blue:    #3b4cca;
    --dash-blue-dk: #2d3db4;
    --dash-blue-lt: #5c6ed4;

    --blue-50:   #eef0fb;
    --blue-100:  #d5d9f5;
    --blue-200:  #adb5eb;
    --blue-400:  #6e80df;
    --blue-500:  #4f63d8;
    --blue-600:  #3b4cca;
    --blue-700:  #2d3db4;
    --blue-800:  #1e2d8a;
    --indigo-500:#5b5fce;
    --indigo-600:#4f46e5;

    --slate-50:  #f8fafc;
    --slate-100: #f1f5f9;
    --slate-200: #e2e8f0;
    --slate-300: #cbd5e1;
    --slate-400: #94a3b8;
    --slate-500: #64748b;
    --slate-600: #475569;
    --slate-700: #334155;
    --slate-800: #1e293b;
    --slate-900: #0f172a;

    --accent-50:  #eef0fb;
    --accent-100: #d5d9f5;
    --accent-200: #adb5eb;
    --accent-500: #4f63d8;
    --accent-600: #3b4cca;
    --accent-700: #2d3db4;

    --red-400:   #f87171;
    --red-500:   #ef4444;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body {
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    overflow-x: hidden;
}

/* ══════════════════════════════
   ANIMATED BACKGROUND
══════════════════════════════ */
.aurora-bg {
    position: fixed;
    inset: 0;
    overflow: hidden;
    background:
        radial-gradient(circle at 10% 10%, rgba(59,76,202,0.12), transparent 40%),
        radial-gradient(circle at 90% 90%, rgba(59,76,202,0.08), transparent 40%),
        linear-gradient(180deg, #f6f8ff 0%, #eef2ff 100%);
}
.aurora-bg::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 20% 10%,  rgba(59,76,202,0.60) 0%, transparent 60%),
        radial-gradient(ellipse 60% 70% at 85% 80%,  rgba(79,70,229,0.50) 0%, transparent 60%),
        radial-gradient(ellipse 50% 50% at 55% 40%,  rgba(45,61,180,0.22) 0%, transparent 55%);
}
.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.35;
    pointer-events: none;
    will-change: transform;
}
.orb-a {
    width: 700px; height: 700px;
    background: radial-gradient(circle, rgba(59,76,202,0.25) 0%, transparent 70%);
    top: -200px; left: -180px;
    animation: orb-move-a 20s ease-in-out infinite alternate;
}
.orb-b {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(99,102,241,0.22) 0%, transparent 70%);
    bottom: -180px; right: -160px;
    animation: orb-move-b 25s ease-in-out infinite alternate;
}
.orb-c {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(45,61,180,0.18) 0%, transparent 70%);
    top: 45%; left: 40%;
    transform: translate(-50%,-50%);
    animation: orb-move-c 30s ease-in-out infinite alternate;
}
@keyframes orb-move-a {
    0%   { transform: translate(0,0) scale(1); }
    100% { transform: translate(100px,80px) scale(1.15); }
}
@keyframes orb-move-b {
    0%   { transform: translate(0,0) scale(1); }
    100% { transform: translate(-80px,-100px) scale(1.1); }
}
@keyframes orb-move-c {
    0%   { transform: translate(-50%,-50%) scale(1); }
    100% { transform: translate(-45%,-55%) scale(1.2); }
}
.arc-ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.07);
    pointer-events: none;
    animation: ring-pulse 8s ease-in-out infinite alternate;
}
.arc-1 { width:800px; height:800px; top:-300px;  right:-250px; animation-delay:0s; }
.arc-2 { width:600px; height:600px; top:-200px;  right:-150px; animation-delay:1.5s; }
.arc-3 { width:400px; height:400px; top:-100px;  right:-50px;  animation-delay:3s; }
.arc-4 { width:700px; height:700px; bottom:-280px; left:-220px; animation-delay:2s; }
.arc-5 { width:500px; height:500px; bottom:-180px; left:-120px; animation-delay:4s; }
@keyframes ring-pulse {
    0%   { opacity:.5;  transform:scale(1); }
    100% { opacity:1;   transform:scale(1.04); }
}

/* ══════════════════════════════
   PAGE LAYOUT
══════════════════════════════ */
.page-wrap {
    position: relative; z-index: 1;
    min-height: 100vh;
    display: flex; align-items: center; justify-content: center;
    padding: 2rem 1.5rem;
}
.card-outer {
    width: 100%;
    max-width: 820px;
    display: flex;
    min-height: 540px;
    border-radius: 24px; overflow: hidden;
    background: white;
    border: 1px solid rgba(226,232,240,0.8);
    box-shadow:
        0 24px 64px rgba(0,0,0,0.10),
        0 4px 16px rgba(0,0,0,0.04);
}

/* ══════════════════════════════
   RIGHT FACE PANEL
══════════════════════════════ */
.form-panel {
    flex: 1;
    background: #ffffff;
    display: flex; align-items: center; justify-content: center;
    padding: 1.75rem 2rem;
    overflow-y: auto;
    position: relative;
}
.form-panel::before {
    content: '';
    position: absolute; top: 0; right: 0;
    width: 200px; height: 200px;
    background: radial-gradient(ellipse at top right, rgba(189,198,245,0.35) 0%, transparent 70%);
    pointer-events: none;
}
.fp-inner {
    width: 100%; max-width: 400px;
    position: relative; z-index: 1;
}

/* Title */
.fp-title {
    font-size: clamp(1.25rem, 2.5vw, 1.5rem);
    font-weight: 800;
    color: var(--slate-900);
    letter-spacing: -.025em;
    line-height: 1.2;
    margin-bottom: .375rem;
}
.fp-sub {
    font-size: .875rem;
    color: var(--slate-400);
    margin-bottom: 1.25rem;
    line-height: 1.6;
}

/* Instructions box */
.instructions-box {
    background: var(--accent-50);
    border: 1.5px solid var(--accent-200);
    border-radius: 10px;
    padding: .875rem 1rem;
    margin-bottom: 1rem;
}
.instructions-title {
    font-size: .8125rem; font-weight: 700;
    color: var(--accent-700);
    display: flex; align-items: center; gap: .375rem;
    margin-bottom: .5rem;
}
.instructions-list {
    list-style: none; padding: 0; margin: 0;
    display: flex; flex-direction: column; gap: .3rem;
}
.instructions-item {
    font-size: .8rem; color: var(--accent-700);
    display: flex; align-items: flex-start; gap: .5rem;
    line-height: 1.4;
}
.instructions-item i { font-size: .5rem; margin-top: .35rem; flex-shrink: 0; }

/* Status indicator */
.status-box {
    background: var(--accent-50);
    border: 1.5px solid var(--accent-200);
    border-radius: 10px;
    padding: .625rem 1rem;
    margin-bottom: .875rem;
    display: flex; align-items: center; gap: .5rem;
    transition: all .25s;
}
.status-box i { font-size: .8125rem; color: var(--accent-600); flex-shrink: 0; }
.status-text {
    font-size: .8125rem; font-weight: 600;
    color: var(--accent-700);
}

/* Location box */
.location-box {
    background: var(--blue-50);
    border: 1.5px solid var(--blue-200);
    border-radius: 10px;
    padding: .5625rem 1rem;
    margin-bottom: .875rem;
    display: flex; align-items: center; gap: .5rem;
    font-size: .8rem; font-weight: 600;
    color: var(--blue-600);
    transition: all .25s;
}
.location-box i { flex-shrink: 0; }

/* Video container */
.video-wrap {
    max-height: 300px;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background: #000;
    margin-bottom: 1rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    border: 2px solid var(--slate-200);
}
#video {
    width: 100%; height: auto;
    display: block; border-radius: 10px;
}
canvas {
    position: absolute; top: 0; left: 0;
    width: 100%; height: 100%;
    pointer-events: none; border-radius: 10px;
}

/* Buttons */
.btn-capture {
    width: 100%;
    padding: .9375rem 1.5rem;
    font-size: .9375rem; font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: white !important;
    background: linear-gradient(135deg, var(--dash-blue) 0%, var(--dash-blue-dk) 100%);
    box-shadow: 0 4px 16px rgba(59,76,202,.35);
    border: none; border-radius: 10px;
    cursor: pointer; transition: all .22s;
    margin-bottom: .625rem;
    display: flex; align-items: center; justify-content: center; gap: .4rem;
}
.btn-capture:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(59,76,202,.45);
    color: white !important;
}
.btn-capture:active { transform: none; }

.btn-back {
    width: 100%;
    padding: .8125rem 1.5rem;
    font-size: .875rem; font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--slate-700) !important;
    background: var(--slate-50);
    border: 1.5px solid var(--slate-200);
    border-radius: 10px;
    cursor: pointer; transition: all .22s;
    display: flex; align-items: center; justify-content: center; gap: .4rem;
    text-decoration: none;
}
.btn-back:hover {
    background: var(--slate-700);
    color: white !important;
    border-color: var(--slate-700);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,.12);
    text-decoration: none;
}

/* Footer */
.fp-footer {
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1px solid var(--slate-100);
    display: flex; align-items: center; gap: .5rem;
}
.fp-footer-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #3b4cca; flex-shrink: 0;
    box-shadow: 0 0 0 3px rgba(59,76,202,.15);
}
.fp-footer-text {
    font-size: .6875rem;
    color: var(--slate-400);
    line-height: 1.4;
}
.fp-footer-text strong {
    color: var(--slate-600); font-weight: 600;
}

/* Loading overlay */
.loading-overlay {
    position: fixed; inset: 0;
    background: rgba(15,23,42,0.55);
    display: none; align-items: center; justify-content: center;
    z-index: 1000;
    backdrop-filter: blur(4px);
}
.loading-content {
    background: white;
    padding: 2rem 2.5rem;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 24px 64px rgba(0,0,0,0.2);
    border: 1px solid var(--slate-200);
}
.loading-content p {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .9375rem; font-weight: 600;
    color: var(--slate-700);
    margin-top: .75rem;
}
.loading-content .spinner-border {
    color: var(--blue-600) !important;
}

/* ══════════════════════════════
   RESPONSIVE
══════════════════════════════ */
@media (max-width: 860px) {
    .info-panel { display: none; }
    .card-outer {
        max-width: 460px; min-height: unset;
        border-radius: 20px; background: transparent;
        border: none; box-shadow: none;
    }
    .form-panel { border-radius: 20px; box-shadow: 0 24px 56px rgba(0,0,0,.28); }
}
@media (max-width: 480px) {
    .page-wrap { padding: 1.25rem 1rem; }
    .form-panel { padding: 1.75rem 1.25rem; }
}
@media (max-height: 700px) and (orientation: landscape) {
    .page-wrap { padding: 1rem; }
    .form-panel { padding: 1.25rem 1.75rem; align-items: flex-start; }
    .fp-sub { margin-bottom: .875rem; }
    .fp-footer { display: none; }
}
</style>

{{-- BACKGROUND --}}
<div class="aurora-bg">
    <div class="orb orb-a"></div>
    <div class="orb orb-b"></div>
    <div class="orb orb-c"></div>
    <div class="arc-ring arc-1"></div>
    <div class="arc-ring arc-2"></div>
    <div class="arc-ring arc-3"></div>
    <div class="arc-ring arc-4"></div>
    <div class="arc-ring arc-5"></div>
</div>

{{-- PAGE --}}
<div class="page-wrap">
    <div class="card-outer">

        {{-- ── RIGHT FACE PANEL ── --}}
        <div class="form-panel">
            <div class="fp-inner">

                <h1 class="fp-title">
                    <i class="fas fa-user-circle" style="color:#3b4cca; margin-right:.375rem;"></i>
                    {{ $title }}
                </h1>
                <p class="fp-sub">Posisikan wajah Anda di dalam frame untuk absen masuk.</p>

                <div class="instructions-box">
                    <div class="instructions-title">
                        <i class="fas fa-info-circle"></i>
                        Cara Penggunaan
                    </div>
                    <ul class="instructions-list">
                        <li class="instructions-item">
                            <i class="fas fa-circle"></i>
                            Pastikan wajah terlihat jelas dalam frame
                        </li>
                        <li class="instructions-item">
                            <i class="fas fa-circle"></i>
                            Jaga jarak yang tepat dengan kamera
                        </li>
                        <li class="instructions-item">
                            <i class="fas fa-circle"></i>
                            Tunggu hingga sistem mengenali wajah Anda
                        </li>
                    </ul>
                </div>

                <div class="status-box" id="status-box">
                    <i class="fas fa-camera" id="status-icon"></i>
                    <span class="status-text" id="status-message">Memuat sistem pengenalan wajah...</span>
                </div>

                <div class="location-box" id="location-box">
                    <i class="fas fa-map-marker-alt"></i>
                    <span id="location-text">Mendeteksi lokasi Anda...</span>
                </div>

                <div class="video-wrap">
                    <video id="video" autoplay playsinline></video>
                </div>

                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="long" id="long">

                <button type="button" class="btn-capture" id="captureBtn">
                    <i class="fas fa-camera"></i>
                    Ambil Foto &amp; Absen
                </button>

                <a href="{{ url('/') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Login
                </a>

                <div class="fp-footer">
                    <div class="fp-footer-dot"></div>
                    <div class="fp-footer-text">
                        <strong>Metech</strong> - Sistem aman &amp; terenkripsi
                        &copy; {{ date('Y') }} Metech
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner-border" role="status" style="width:2.5rem;height:2.5rem;">
            <span class="sr-only">Loading...</span>
        </div>
        <p>Memproses pengenalan wajah...</p>
    </div>
</div>

@push('script')
    <script src="{{ url('/face/dist/face-api.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        /* ── Live clock ── */
        (function tick() {
            const n      = new Date();
            const hm     = n.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            const sec    = String(n.getSeconds()).padStart(2, '0');
            const dateStr= n.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const dayStr = n.toLocaleDateString('id-ID', { weekday: 'long' }).toUpperCase();
            const hme = document.getElementById('clock-hm');
            const sce = document.getElementById('clock-sec');
            const dte = document.getElementById('clock-date-full');
            const dye = document.getElementById('clock-day');
            if (hme) hme.textContent = hm;
            if (sce) sce.textContent = sec;
            if (dte) dte.textContent = dateStr;
            if (dye) dye.textContent = dayStr;
            setTimeout(tick, 1000);
        })();

        /* ── Status updater ── */
        function updateStatus(message, type = 'info') {
            const statusMsg  = document.getElementById('status-message');
            const statusBox  = document.getElementById('status-box');
            const statusIcon = document.getElementById('status-icon');

            const icons = {
                'info':       'fas fa-camera',
                'success':    'fas fa-check-circle',
                'error':      'fas fa-exclamation-triangle',
                'processing': 'fas fa-spinner fa-spin',
                'loading':    'fas fa-cog fa-spin'
            };
            const colors = {
                'info':       { bg: '#ecfdf5', border: '#a7f3d0', text: '#047857', icon: '#059669' },
                'success':    { bg: '#ecfdf5', border: '#a7f3d0', text: '#047857', icon: '#059669' },
                'error':      { bg: '#fef2f2', border: '#fca5a5', text: '#b91c1c', icon: '#ef4444' },
                'processing': { bg: '#eff6ff', border: '#bfdbfe', text: '#1d4ed8', icon: '#3b82f6' },
                'loading':    { bg: '#f8fafc', border: '#e2e8f0', text: '#475569', icon: '#64748b' }
            };

            const c = colors[type] || colors['info'];
            statusMsg.textContent = message;
            statusIcon.className  = icons[type] || icons['info'];
            statusBox.style.background   = c.bg;
            statusBox.style.borderColor  = c.border;
            statusMsg.style.color        = c.text;
            statusIcon.style.color       = c.icon;
        }

        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }
        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        /* ── Geolocation ── */
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showLocationError);
            } else {
                document.getElementById('location-text').textContent = 'Geolocation tidak didukung browser ini';
            }
        }
        function showPosition(position) {
            $('#lat').val(position.coords.latitude);
            $('#long').val(position.coords.longitude);
            document.getElementById('location-text').textContent = 'Lokasi terdeteksi ✓';
        }
        function showLocationError() {
            document.getElementById('location-text').textContent = 'Gagal mendeteksi lokasi';
        }
        setInterval(getLocation, 5000);
        getLocation();

        /* ── Face API setup ── */
        let faceMatcher  = undefined;
        let video        = document.getElementById("video");
        let canvas       = document.createElement("canvas");
        let ctx          = canvas.getContext("2d");
        let isProcessing = false;

        document.querySelector('.video-wrap').appendChild(canvas);

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
        };

        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri("{{ url('/face/weights') }}"),
            faceapi.nets.faceLandmark68Net.loadFromUri("{{ url('/face/weights') }}"),
            faceapi.nets.faceRecognitionNet.loadFromUri("{{ url('/face/weights') }}")
        ]).then(startStream);

        video.onloadedmetadata = () => {
            canvas.width  = video.videoWidth;
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
                    const content = (typeof data === 'string') ? JSON.parse(data) : data;
                    if (!content || content.length === 0) {
                        updateStatus('Tidak ada data wajah terdaftar', 'error');
                        return;
                    }
                    const labeledDescriptors = content.map(person => {
                        const descs = person.descriptors.map(d => new Float32Array(d));
                        return new faceapi.LabeledFaceDescriptors(person.label, descs);
                    });
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

        async function onPlay() {
            if (faceMatcher) {
                const displaySize = { width: video.videoWidth, height: video.videoHeight };
                faceapi.matchDimensions(canvas, displaySize);

                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions({
                    inputSize: 416,
                    scoreThreshold: 0.3
                }))
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));

                ctx.clearRect(0, 0, canvas.width, canvas.height);

                results.forEach((result, i) => {
                    const box     = resizedDetections[i].detection.box;
                    const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() });
                    drawBox.draw(canvas);

                    let label    = result.label;
                    let distance = result.distance;

                    if (label !== "unknown" && distance < 0.6 && !isProcessing) {
                        isProcessing = true;
                        updateStatus('Wajah dikenali! Memproses absen...', 'processing');
                        showLoading();

                        var canvas2 = document.createElement('canvas');
                        canvas2.width  = 600;
                        canvas2.height = 600;
                        var ctx2 = canvas2.getContext('2d');
                        ctx2.drawImage(video, 0, 0, 600, 600);
                        var new_image_url = canvas2.toDataURL();
                        var img = document.createElement('img');
                        img.src = new_image_url;

                        let lat  = $('#lat').val();
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
                                let message = '';
                                let icon    = '';
                                switch (msg) {
                                    case 'masuk':
                                        message = 'Absen masuk berhasil dicatat!';
                                        icon    = 'success';
                                        updateStatus('Absen masuk berhasil!', 'success');
                                        break;
                                    case 'outlocation':
                                        message = 'Anda berada di luar radius kantor';
                                        icon    = 'error';
                                        updateStatus('Di luar radius kantor', 'error');
                                        break;
                                    case 'selesai':
                                        message = 'Anda sudah melakukan absen masuk hari ini';
                                        icon    = 'warning';
                                        updateStatus('Sudah absen hari ini', 'error');
                                        break;
                                    case 'noMs':
                                        message = 'Hubungi Admin untuk mengatur shift Anda';
                                        icon    = 'error';
                                        updateStatus('Shift belum diatur', 'error');
                                        break;
                                    default:
                                        message = 'Tidak ada data user terdaftar';
                                        icon    = 'error';
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
            setTimeout(() => onPlay(), 500);
        }

        /* ── Manual capture button ── */
        document.getElementById("captureBtn").addEventListener("click", async function () {
            if (isProcessing) return;
            isProcessing = true;

            if (!faceMatcher) {
                Swal.fire({
                    icon: 'error',
                    title: 'Sistem belum siap',
                    text: 'Model wajah masih dimuat'
                });
                isProcessing = false;
                return;
            }

            updateStatus('Memproses foto...', 'processing');
            showLoading();

            const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptors();

            if (detections.length === 0) {
                hideLoading();
                isProcessing = false;
                updateStatus('Wajah tidak terdeteksi', 'error');
                Swal.fire({
                    icon: 'error',
                    title: 'Wajah tidak terdeteksi',
                    text: 'Pastikan wajah terlihat jelas'
                });
                return;
            }

            const result = faceMatcher.findBestMatch(detections[0].descriptor);
            let label    = result.label;

            if (label === "unknown") {
                hideLoading();
                isProcessing = false;
                updateStatus('Wajah tidak dikenal', 'error');
                Swal.fire({
                    icon: 'error',
                    title: 'Wajah tidak dikenali'
                });
                return;
            }

            var canvas2 = document.createElement('canvas');
            canvas2.width  = 600;
            canvas2.height = 600;
            var ctx2 = canvas2.getContext('2d');
            ctx2.drawImage(video, 0, 0, 600, 600);
            var image = canvas2.toDataURL();

            let lat  = $('#lat').val();
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