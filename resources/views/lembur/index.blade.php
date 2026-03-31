@extends('templates.dashboard')
@section('isi')
    <div class="container-fluid">

        <?php
            $cek_lembur = $lembur->count();

            if($cek_lembur > 0) {
                foreach($lembur as $l) {
                    $id = $l->id;
                    $jam_masuk = $l->jam_masuk;
                    $jam_keluar = $l->jam_keluar;
                }
            } else {
                    $id = "";
                    $jam_masuk = "";
                    $jam_keluar = "";
            }
        ?>

        @php
            // Ambil waktu server (menggunakan timezone dari config Laravel)
            $serverTimeMs = now(config('app.timezone'))->timestamp * 1000;
        @endphp

        <center>
            <p class="p mb-2 text-gray-800">Tanggal : {{ date('Y-m-d') }}</p>
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
                var waktu = new Date(serverTimeMs);

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

                serverTimeMs += 1000;
            }

            setInterval(waktu, 1000);
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

        <br>

        @if($cek_lembur == 0)
            <div class="col-lg-12">
                <div class="card">
                    <form method="post" action="{{ url('/lembur/masuk') }}" class="p-4">
                        @csrf
                        <div class="form-row">
                            <div class="col"></div>
                            <div class="col">
                                <center>
                                    <h2>Masuk Lembur: </h2>
                                    <div class="webcam" id="results"></div>
                                </center>
                            </div>
                            <div class="col">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
                                <input type="hidden" name="jam_masuk" value="{{ date('Y-m-d H:i') }}">
                                <input type="hidden" name="lat_masuk" id="lat">
                                <input type="hidden" name="long_masuk" id="long">
                                <input type="hidden" name="jarak_masuk">
                                <input type="hidden" name="status" value="Pending">
                                <input type="hidden" name="foto_jam_masuk" class="image-tag">
                            </div>
                        </div>
                        <center>
                            <button type="submit" class="btn btn-primary" value="Ambil Foto" onClick="take_snapshot()">Masuk</button>
                        </center>
                    </form>
                </div>
            </div>
            <script type="text/javascript" src="{{ url('webcamjs/webcam.min.js') }}"></script>
            <script language="JavaScript">
                Webcam.set({
                    width: 240,
                    height: 320,
                    image_format: 'jpeg',
                    jpeg_quality: 50
                });
                Webcam.attach( '.webcam' );
            </script>
            <script language="JavaScript">
                function take_snapshot() {
                    Webcam.snap( function(data_uri) {
                        $(".image-tag").val(data_uri);
                        document.getElementById('results').innerHTML =
                            '<img src="'+data_uri+'"/>';
                    } );
                }
            </script>

        @elseif($cek_lembur > 0 && $jam_masuk == true && $jam_keluar == null)
            <div class="col-lg-12">
                <div class="card">
                    <form method="post" action="{{ url('/lembur/pulang/'.$id) }}" class="p-4">
                        @method('put')
                        @csrf
                        <div class="form-row">
                            <div class="col"></div>
                            <div class="col">
                                <center>
                                    <h2>Pulang Lembur: </h2>
                                    <div class="webcam" id="results"></div>
                                </center>
                            </div>
                            <div class="col">
                                <input type="hidden" name="jam_keluar" value="{{ date('Y-m-d H:i') }}">
                                <input type="hidden" name="lat_keluar" id="lat">
                                <input type="hidden" name="long_keluar" id="long">
                                <input type="hidden" name="jarak_keluar">
                                <input type="hidden" name="foto_jam_keluar" class="image-tag">
                                <input type="hidden" name="total_lembur">
                            </div>
                        </div>
                        <center>
                            <button type="submit" class="btn btn-primary" value="Ambil Foto" onClick="take_snapshot()">Pulang</button>
                        </center>
                        </form>
                </div>
            </div>
            <script type="text/javascript" src="{{ url('webcamjs/webcam.min.js') }}"></script>
            <script language="JavaScript">
            Webcam.set({
            width: 240,
            height: 320,
            image_format: 'jpeg',
            jpeg_quality: 50
            });
            Webcam.attach( '.webcam' );
            </script>
            <script language="JavaScript">
            function take_snapshot() {
            // take snapshot and get image data
            Webcam.snap( function(data_uri) {
                        $(".image-tag").val(data_uri);
                // display results in page
                document.getElementById('results').innerHTML =
                '<img src="'+data_uri+'"/>';
            } );
            }
            </script>
        @else
            <div class="col-lg-12">
                <div class="card">
                    <div class="p-4">
                        <center>
                            <h2>Anda Sudah Selesai Lembur Hari Ini</h2>
                        </center>
                    </div>
                </div>
            </div>
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
                    // Isi field untuk form lembur
                    $('#lat').val(lat);
                    $('#lat2').val(lat);
                    $('#long').val(long);
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

            // Validasi form location sebelum submit
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

            // Stop location retry when lembur forms are submitted
            $('form[action*="/lembur/masuk"], form[action*="/lembur/pulang/"]').on('submit', function(e) {
                window.stopLocationRetry();
            });

            // Cleanup on page unload
            $(window).on('beforeunload', function() {
                window.stopLocationRetry();
            });
        } // End of initializeLocationScript
    </script>
@endsection

