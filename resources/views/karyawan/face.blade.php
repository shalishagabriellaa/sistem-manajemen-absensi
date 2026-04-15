@extends('templates.dashboard')
@section('isi')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    * { font-family: 'Inter', sans-serif; }

    .face-card {
        background: #fff; border-radius: 16px;
        padding: 1.5rem; border: 1px solid #f0f0f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .video-container {
        position: relative; border-radius: 12px;
        overflow: hidden; background: #000;
        margin-bottom: 1rem;
    }
    #video { width: 100%; height: auto; display: block; }
    #overlay {
        position: absolute; top: 0; left: 0;
        width: 100%; height: 100%; pointer-events: none;
    }
    .status-box {
        border-radius: 10px; padding: 0.65rem 1rem;
        margin-bottom: 1rem; font-size: 0.875rem;
        display: flex; align-items: center; gap: 8px;
        transition: all 0.3s;
    }
    .stats-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 8px; margin-bottom: 1rem;
    }
    .stat-box {
        background: #f9fafb; border-radius: 10px;
        padding: 10px; text-align: center;
        border: 1px solid #f0f0f0;
    }
    .stat-number { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 0; }
    .stat-label  { font-size: 0.75rem; color: #6b7280; margin: 0; }
    .progress-wrap {
        background: #f0f0f0; border-radius: 99px;
        height: 6px; margin-bottom: 1rem; overflow: hidden;
    }
    .progress-fill {
        height: 100%; border-radius: 99px;
        background: linear-gradient(90deg, #10b981, #059669);
        transition: width 0.4s ease; width: 0%;
    }
    .sample-thumbs {
        display: flex; gap: 6px; flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    .thumb {
        width: 56px; height: 56px; border-radius: 8px;
        border: 2px dashed #d1d5db; object-fit: cover;
        background: #f9fafb;
    }
    .thumb.filled { border: 2px solid #10b981; }
    .btn-capture {
        padding: 0.65rem 1.25rem; font-size: 0.875rem; font-weight: 600;
        color: #fff; background: linear-gradient(135deg,#3b82f6,#2563eb);
        border: none; border-radius: 10px; cursor: pointer; transition: all .2s;
    }
    .btn-capture:disabled { opacity:.5; cursor:not-allowed; }
    .btn-save {
        padding: 0.65rem 1.25rem; font-size: 0.875rem; font-weight: 600;
        color: #fff; background: linear-gradient(135deg,#10b981,#059669);
        border: none; border-radius: 10px; cursor: pointer; transition: all .2s;
    }
    .btn-save:disabled { opacity:.5; cursor:not-allowed; }
</style>

<div class="row">
    <div class="col-md-12 m project-list">
        <div class="card">
            <div class="row">
                <div class="col-md-6 p-0 d-flex mt-2">
                    <h4>{{ $title }} — {{ $karyawan->name }}</h4>
                </div>
                <div class="col-md-6 p-0">
                    <a href="{{ url('/pegawai') }}" class="btn btn-danger btn-sm ms-2">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card p-3">
            <div class="row">
                {{-- Kolom kiri: kamera --}}
                <div class="col-md-6">
                    <div class="face-card">
                        <div class="status-box" id="statusBox"
                             style="background:#eff6ff;border:1px solid #bfdbfe;color:#3b82f6;">
                            <i class="fas fa-cog fa-spin" id="statusIcon"></i>
                            <span id="statusText">Memuat model AI...</span>
                        </div>

                        <div class="video-container">
                            <video id="video" autoplay playsinline></video>
                            <canvas id="overlay"></canvas>
                        </div>

                        <div class="sample-thumbs" id="sampleThumbs">
                            @for ($i = 0; $i < 5; $i++)
                                <img class="thumb" id="thumb-{{ $i }}"
                                     src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">
                            @endfor
                        </div>

                        <div class="stats-grid">
                            <div class="stat-box">
                                <p class="stat-number" id="countSamples">0</p>
                                <p class="stat-label">Sampel</p>
                            </div>
                            <div class="stat-box">
                                <p class="stat-number">5</p>
                                <p class="stat-label">Target</p>
                            </div>
                            <div class="stat-box">
                                <p class="stat-number" id="progressPct">0%</p>
                                <p class="stat-label">Progress</p>
                            </div>
                        </div>

                        <div class="progress-wrap">
                            <div class="progress-fill" id="progressBar"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn-capture" id="btnCapture" disabled>
                                <i class="fas fa-camera me-1"></i> Ambil Sampel
                            </button>
                            <button class="btn-save ms-2" id="btnSave" disabled>
                                <i class="fas fa-save me-1"></i> Simpan Wajah
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Kolom kanan: info karyawan --}}
                <div class="col-md-6">
                    <div class="face-card">
                        <h5 class="mb-3">Informasi Karyawan</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td style="width:40%;color:#6b7280;">Nama</td>
                                <td><strong>{{ $karyawan->name }}</strong></td>
                            </tr>
                            <tr>
                                <td style="color:#6b7280;">Username</td>
                                <td>{{ $karyawan->username }}</td>
                            </tr>
                            <tr>
                                <td style="color:#6b7280;">Email</td>
                                <td>{{ $karyawan->email }}</td>
                            </tr>
                            <tr>
                                <td style="color:#6b7280;">Divisi</td>
                                <td>{{ $karyawan->Jabatan->nama_jabatan ?? '-' }}</td>
                            </tr>
                        </table>

                        <hr>
                        <h6 class="mb-2">Status Wajah Terdaftar</h6>
                        <div id="faceStatus" class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Memeriksa data wajah...
                        </div>

                        <div class="mt-3">
                            <h6 class="mb-2">Petunjuk:</h6>
                            <ul style="font-size:0.875rem;color:#6b7280;padding-left:1.2rem;">
                                <li>Pastikan hanya 1 wajah dalam frame</li>
                                <li>Klik <strong>Ambil Sampel</strong> sebanyak 5 kali</li>
                                <li>Variasikan sedikit posisi wajah tiap sampel</li>
                                <li>Klik <strong>Simpan Wajah</strong> setelah 5 sampel terkumpul</li>
                                <li>Data wajah lama akan digantikan data baru</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script src="{{ url('/face/dist/face-api.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
 // DEBUG SEMENTARA - hapus setelah selesai
    $.post('/test-descrip', {_token: '{{ csrf_token() }}'}, function(res) {
        console.log('DEBUG:', res);
    });
    
    const TARGET   = 5;
    const USERNAME = "{{ $karyawan->username }}";
    const USER_ID  = {{ $karyawan->id }};

    let descriptors = [];
    let video       = document.getElementById('video');
    let overlay     = document.getElementById('overlay');
    let ctx         = overlay.getContext('2d');
    let modelsReady = false;

    // ── Cek apakah wajah sudah terdaftar ──────────────────
    $.ajax({
        url: "{{ url('/ajaxGetNeural') }}",
        method: 'GET',
        success: function(data) {
            try {
                const neural = JSON.parse(data);
                const found  = neural.find(n => n.label === USERNAME);
                const el     = document.getElementById('faceStatus');
                if (found) {
                    el.className = 'alert alert-success';
                    el.innerHTML = '<i class="fas fa-check-circle me-1"></i>'
                        + 'Wajah sudah terdaftar dengan <strong>'
                        + found.descriptors.length + ' sampel</strong>. '
                        + 'Ambil sampel baru untuk memperbarui.';
                } else {
                    el.className = 'alert alert-warning';
                    el.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>'
                        + 'Wajah belum terdaftar. Silakan ambil sampel.';
                }
            } catch(e) {
                document.getElementById('faceStatus').innerHTML
                    = 'Gagal membaca data wajah.';
            }
        }
    });

    // ── Status helper ──────────────────────────────────────
    function setStatus(msg, type) {
        const cfg = {
            info:       { bg:'#f0fdf4', border:'#bbf7d0', color:'#059669', icon:'fas fa-camera' },
            loading:    { bg:'#eff6ff', border:'#bfdbfe', color:'#3b82f6', icon:'fas fa-cog fa-spin' },
            success:    { bg:'#f0fdf4', border:'#bbf7d0', color:'#059669', icon:'fas fa-check-circle' },
            error:      { bg:'#fef2f2', border:'#fecaca', color:'#dc2626', icon:'fas fa-exclamation-triangle' },
            processing: { bg:'#eff6ff', border:'#bfdbfe', color:'#3b82f6', icon:'fas fa-spinner fa-spin' },
        };
        const c = cfg[type] || cfg.info;
        const box = document.getElementById('statusBox');
        box.style.cssText = `background:${c.bg};border:1px solid ${c.border};color:${c.color}`;
        document.getElementById('statusIcon').className = c.icon;
        document.getElementById('statusText').textContent = msg;
    }

    // ── Progress update ────────────────────────────────────
    function updateProgress() {
        const n   = descriptors.length;
        const pct = Math.round((n / TARGET) * 100);
        document.getElementById('countSamples').textContent = n;
        document.getElementById('progressPct').textContent  = pct + '%';
        document.getElementById('progressBar').style.width  = pct + '%';
        document.getElementById('btnSave').disabled = n < TARGET;
    }

    // ── Load models & start stream ─────────────────────────
    Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri("{{ url('/face/weights') }}"),
        faceapi.nets.faceLandmark68Net.loadFromUri("{{ url('/face/weights') }}"),
        faceapi.nets.faceRecognitionNet.loadFromUri("{{ url('/face/weights') }}")
    ]).then(async () => {
        setStatus('Mengakses kamera...', 'loading');
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode:'user', width:{ideal:640}, height:{ideal:480} },
                audio: false
            });
            video.srcObject = stream;
        } catch(e) {
            setStatus('Gagal mengakses kamera', 'error');
        }
    });

    video.onloadedmetadata = () => {
        overlay.width  = video.videoWidth;
        overlay.height = video.videoHeight;
        modelsReady    = true;
        document.getElementById('btnCapture').disabled = false;
        setStatus('Kamera siap! Posisikan wajah dalam frame', 'info');
        drawLoop();
    };

    // ── Live detection loop ────────────────────────────────
    async function drawLoop() {
        if (!modelsReady) { setTimeout(drawLoop, 300); return; }

        const sz = { width: video.videoWidth, height: video.videoHeight };
        faceapi.matchDimensions(overlay, sz);

        const dets = await faceapi
            .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions({ inputSize:416, scoreThreshold:0.3 }))
            .withFaceLandmarks();

        ctx.clearRect(0, 0, overlay.width, overlay.height);
        faceapi.draw.drawDetections(overlay, faceapi.resizeResults(dets, sz));
        faceapi.draw.drawFaceLandmarks(overlay, faceapi.resizeResults(dets, sz));

        if (dets.length === 0)      setStatus('Tidak ada wajah terdeteksi', 'error');
        else if (dets.length === 1) setStatus('Wajah terdeteksi! Siap ambil sampel', 'info');
        else                        setStatus('Lebih dari 1 wajah terdeteksi', 'error');

        setTimeout(drawLoop, 300);
    }

    // ── Ambil sampel ───────────────────────────────────────
    document.getElementById('btnCapture').addEventListener('click', async () => {
        if (!modelsReady) return;
        if (descriptors.length >= TARGET) {
            setStatus('Sampel sudah cukup, silakan simpan', 'success'); return;
        }

        setStatus('Mengambil sampel...', 'processing');

        const dets = await faceapi
            .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions({ inputSize:416, scoreThreshold:0.3 }))
            .withFaceLandmarks()
            .withFaceDescriptors();

        if (dets.length === 0) {
            setStatus('Wajah tidak terdeteksi, coba lagi', 'error'); return;
        }
        if (dets.length > 1) {
            setStatus('Pastikan hanya 1 wajah dalam frame', 'error'); return;
        }

        // Simpan descriptor sebagai plain array (konsisten dengan neural.json yang ada)
        descriptors.push(Array.from(dets[0].descriptor));

        // Preview thumbnail
        const idx = descriptors.length - 1;
        const tmp = document.createElement('canvas');
        tmp.width = 56; tmp.height = 56;
        tmp.getContext('2d').drawImage(video, 0, 0, 56, 56);
        const thumb = document.getElementById('thumb-' + idx);
        thumb.src = tmp.toDataURL();
        thumb.classList.add('filled');

        updateProgress();

        if (descriptors.length >= TARGET) {
            setStatus('Semua sampel terkumpul! Klik Simpan Wajah', 'success');
            document.getElementById('btnCapture').disabled = true;
        } else {
            setStatus(`Sampel ${descriptors.length}/${TARGET} berhasil diambil`, 'success');
        }
    });

    // ── Simpan ke server (ikuti pola ajaxDescrip) ──────────
    document.getElementById('btnSave').addEventListener('click', async () => {
        if (descriptors.length < TARGET) return;

        const { isConfirmed } = await Swal.fire({
            icon: 'question',
            title: 'Simpan Data Wajah?',
            text: `Data wajah "${USERNAME}" akan disimpan dengan ${TARGET} sampel.`,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10b981',
        });
        if (!isConfirmed) return;

        document.getElementById('btnSave').disabled = true;
        setStatus('Menyimpan data wajah...', 'processing');

        // Ambil juga foto untuk disimpan (ikuti pola ajaxPhoto)
        const photoCanvas = document.createElement('canvas');
        photoCanvas.width  = 600;
        photoCanvas.height = 600;
        photoCanvas.getContext('2d').drawImage(video, 0, 0, 600, 600);
        const photoData = photoCanvas.toDataURL();

        // Format myData persis seperti yang dibaca neural.json
        const myData = JSON.stringify({
            label: USERNAME,
            descriptors: descriptors
        });

        // Simpan descriptor — ikuti pola ajaxDescrip
        $.ajax({
            type: 'POST',
            url: "{{ url('/ajaxDescrip') }}",
            data: {
                user_id: USER_ID,
                myData:  myData,
                _token:  $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                // Simpan foto — ikuti pola ajaxPhoto
                $.ajax({
                    type: 'POST',
                    url: "{{ url('/ajaxPhoto') }}",
                    data: {
                        image: photoData,
                        path:  USERNAME,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data wajah berhasil disimpan.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ url('/pegawai') }}";
                        });
                    },
                    error: function() {
                        // Foto gagal disimpan tapi descriptor berhasil — tetap lanjut
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data wajah disimpan (foto tidak tersimpan).',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ url('/pegawai') }}";
                        });
                    }
                });
            },
            error: function() {
                setStatus('Gagal menyimpan data wajah', 'error');
                document.getElementById('btnSave').disabled = false;
            }
        });
    });
</script>
@endpush
@endsection