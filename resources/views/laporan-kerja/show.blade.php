@extends('templates.app')
@section('container')
    <div id="app-wrap" class="style1">
        <div class="tf-container">
            <div class="tf-tab">
                <div class="bill-content mt-4">
                    <div class="tf-container ">
                        <ul>
                            <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                                <div class="content-right">
                                    <h5>
                                        Informasi Umum
                                    </h5>
                                    <p>
                                        {!! $laporan_kerja->informasi_umum ? nl2br(e($laporan_kerja->informasi_umum)) : '-' !!}
                                    </p>
                                </div>
                            </li>
                            <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                                <div class="content-right">
                                    <h5>
                                        Pekerjaan Yang Dilaksanakan
                                    </h5>
                                    <p>
                                        {!! $laporan_kerja->pekerjaan_dilaksanakan ? nl2br(e($laporan_kerja->pekerjaan_dilaksanakan)) : '-' !!}
                                    </p>
                                </div>
                            </li>
                            <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                                <div class="content-right">
                                    <h5>
                                        Pekerjaan Yang Belum Selesai
                                    </h5>
                                    <p>
                                        {!! $laporan_kerja->pekerjaan_belum_selesai ? nl2br(e($laporan_kerja->pekerjaan_belum_selesai)) : '-' !!}
                                    </p>
                                </div>
                            </li>
                            <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                                <div class="content-right">
                                    <h5>
                                        Catatan
                                    </h5>
                                    <p>
                                        {!! $laporan_kerja->catatan ? nl2br(e($laporan_kerja->catatan)) : '-' !!}
                                    </p>
                                </div>
                            </li>
                            @if($laporan_kerja->foto)
                            <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                                <div class="content-right">
                                    <h5>
                                        <i class="fas fa-camera text-primary"></i> Foto Laporan Kerja
                                    </h5>
                                    <div class="mt-3">
                                        <div class="card shadow-sm">
                                            <div class="card-body p-3">
                                                <div class="text-center">
                                                    <a href="{{ asset('storage/' . $laporan_kerja->foto) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $laporan_kerja->foto) }}" alt="Foto Laporan Kerja" class="img-fluid rounded shadow-sm photo-thumbnail" style="max-width: 100%; max-height: 400px; height: auto; object-fit: contain;">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-center">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Klik gambar untuk membuka foto dalam ukuran penuh
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom" style="padding: 20px 60px 80px 60px; background-color:white">
        <div class="tf-container">  
            <div class="row">
                <div class="col">
                    <a href="{{ url('/laporan-kerja/edit/'.$laporan_kerja->id) }}" class="tf-btn accent large">Edit</a>
                </div>
                <div class="col">
                    <form action="{{ url('/laporan-kerja/delete/'.$laporan_kerja->id) }}" method="post">
                        @method('delete')
                        @csrf
                        <button class="tf-btn btn-danger large"  onClick="return confirm('Are You Sure')">Delete</button>
                    </form>
                </div>
            </div>
            <div class="row mb-4" style="margin-top:10px;">
                <div class="col">
                    <a href="{{ url('/laporan-kerja') }}" class="tf-btn btn-secondary large">
                        <i class="fas fa-arrow-left"></i> Kembali ke Riwayat Laporan Kerja
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">
                        <i class="fas fa-camera"></i> Foto Laporan Kerja
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <strong id="photoInfo"></strong>
                    </div>
                    <img id="photoDisplay" src="" alt="Foto Laporan Kerja" class="img-fluid rounded shadow" style="max-width: 100%; max-height: 90vh; height: auto; object-fit: contain;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <a id="downloadBtn" href="" download class="btn btn-primary">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        #photoModal .modal-lg {
            max-width: 80%;
            max-height: 80vh;
        }

        #photoModal .modal-dialog {
            margin: 5vh auto;
        }

        #photoDisplay {
            transition: opacity 0.3s ease;
        }

        .photo-thumbnail:hover {
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
    </style>

    <script>
        function showPhotoModal(photoUrl, userName, tanggal) {
            // Set modal content
            document.getElementById('photoDisplay').src = photoUrl;
            document.getElementById('photoInfo').textContent = userName + ' - ' + tanggal;
            document.getElementById('downloadBtn').href = photoUrl;

            // Show modal
            $('#photoModal').modal({
                backdrop: true, // Allow backdrop click to close
                keyboard: true, // Allow ESC key to close
                focus: true,
                show: true
            });

            // Reset opacity when image loads
            document.getElementById('photoDisplay').onload = function() {
                this.style.opacity = '1';
            };
            document.getElementById('photoDisplay').onerror = function() {
                this.src = '/images/placeholder-image.png'; // Fallback if image fails to load
                document.getElementById('photoInfo').textContent = 'Foto tidak dapat dimuat';
            };
        }

        // Ensure modal close buttons work
        $(document).ready(function() {
            console.log('Modal event handlers initialized');

            // Handle modal close events
            $('#photoModal').on('hidden.bs.modal', function () {
                console.log('Modal hidden');
                // Reset modal content when closed
                document.getElementById('photoDisplay').src = '';
                document.getElementById('photoInfo').textContent = '';
            });

            // Force close modal when close button is clicked
            $('#photoModal .close, #photoModal .btn-secondary').on('click', function(e) {
                console.log('Close button clicked');
                e.preventDefault();
                e.stopPropagation();
                $('#photoModal').modal('hide');
            });

            // Additional click handlers for modal close
            $('#photoModal').on('click', '.modal-header .close', function() {
                console.log('Header close clicked');
                $('#photoModal').modal('hide');
            });

            $('#photoModal').on('click', '.modal-footer .btn-secondary', function() {
                console.log('Footer close clicked');
                $('#photoModal').modal('hide');
            });

            // Handle ESC key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && $('#photoModal').is(':visible')) {
                    console.log('ESC key pressed');
                    $('#photoModal').modal('hide');
                }
            });
        });
    </script>
@endsection

