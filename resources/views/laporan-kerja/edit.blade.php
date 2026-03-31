@extends('templates.app')
@section('container')
    <div class="card-secton transfer-section">
        <div class="tf-container">
            <div class="tf-balance-box">
                    <form method="post" class="tf-form p-2" action="{{ url('/laporan-kerja/update/'.$laporan_kerja->id) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <div class="group-input">
                            <label for="informasi_umum" class="form-label">Informasi Umum</label>
                            <textarea name="informasi_umum" id="informasi_umum" class="@error('informasi_umum') is-invalid @enderror" rows="5">{{ old('informasi_umum', $laporan_kerja->informasi_umum) }}</textarea>
                            @error('informasi_umum')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="group-input">
                            <label for="pekerjaan_dilaksanakan" class="form-label">Pekerjaan Yang Dilaksanakan</label>
                            <textarea name="pekerjaan_dilaksanakan" id="pekerjaan_dilaksanakan" class="@error('pekerjaan_dilaksanakan') is-invalid @enderror" rows="5">{{ old('pekerjaan_dilaksanakan', $laporan_kerja->pekerjaan_dilaksanakan) }}</textarea>
                            @error('pekerjaan_dilaksanakan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="group-input">
                            <label for="pekerjaan_belum_selesai" class="form-label">Pekerjaan Belum Selesai</label>
                            <textarea name="pekerjaan_belum_selesai" id="pekerjaan_belum_selesai" class="@error('pekerjaan_belum_selesai') is-invalid @enderror" rows="5">{{ old('pekerjaan_belum_selesai', $laporan_kerja->pekerjaan_belum_selesai) }}</textarea>
                            @error('pekerjaan_belum_selesai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="group-input">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea name="catatan" id="catatan" class="@error('catatan') is-invalid @enderror" rows="5">{{ old('catatan', $laporan_kerja->catatan) }}</textarea>
                            @error('catatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="group-input">
                            <h5 class="mb-3">
                                <i class="fas fa-camera text-primary"></i> @if($laporan_kerja->foto) Ganti @else Tambah @endif Foto Laporan Kerja
                            </h5>
                            <small class="text-muted d-block mb-3">(Opsional - Maksimal 5MB)</small>

                            <div class="custom-file-upload">
                                <input type="file" name="foto" id="foto" class="@error('foto') is-invalid @enderror" accept="image/*" style="display: none;">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('foto').click()">
                                    <i class="fas fa-camera"></i> @if($laporan_kerja->foto) Pilih Foto Pengganti @else Pilih Foto @endif
                                </button>
                                <span id="fileName" class="ml-2 text-muted">@if($laporan_kerja->foto) Foto baru akan mengganti foto saat ini @else Belum ada file dipilih @endif</span>
                            </div>
                            @error('foto')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                            <small class="form-text text-muted mt-2">
                                @if($laporan_kerja->foto)
                                Pilih foto baru untuk langsung mengganti foto saat ini. Foto lama akan otomatis terhapus saat menyimpan.
                                @else
                                Upload foto laporan kerja. Format: JPG, PNG, JPEG. Ukuran maksimal 5MB.
                                @endif
                            </small>
                        </div>

                        <!-- Photo Display Area -->
                        <div class="group-input mb-4">
                            <label class="form-label d-block mb-2">
                                <i class="fas fa-image"></i> @if($laporan_kerja->foto) Foto Saat Ini @else Preview Foto @endif
                            </label>
                            <div class="text-center">
                                @if($laporan_kerja->foto)
                                    <img id="currentPhotoImg" src="{{ asset('storage/' . $laporan_kerja->foto) }}" alt="Foto Laporan Kerja Saat Ini" class="img-fluid rounded shadow-sm photo-thumbnail" style="max-width: 100%; max-height: 300px; height: auto; object-fit: contain; cursor: pointer;" onclick="showPhotoModal('{{ asset('storage/' . $laporan_kerja->foto) }}', '{{ $laporan_kerja->user->name ?? 'N/A' }}', '{{ $laporan_kerja->tanggal }}')">
                                @else
                                    <div id="photoPlaceholder" class="border border-dashed rounded p-5 text-muted">
                                        <i class="fas fa-camera fa-3x mb-3"></i>
                                        <br>
                                        <strong>Belum ada foto</strong>
                                        <br>
                                        <small>Pilih foto di bawah ini</small>
                                    </div>
                                @endif
                                <!-- Hidden preview image that will replace current photo -->
                                <img id="newPhotoPreview" src="" alt="Preview Foto Baru" class="img-fluid rounded shadow-sm" style="max-width: 100%; max-height: 300px; height: auto; object-fit: contain; cursor: pointer; display: none;">
                            </div>
                            <div class="text-center mt-2">
                                @if($laporan_kerja->foto)
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCurrentPhoto()">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                @endif
                                <div id="photoStatus" class="mt-2">
                                    @if($laporan_kerja->foto)
                                        <small class="text-info">
                                            <i class="fas fa-info-circle"></i> Klik gambar untuk melihat dalam ukuran penuh
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bottom" style="padding: 20px 60px 60px 60px; background-color:white">
                            <div class="tf-container">
                                <div class="row">
                                    <div class="col text-center">
                                        <button type="submit" class="tf-btn accent large">
                                            <i class="fas fa-save"></i> Simpan
                                        </button>
                                    </div>
                                    <div class="col text-center">
                                        <a href="{{ url('/laporan-kerja') }}" class="tf-btn btn-secondary large">
                                            <i class="fas fa-arrow-left"></i> Batal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
        // File selection functionality
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileNameSpan = document.getElementById('fileName');
            const currentPhotoImg = document.getElementById('currentPhotoImg');
            const newPhotoPreview = document.getElementById('newPhotoPreview');
            const photoPlaceholder = document.getElementById('photoPlaceholder');
            const photoStatus = document.getElementById('photoStatus');

            if (file) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    showAlert('Format file tidak didukung. Harap pilih file gambar (JPG, PNG, JPEG, GIF).', 'danger');
                    resetFileInput();
                    return;
                }

                // Validate file size (5MB)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    showAlert('Ukuran file terlalu besar. Maksimal 5MB.', 'danger');
                    resetFileInput();
                    return;
                }

                // Show preview by replacing current photo
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Hide current photo and placeholder
                    if (currentPhotoImg) currentPhotoImg.style.display = 'none';
                    if (photoPlaceholder) photoPlaceholder.style.display = 'none';

                    // Show new photo preview
                    newPhotoPreview.src = e.target.result;
                    newPhotoPreview.style.display = 'block';
                    newPhotoPreview.onclick = function() {
                        showPhotoModal(e.target.result, 'Preview Foto Baru', 'Belum disimpan');
                    };

                    // Update status
                    fileNameSpan.textContent = 'File dipilih: ' + file.name;
                    fileNameSpan.className = 'ml-2 text-success';

                    photoStatus.innerHTML = '<small class="text-success"><i class="fas fa-check-circle"></i> Foto baru siap diganti. Klik simpan untuk menyimpan perubahan.</small>';
                };
                reader.readAsDataURL(file);
            } else {
                resetFileInput();
            }
        });

        function resetFileInput() {
            const fileInput = document.getElementById('foto');
            const fileNameSpan = document.getElementById('fileName');
            const currentPhotoImg = document.getElementById('currentPhotoImg');
            const newPhotoPreview = document.getElementById('newPhotoPreview');
            const photoPlaceholder = document.getElementById('photoPlaceholder');
            const photoStatus = document.getElementById('photoStatus');

            fileInput.value = '';

            // Reset display
            if (newPhotoPreview) newPhotoPreview.style.display = 'none';
            @if($laporan_kerja->foto)
                if (currentPhotoImg) currentPhotoImg.style.display = 'block';
                fileNameSpan.textContent = 'Foto baru akan mengganti foto saat ini';
                photoStatus.innerHTML = '<small class="text-info"><i class="fas fa-info-circle"></i> Klik gambar untuk melihat dalam ukuran penuh</small>';
            @else
                if (photoPlaceholder) photoPlaceholder.style.display = 'block';
                fileNameSpan.textContent = 'Belum ada file dipilih';
                photoStatus.innerHTML = '';
            @endif
            fileNameSpan.className = 'ml-2 text-muted';
        }

        function removeCurrentPhoto() {
            if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
                const currentPhotoImg = document.getElementById('currentPhotoImg');
                const photoPlaceholder = document.getElementById('photoPlaceholder');
                const photoStatus = document.getElementById('photoStatus');

                // Add hidden input to mark photo for deletion
                const form = document.querySelector('form');
                let deleteInput = document.getElementById('delete_current_photo');
                if (!deleteInput) {
                    deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'delete_current_photo';
                    deleteInput.id = 'delete_current_photo';
                    deleteInput.value = '1';
                    form.appendChild(deleteInput);
                } else {
                    deleteInput.value = '1';
                }

                // Hide current photo and show placeholder
                if (currentPhotoImg) currentPhotoImg.style.display = 'none';
                if (photoPlaceholder) {
                    photoPlaceholder.innerHTML = `
                        <i class="fas fa-trash fa-3x mb-3 text-danger"></i>
                        <br>
                        <strong class="text-danger">Foto akan dihapus</strong>
                        <br>
                        <small>Klik simpan untuk menghapus foto</small>
                    `;
                    photoPlaceholder.style.display = 'block';
                }

                // Update status
                photoStatus.innerHTML = '<small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Foto akan dihapus saat menyimpan perubahan.</small>';

            }
        }

        function showAlert(message, type) {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            `;

            // Add to body
            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    $(alertDiv).alert('close');
                }
            }, 5000);
        }

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

    <style>
        .custom-file-upload {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .custom-file-upload .btn {
            white-space: nowrap;
        }

        #imagePreview .card {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }

        #imagePreview img {
            border-radius: 0.375rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .invalid-feedback {
            display: block;
        }

        .gap-3 {
            gap: 1rem !important;
        }

        .btn {
            border-radius: 10px !important;
        }

        .border-dashed {
            border-style: dashed !important;
            border-color: #dee2e6 !important;
        }
    </style>
@endsection

