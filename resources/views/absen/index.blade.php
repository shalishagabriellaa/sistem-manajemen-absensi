@extends('templates.dashboard')
@section('isi')
    @if($shift_karyawan)
        <?php $skid = $shift_karyawan->id ?>
        <?php $sktanggal = $shift_karyawan->tanggal ?>
        <?php $sknamas = $shift_karyawan->Shift->nama_shift  ?>
        <?php $skjamas = $shift_karyawan->Shift->jam_masuk ?>
        <?php $skjamkel = $shift_karyawan->Shift->jam_keluar ?>
        <?php $skjamab = $shift_karyawan->jam_absen ?>
        <?php $skjampul = $shift_karyawan->jam_pulang ?>
        <?php $skstatus = $shift_karyawan->status_absen ?>
        <?php $lock_location = $shift_karyawan->lock_location ?>
    @else
        <?php $skid = "-" ?>
        <?php $sktanggal = "-" ?>
        <?php $sknamas = "-"  ?>
        <?php $skjamas = "-" ?>
        <?php $skjamkel = "-" ?>
        <?php $skjamab = "-" ?>
        <?php $skjampul = "-" ?>
        <?php $skstatus = "-" ?>
        <?php $lock_location = null ?>
    @endif
    <div class="container-fluid">
        <center>
            <p class="p mb-2 text-gray-800">Tanggal Shift : {{ $sktanggal }}</p>
            <p class="p mb-2 text-gray-800">Shift : {{ $sknamas}} ({{ $skjamas }} - {{  $skjamkel }})</p>
        </center>

        <style>
            .jam-digital-malasngoding {
              overflow: hidden;
              float: center;
              width: 100px;
              margin: 2px auto;
              border: 0px solid #efefef;
            }

            .kotak {
              float: left;
              width: 30px;
              height: 30px;
              background-color: #189fff;
            }

            .jam-digital-malasngoding p {
              color: #fff;
              font-size: 16px;
              text-align: center;
              margin-top: 3px;
            }
        </style>

        @php
            // Ambil waktu server (menggunakan timezone dari config Laravel)
            $serverTimeMs = now(config('app.timezone'))->timestamp * 1000;
        @endphp

        <div class="jam-digital-malasngoding">
            <div class="kotak">
              <p id="jam"></p>
            </div>
            <div class="kotak">
              <p id="menit"></p>
            </div>
            <div class="kotak">
              <p id="detik"></p>
            </div>
        </div>

        <script>
            // Gunakan waktu server sebagai sumber utama, bukan jam device
            var serverTimeMs = {{ $serverTimeMs }};
            var serverTimezone = '{{ config("app.timezone") }}';

            function waktu() {
                // Buat object Date dari waktu server saat ini
                var waktu = new Date(serverTimeMs);

                // Format ke jam-menit-detik di timezone server
                var options = {
                    timeZone: serverTimezone,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                };

                var waktuFormatted = waktu.toLocaleTimeString('en-GB', options);
                var parts = waktuFormatted.split(':');

                document.getElementById("jam").innerHTML = parts[0];
                document.getElementById("menit").innerHTML = parts[1];
                document.getElementById("detik").innerHTML = parts[2];

                // Tambah 1 detik untuk pemanggilan berikutnya (simulasi waktu server)
                serverTimeMs += 1000;
            }

            // Jalankan setiap 1 detik
            setInterval(waktu, 1000);

            // Panggil sekali di awal supaya langsung tampil
            waktu();
        </script>
        <br>

        <div class="d-flex justify-content-center">
            <form action="{{ url('/my-location') }}" method="get" id="locationForm">
                @csrf
                <input type="hidden" name="lat" id="lat2">
                <input type="hidden" name="long" id="long2">
                <input type="hidden" name="userid" value="{{ auth()->user()->id }}">
                <button type="submit" class="btn btn-success" id="locationBtn" disabled>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span id="btnText">Mengambil Lokasi...</span>
                </button>
            </form>
        </div>

        @if(!$shift_karyawan)
        <br>
        <div class="col-lg-12">
            <div class="card">
                <div class="p-4">
                    <center>
                        <h2>Hubungi Admin Untuk Input Shift Anda</h2>
                    </center>
                </div>
            </div>
        </div>
        @elseif($skstatus == "Libur")
        <br>
        <div class="col-lg-12">
            <div class="card">
                <div class="p-4">
                    <center>
                        <h2>Hari Ini Anda Libur</h2>
                    </center>
                </div>
            </div>
        </div>
        @elseif($skstatus == "Cuti")
        <br>
        <div class="col-lg-12">
            <div class="card">
                <div class="p-4">
                    <center>
                        <h2>Hari Ini Anda Cuti</h2>
                    </center>
                </div>
            </div>
        </div>
        @else
            @if($skjamab == null)
                <br>
                <div class="col-lg-12">
                    <div class="card">
                        <form method="post" action="{{ url('/absen/masuk/'.$skid) }}" class="p-4">
                            @method('put')
                            @csrf
                            <div class="form-row">
                                <div class="col"></div>
                                <div class="col">
                                    <center>
                                        <h2>Absen Masuk: </h2>
                                        <div class="webcam" id="results"></div>
                                        @if ($lock_location == null)
                                            <div class="form-group">
                                                <label for="keterangan_masuk">Keterangan Masuk</label>
                                                <textarea type="text" class="form-control @error('keterangan_masuk') is-invalid @enderror" id="keterangan_masuk" name="keterangan_masuk" placeholder="Masuk">{{ old('keterangan_masuk', 'Masuk') }}</textarea>
                                                @error('keterangan_masuk')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        @endif
                                    </center>
                                </div>
                                <div class="col">
                                    <input type="hidden" name="jam_absen">
                                    <input type="hidden" name="foto_jam_absen" class="image-tag">
                                    <input type="hidden" name="lat_absen" id="lat">
                                    <input type="hidden" name="long_absen" id="long">
                                    <input type="hidden" name="telat">
                                    <input type="hidden" name="jarak_masuk">
                                    <input type="hidden" name="status_absen">
                                </div>
                            </div>
                            <center>
                                <button type="button" class="btn btn-primary" value="Ambil Foto" id="submitBtnMasuk" onClick="take_snapshot_and_submit(this.form)">Masuk</button>
                            </center>
                            </form>
                    </div>
                </div>

                <script type="text/javascript" src="{{ url('webcamjs/webcam.min.js') }}"></script>
                <script language="JavaScript">
                Webcam.set({
                    width: 320,
                    height: 320,
                    image_format: 'jpeg',
                    jpeg_quality: 90
                });
                Webcam.attach( '.webcam' );
                </script>
                <script language="JavaScript">
                function take_snapshot_and_submit(form) {
                    // Stop location retry when user clicks absen button
                    window.stopLocationRetry();

                    // Disable button to prevent multiple clicks
                    const submitBtn = form.querySelector('#submitBtnMasuk');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengambil foto...';

                    try {
                        // Check if Webcam is available
                        if (typeof Webcam === 'undefined') {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                            alert('Webcam library tidak tersedia. Silakan refresh halaman.');
                            return;
                        }

                        // take snapshot and get image data
                        Webcam.snap( function(data_uri) {
                            try {
                                // Validate that we got a proper data URI
                                if (data_uri && data_uri.startsWith('data:image/')) {
                                    form.querySelector('.image-tag').value = data_uri;
                                    // display results in page
                                    document.getElementById('results').innerHTML =
                                        '<img src="'+data_uri+'" style="max-width: 100%; height: auto;"/>';

                                    // Submit form after photo is captured using AJAX
                                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';

                                    // Prepare form data for AJAX submission
                                    var formData = new FormData(form);
                                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                                    // Submit via AJAX
                                    fetch(form.action, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Show success popup
                                            if (typeof Swal !== 'undefined') {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil!',
                                                    text: data.message,
                                                    confirmButtonText: 'OK',
                                                    timer: 3000,
                                                    timerProgressBar: true
                                                }).then(() => {
                                                    // Reload page after popup
                                                    window.location.reload();
                                                });
                                            } else {
                                                alert(data.message);
                                                window.location.reload();
                                            }
                                        } else {
                                            // Show error popup
                                            if (typeof Swal !== 'undefined') {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Error',
                                                    text: data.message || 'Terjadi kesalahan saat menyimpan data.',
                                                    confirmButtonText: 'OK'
                                                });
                                            } else {
                                                alert(data.message || 'Terjadi kesalahan saat menyimpan data.');
                                            }
                                            submitBtn.disabled = false;
                                            submitBtn.innerHTML = originalText;
                                        }
                                    })
                                    .catch(error => {
                                        console.error('AJAX Error:', error);
                                        if (typeof Swal !== 'undefined') {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.',
                                                confirmButtonText: 'OK'
                                            });
                                        } else {
                                            alert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.');
                                        }
                                        submitBtn.disabled = false;
                                        submitBtn.innerHTML = originalText;
                                    });
                                } else {
                                    // Reset button and show error
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalText;
                                    alert('Gagal mengambil foto. Silakan coba lagi.');
                                }
                            } catch (error) {
                                console.error('Error in photo callback:', error);
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                                alert('Terjadi kesalahan saat memproses foto. Silakan coba lagi.');
                            }
                        });
                    } catch (error) {
                        console.error('Error taking snapshot:', error);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        alert('Gagal mengambil foto. Pastikan kamera berfungsi dengan baik.');
                    }
                }
                </script>

            @elseif($skjampul == null)
                <br>
                <div class="col-lg-12">
                    <div class="card">
                        <form method="post" class="p-4" action="{{ url('/absen/pulang/'.$skid) }}">
                            @method('put')
                            @csrf
                            <div class="form-row">
                                <div class="col"></div>
                                <div class="col">
                                    <center>
                                        <h2>Absen Pulang: </h2>
                                        <div class="webcam" id="results"></div>
                                        @if ($lock_location == null)
                                            <div class="form-group">
                                                <label for="keterangan_pulang">keterangan Pulang</label>
                                                <textarea type="text" class="form-control @error('keterangan_pulang') is-invalid @enderror" id="keterangan_pulang" name="keterangan_pulang" placeholder="Pulang">{{ old('keterangan_pulang', 'Pulang') }}</textarea>
                                                @error('keterangan_pulang')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        @endif
                                    </center>
                                </div>
                                <div class="col">
                                    <input type="hidden" name="jam_pulang">
                                    <input type="hidden" name="foto_jam_pulang" class="image-tag">
                                    <input type="hidden" name="lat_pulang" id="lat">
                                    <input type="hidden" name="long_pulang" id="long">
                                    <input type="hidden" name="pulang_cepat">
                                    <input type="hidden" name="jarak_pulang">
                                </div>
                            </div>
                            <center>
                                <button type="button" class="btn btn-primary" value="Ambil Foto" id="submitBtnPulang" onClick="take_snapshot_and_submit_pulang(this.form)">Pulang</button>
                            </center>
                        </form>
                    </div>
                </div>

                <script type="text/javascript" src="{{ url('webcamjs/webcam.min.js') }}"></script>
                <script language="JavaScript">
                Webcam.set({
                    width: 320,
                    height: 320,
                    image_format: 'jpeg',
                    jpeg_quality: 90
                });
                Webcam.attach( '.webcam' );
                </script>
                <script language="JavaScript">
                function take_snapshot_and_submit_pulang(form) {
                    // Stop location retry when user clicks absen button
                    window.stopLocationRetry();

                    // Disable button to prevent multiple clicks
                    const submitBtn = form.querySelector('#submitBtnPulang');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengambil foto...';

                    try {
                        // Check if Webcam is available
                        if (typeof Webcam === 'undefined') {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                            alert('Webcam library tidak tersedia. Silakan refresh halaman.');
                            return;
                        }

                        // take snapshot and get image data
                        Webcam.snap( function(data_uri) {
                            try {
                                // Validate that we got a proper data URI
                                if (data_uri && data_uri.startsWith('data:image/')) {
                                    form.querySelector('.image-tag').value = data_uri;
                                    // display results in page
                                    document.getElementById('results').innerHTML =
                                        '<img src="'+data_uri+'" style="max-width: 100%; height: auto;"/>';

                                    // Submit form after photo is captured using AJAX
                                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';

                                    // Prepare form data for AJAX submission
                                    var formData = new FormData(form);
                                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                                    // Submit via AJAX
                                    fetch(form.action, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Show success popup
                                            if (typeof Swal !== 'undefined') {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil!',
                                                    text: data.message,
                                                    confirmButtonText: 'OK',
                                                    timer: 3000,
                                                    timerProgressBar: true
                                                }).then(() => {
                                                    // Reload page after popup
                                                    window.location.reload();
                                                });
                                            } else {
                                                alert(data.message);
                                                window.location.reload();
                                            }
                                        } else {
                                            // Show error popup
                                            if (typeof Swal !== 'undefined') {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Error',
                                                    text: data.message || 'Terjadi kesalahan saat menyimpan data.',
                                                    confirmButtonText: 'OK'
                                                });
                                            } else {
                                                alert(data.message || 'Terjadi kesalahan saat menyimpan data.');
                                            }
                                            submitBtn.disabled = false;
                                            submitBtn.innerHTML = originalText;
                                        }
                                    })
                                    .catch(error => {
                                        console.error('AJAX Error:', error);
                                        if (typeof Swal !== 'undefined') {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.',
                                                confirmButtonText: 'OK'
                                            });
                                        } else {
                                            alert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.');
                                        }
                                        submitBtn.disabled = false;
                                        submitBtn.innerHTML = originalText;
                                    });
                                } else {
                                    // Reset button and show error
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalText;
                                    alert('Gagal mengambil foto. Silakan coba lagi.');
                                }
                            } catch (error) {
                                console.error('Error in photo callback:', error);
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                                alert('Terjadi kesalahan saat memproses foto. Silakan coba lagi.');
                            }
                        });
                    } catch (error) {
                        console.error('Error taking snapshot:', error);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        alert('Gagal mengambil foto. Pastikan kamera berfungsi dengan baik.');
                    }
                }
                </script>
            @else
                <br>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="p-4">
                            <center>
                                <h2>Anda Sudah Selesai Absen</h2>
                            </center>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <script>
        // Pastikan jQuery sudah loaded sebelum script ini berjalan
        if (typeof jQuery === 'undefined') {
            console.error('jQuery not loaded, retrying location script initialization...');
            // Tunggu 500ms lalu coba lagi
            setTimeout(function() {
                if (typeof jQuery !== 'undefined') {
                    initializeLocationScript();
                } else {
                    console.error('jQuery still not loaded after retry');
                }
            }, 500);
        } else {
            initializeLocationScript();
        }

        // Global variables for location management
        let locationObtained = false;
        let locationError = false;
        let locationInterval = null;
        let retryCount = 0;
        const MAX_RETRIES = 10; // Maksimal 10 kali retry (50 detik)

        // Global function to stop location retry
        window.stopLocationRetry = function() {
            if (locationInterval) {
                clearInterval(locationInterval);
                locationInterval = null;
            }
        };

        function initializeLocationScript() {
            function showLocationError(message) {
                if (!locationError) {
                    locationError = true;
                    $('#locationBtn').prop('disabled', false);
                    $('#btnText').text('Lihat Lokasi Saya (Error)');
                    $('.spinner-border').addClass('d-none');

                    window.stopLocationRetry();

                    // Tampilkan alert error
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Lokasi',
                            text: message,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        alert('Error: ' + message);
                    }
                }
            }


            function showPosition(position) {
                const lat = position.coords.latitude;
                const long = position.coords.longitude;

                // Validasi koordinat
                if (lat && long && lat !== 0 && long !== 0) {
                    // Hanya isi field untuk tombol "Lihat Lokasi Saya"
                    $('#lat2').val(lat);
                    $('#long2').val(long);

                    if (!locationObtained) {
                        locationObtained = true;
                        $('#locationBtn').prop('disabled', false);
                        $('#btnText').text('Lihat Lokasi Saya');
                        $('.spinner-border').addClass('d-none');

                        window.stopLocationRetry();
                    }
                } else {
                    showLocationError("Koordinat lokasi tidak valid.");
                }
            }

            function showError(error) {
                let errorMessage = "";
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = "Akses lokasi ditolak. Silakan berikan izin akses lokasi di browser.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = "Informasi lokasi tidak tersedia. Pastikan GPS aktif.";
                        break;
                    case error.TIMEOUT:
                        errorMessage = "Waktu habis mendapatkan lokasi. Periksa koneksi internet dan GPS.";
                        break;
                    default:
                        errorMessage = "Terjadi kesalahan saat mendapatkan lokasi: " + error.message;
                        break;
                }
                showLocationError(errorMessage);
            }

            function getLocation() {
            if (navigator.geolocation) {

                // Gunakan single call dengan reasonable settings
                navigator.geolocation.getCurrentPosition(showPosition, showError, {
                    enableHighAccuracy: true,
                    timeout: 10000, // Reasonable timeout
                    maximumAge: 300000 // Cache for 5 minutes
                });
            } else {
                showLocationError("Browser Anda tidak mendukung geolocation.");
            }
        }

        // Mulai mendapatkan lokasi saat halaman dimuat
        $(document).ready(function() {
            retryCount = 0;
            getLocation();

            // Coba lagi setiap 5 detik jika belum mendapat lokasi (lebih reasonable)
            locationInterval = setInterval(function() {
                retryCount++;
                if (retryCount >= MAX_RETRIES) {
                    window.stopLocationRetry();
                    if (!locationObtained && !locationError) {
                        showLocationError("Tidak dapat mendapatkan lokasi setelah beberapa percobaan. Silakan refresh halaman.");
                    }
                    return;
                }

                if (!locationObtained && !locationError) {
                    getLocation();
                } else {
                    window.stopLocationRetry();
                }
            }, 5000); // Increased to 5 seconds
        });

        // Validasi form sebelum submit
        $('#locationForm').on('submit', function(e) {
            const lat = $('#lat2').val();
            const long = $('#long2').val();

            if (!lat || !long || lat === '0' || long === '0') {
                e.preventDefault();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lokasi Belum Siap',
                        text: 'Sedang mendapatkan lokasi Anda. Silakan tunggu sebentar atau periksa GPS.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert('Sedang mendapatkan lokasi Anda. Silakan tunggu sebentar.');
                }
                return false;
            }
        });

        // Stop location retry when absen forms are submitted
        $('form[action*="/absen/masuk/"], form[action*="/absen/pulang/"]').on('submit', function(e) {
            window.stopLocationRetry();
        });

            // Cleanup on page unload
            $(window).on('beforeunload', function() {
                window.stopLocationRetry();
            });
        } // End of initializeLocationScript
    </script>
@endsection
