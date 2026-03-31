@extends('templates.app')
@section('container')

    <div class="card-secton transfer-section">
        <div class="tf-container">
            <div class="tf-balance-box">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="inner-left d-flex justify-content-between align-items-center">
                        <span>Tanggal</span>
                    </div>
                    <span>{{ date('Y-m-d') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <br>
    @php
        // Ambil waktu server (menggunakan timezone dari config Laravel)
        $serverTimeMs = now(config('app.timezone'))->timestamp * 1000;
    @endphp

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

    <div class="d-flex justify-content-center mb-4">
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

    <div class="transfer-content">
        @if (!$lembur)
            <form method="post" action="{{ url('/lembur/masuk') }}">
                @csrf
                <div class="tf-container">
                    <center>
                        <h2>Masuk Lembur: </h2>
                        <div class="webcam" id="results"></div>
                    </center>
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
                            <input type="hidden" name="jam_masuk" value="{{ date('Y-m-d H:i') }}">
                            <input type="hidden" name="lat_masuk" id="lat">
                            <input type="hidden" name="long_masuk" id="long">
                            <input type="hidden" name="jarak_masuk">
                            <input type="hidden" name="status" value="Pending">
                            <input type="hidden" name="foto_jam_masuk" class="image-tag">
                    <button type="submit" class="tf-btn accent large" onClick="take_snapshot()">Save</button>
                </div>
            </form>
            <br>
            <br>
            <br>
            <br>
            <br>
            <script type="text/javascript" src="{{ url('webcamjs/webcam.min.js') }}"></script>
            <script language="JavaScript">
            Webcam.set({
                width: 310,
                height: 420,
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
        @elseif($lembur && $lembur->jam_keluar == null)
            <form method="post" action="{{ url('/lembur/pulang/'.$lembur->id) }}">
                @method('PUT')
                @csrf
                <div class="tf-container">
                    <center>
                        <h2>Pulang Lembur: </h2>
                        <div class="webcam" id="results"></div>
                    </center>
                            <input type="hidden" name="jam_keluar" value="{{ date('Y-m-d H:i') }}">
                            <input type="hidden" name="lat_keluar" id="lat">
                            <input type="hidden" name="long_keluar" id="long">
                            <input type="hidden" name="jarak_keluar">
                            <input type="hidden" name="foto_jam_keluar" class="image-tag">
                            <input type="hidden" name="total_lembur">
                    <button type="submit" class="tf-btn accent large" onClick="take_snapshot()">Save</button>
                </div>
            </form>
            <br>
            <br>
            <br>
            <br>
            <br>
            <script type="text/javascript" src="{{ url('webcamjs/webcam.min.js') }}"></script>
            <script language="JavaScript">
            Webcam.set({
                width: 310,
                height: 420,
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
        @else
            <center>
                    <h2>Anda Sudah Selesai Lembur Hari Ini</h2>
            </center>
        @endif
    </div>

    @push('script')
        <script>
            let locationObtained = false;
            let locationError = false;

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition, showError, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 30000
                    });
                } else {
                    showLocationError("Browser Anda tidak mendukung geolocation.");
                }
            }

            function showPosition(position) {
                const lat = position.coords.latitude;
                const long = position.coords.longitude;

                // Validasi koordinat
                if (lat && long && lat !== 0 && long !== 0) {
                    $('#lat').val(lat);
                    $('#lat2').val(lat);
                    $('#long').val(long);
                    $('#long2').val(long);

                    if (!locationObtained) {
                        locationObtained = true;
                        $('#locationBtn').prop('disabled', false);
                        $('#btnText').text('Lihat Lokasi Saya');
                        $('.spinner-border').addClass('d-none');
                    }
                } else {
                    showLocationError("Koordinat lokasi tidak valid.");
                }
            }

            function showError(error) {
                let errorMessage = "";
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = "Akses lokasi ditolak. Silakan berikan izin akses lokasi.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = "Informasi lokasi tidak tersedia.";
                        break;
                    case error.TIMEOUT:
                        errorMessage = "Waktu habis mendapatkan lokasi. Coba lagi.";
                        break;
                    default:
                        errorMessage = "Terjadi kesalahan saat mendapatkan lokasi.";
                        break;
                }
                showLocationError(errorMessage);
            }

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

            // Mulai mendapatkan lokasi saat halaman dimuat
            $(document).ready(function() {
                getLocation();
                // Coba lagi setiap 5 detik jika belum mendapat lokasi
                const locationInterval = setInterval(function() {
                    if (!locationObtained && !locationError) {
                        getLocation();
                    } else {
                        clearInterval(locationInterval);
                    }
                }, 5000);
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
                            text: 'Sedang mendapatkan lokasi Anda. Silakan tunggu sebentar.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        alert('Sedang mendapatkan lokasi Anda. Silakan tunggu sebentar.');
                    }
                    return false;
                }
            });
        </script>
    @endpush
    
@endsection