@extends('templates.app')
@section('container')
    <div class="card-secton transfer-section">
        <div class="tf-container">
            <div class="tf-balance-box">
                    <form method="post" class="tf-form p-2" action="{{ url('/laporan-kerja/store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="group-input">
                            <label for="informasi_umum" class="form-label">Informasi Umum</label>
                            <textarea name="informasi_umum" id="informasi_umum" class="@error('informasi_umum') is-invalid @enderror" rows="5">{{ old('informasi_umum') }}</textarea>
                            @error('informasi_umum')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="group-input">
                            <label for="pekerjaan_dilaksanakan" class="form-label">Pekerjaan Yang Dilaksanakan</label>
                            <textarea name="pekerjaan_dilaksanakan" id="pekerjaan_dilaksanakan" class="@error('pekerjaan_dilaksanakan') is-invalid @enderror" rows="5">{{ old('pekerjaan_dilaksanakan') }}</textarea>
                            @error('pekerjaan_dilaksanakan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="group-input">
                            <label for="pekerjaan_belum_selesai" class="form-label">Pekerjaan Belum Selesai</label>
                            <textarea name="pekerjaan_belum_selesai" id="pekerjaan_belum_selesai" class="@error('pekerjaan_belum_selesai') is-invalid @enderror" rows="5">{{ old('pekerjaan_belum_selesai') }}</textarea>
                            @error('pekerjaan_belum_selesai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="group-input">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea name="catatan" id="catatan" class="@error('catatan') is-invalid @enderror" rows="5">{{ old('catatan') }}</textarea>
                            @error('catatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="group-input">
                            <h5 class="mb-3">
                                <i class="fas fa-camera text-primary"></i> Foto Laporan Kerja
                            </h5>
                            <small class="text-muted d-block mb-3">(Opsional - Maksimal 5MB)</small>

                            <div class="custom-file-upload">
                                <input type="file" name="foto" id="foto" class="@error('foto') is-invalid @enderror" accept="image/*" style="display: none;">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('foto').click()">
                                    <i class="fas fa-camera"></i> Pilih Foto
                                </button>
                                <span id="fileName" class="ml-2 text-muted">Belum ada file dipilih</span>
                            </div>
                            @error('foto')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                            <small class="form-text text-muted mt-2">
                                Upload foto terkait pekerjaan yang dilakukan. Format: JPG, PNG, JPEG. Ukuran maksimal 5MB.
                            </small>
                        </div>

                        <!-- Preview Image -->
                        <div id="imagePreview" class="group-input" style="display: none;">
                            <div class="card shadow-sm">
                                <div class="card-body p-3">
                                    <div class="text-center">
                                        <img id="previewImg" src="" alt="Preview Foto Laporan Kerja" class="img-fluid rounded shadow-sm photo-thumbnail" style="max-width: 100%; max-height: 350px; height: auto; object-fit: contain; cursor: pointer;">
                                    </div>
                                    <div class="mt-3 text-center">
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Foto akan disimpan sebagai bagian dari laporan kerja
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bottom" style="padding: 20px 60px 60px 60px; background-color:white">
                            <div class="tf-container">
                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="tf-btn accent large">Kirim Laporan</button>
                                    </div>
                                    <div class="col">
                                        <a href="{{ url('/laporan-kerja') }}" class="tf-btn btn-danger large">Batal</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>

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
        // Image preview functionality
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileNameSpan = document.getElementById('fileName');
            const previewContainer = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            if (file) {
                // Update file name display
                fileNameSpan.textContent = file.name;
                fileNameSpan.className = 'ml-2 text-success';

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

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.style.display = 'block';

                    // Scroll to preview smoothly
                    setTimeout(() => {
                        previewContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 300);
                };
                reader.readAsDataURL(file);
            } else {
                resetFileInput();
            }
        });

        function resetFileInput() {
            const fileInput = document.getElementById('foto');
            const fileNameSpan = document.getElementById('fileName');
            const previewContainer = document.getElementById('imagePreview');

            fileInput.value = '';
            fileNameSpan.textContent = 'Belum ada file dipilih';
            fileNameSpan.className = 'ml-2 text-muted';
            previewContainer.style.display = 'none';
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

        function showPreviewModal() {
            const previewImg = document.getElementById('previewImg');
            if (previewImg.src && previewImg.src !== '') {
                showPhotoModal(previewImg.src, 'Preview Foto', 'Belum disimpan');
            } else {
                showAlert('Tidak ada foto untuk dipreview.', 'warning');
            }
        }

        // Add click event for preview image
        document.addEventListener('DOMContentLoaded', function() {
            const previewImg = document.getElementById('previewImg');
            if (previewImg) {
                previewImg.addEventListener('click', function() {
                    if (this.src && this.src !== '') {
                        showPreviewModal();
                    }
                });
            }
        });

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
    </style>
@endsection

