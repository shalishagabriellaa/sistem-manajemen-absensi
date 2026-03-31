@extends('templates.dashboard')
@section('isi')
    <div class="row">
        <div class="col-md-12 project-list">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 mt-2 p-0 d-flex">
                        <h4>{{ $title }}</h4>
                    </div>
                    <div class="col-md-6 p-0">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ url('/laporan-kerja') }}">
                        <div class="row mb-3">
                            <div class="col-2">
                                <select name="jabatan_id" id="jabatan_id" class="form-control selectpicker" data-live-search="true">
                                    <option value=""selected>Semua Divisi</option>
                                    @foreach($jabatan as $j)
                                        @if(request('jabatan_id') == $j->id)
                                            <option value="{{ $j->id }}"selected>{{ $j->nama_jabatan }}</option>
                                        @else
                                            <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <input type="date" class="form-control" name="mulai" id="mulai" placeholder="Tanggal Mulai" value="{{ request('mulai') }}">
                            </div>
                            <div class="col-2">
                                <input type="date" class="form-control" name="akhir" id="akhir" placeholder="Tanggal Akhir" value="{{ request('akhir') }}">
                            </div>
                            <div class="col-5">
                                <input type="text" style="border-color: rgb(222, 222, 222)" class="form-control" name="search" placeholder="Search.." id="search" value="{{ request('search') }}">
                            </div>
                            <div class="col-1">
                                <button type="submit" id="search" class="btn" style="background-color: rgb(222, 222, 222);"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="border-radius: 10px">
                        <table class="table" style="font-size:12px">
                            <thead>
                                <tr>
                                    <th class="text-center" style="position: sticky; left: 0; background-color: rgb(215, 215, 215); z-index: 2;">No.</th>
                                    <th style="position: sticky; left: 40px; background-color: rgb(215, 215, 215); z-index: 2; min-width: 230px;" class="text-center">Nama Pegawai</th>
                                    <th style="min-width: 200px; background-color:rgb(243, 243, 243);" class="text-center">Tanggal</th>
                                    <th style="min-width: 300px; background-color:rgb(243, 243, 243);" class="text-center">Informasi Umum</th>
                                    <th style="min-width: 300px; background-color:rgb(243, 243, 243);" class="text-center">Pekerjaan Yang Dilaksanakan</th>
                                    <th style="min-width: 300px; background-color:rgb(243, 243, 243);" class="text-center">Pekerjaan Belum Selesai</th>
                                    <th style="min-width: 300px; background-color:rgb(243, 243, 243);" class="text-center">Catatan</th>
                                    <th style="min-width: 150px; background-color:rgb(243, 243, 243);" class="text-center">Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($laporan_kerjas) <= 0)
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak Ada Data</td>
                                    </tr>
                                @else
                                    @foreach ($laporan_kerjas as $key => $laporan_kerja)
                                        <tr>
                                            <td class="text-center" style="position: sticky; left: 0; background-color: rgb(235, 235, 235); z-index: 1; vertical-align: middle;">{{ ($laporan_kerjas->currentpage() - 1) * $laporan_kerjas->perpage() + $key + 1 }}.</td>
                                            <td class="text-center" style="position: sticky; left: 40px; background-color: rgb(235, 235, 235); z-index: 1; vertical-align: middle;">
                                                {{ $laporan_kerja->user->name ?? '-' }}
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                                @if ($laporan_kerja->tanggal)
                                                    @php
                                                        Carbon\Carbon::setLocale('id');
                                                        $tanggal = Carbon\Carbon::createFromFormat('Y-m-d', $laporan_kerja->tanggal);
                                                        $new_tanggal = $tanggal->translatedFormat('d F Y');
                                                    @endphp
                                                    {{ $new_tanggal  }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle;">
                                                @if ($laporan_kerja->informasi_umum)
                                                    {!! nl2br(e($laporan_kerja->informasi_umum)) !!}
                                                @else
                                                    <center>
                                                        -
                                                    </center>
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle;">
                                                @if ($laporan_kerja->pekerjaan_dilaksanakan)
                                                    {!! nl2br(e($laporan_kerja->pekerjaan_dilaksanakan)) !!}
                                                @else
                                                    <center>
                                                        -
                                                    </center>
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle;">
                                                @if ($laporan_kerja->pekerjaan_belum_selesai)
                                                    {!! nl2br(e($laporan_kerja->pekerjaan_belum_selesai)) !!}
                                                @else
                                                    <center>
                                                        -
                                                    </center>
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle;">
                                                @if ($laporan_kerja->catatan)
                                                    {!! nl2br(e($laporan_kerja->catatan)) !!}
                                                @else
                                                    <center>
                                                        -
                                                    </center>
                                                @endif
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                                @if ($laporan_kerja->foto)
                                                    @php
                                                        $photoPath = 'storage/' . $laporan_kerja->foto;
                                                        $photoExists = file_exists(public_path($photoPath));
                                                    @endphp
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle"></i> Ada
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        @if($photoExists)
                                                            <a href="javascript:void(0)" class="text-primary" title="Lihat Foto" onclick="showPhotoModal('{{ url($photoPath) }}', '{{ addslashes($laporan_kerja->user->name ?? 'N/A') }}', '{{ $laporan_kerja->tanggal }}')">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @else
                                                            <span class="text-warning" title="File foto tidak ditemukan">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                            </span>
                                                        @endif
                                                    </small>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-times-circle"></i> Tidak Ada
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        {{ $laporan_kerjas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
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

    @push('style')
        <style>
            td {
                border: 1px solid #e2dede;
            }
            th {
                border: 1px solid #e2dede;
            }

        #photoModal .modal-lg {
            max-width: 50%;
            max-height: 50vh;
        }

        #photoModal .modal-dialog {
            margin: 5vh auto;
        }

            #photoDisplay {
                transition: opacity 0.3s ease;
            }
        </style>
    @endpush

    @push('script')
        <script>
            $(document).ready(function() {
                $('#mulai').change(function(){
                    var mulai = $(this).val();
                    $('#akhir').val(mulai);
                });
            });

            function showPhotoModal(photoUrl, userName, tanggal) {
                console.log('showPhotoModal called with:', photoUrl, userName, tanggal);

                try {
                    // Set modal content
                    const photoDisplay = document.getElementById('photoDisplay');
                    const photoInfo = document.getElementById('photoInfo');
                    const downloadBtn = document.getElementById('downloadBtn');

                    if (!photoDisplay || !photoInfo || !downloadBtn) {
                        console.error('Modal elements not found');
                        alert('Error: Modal elements not found');
                        return;
                    }

                    photoDisplay.src = photoUrl;
                    photoInfo.textContent = userName + ' - ' + tanggal;
                    downloadBtn.href = photoUrl;

                    // Show modal using Bootstrap
                    if (typeof $ !== 'undefined' && $('#photoModal').modal) {
                        $('#photoModal').modal('show');
                        console.log('Modal shown with jQuery');
                    } else {
                        // Fallback to vanilla JS
                        const modal = document.getElementById('photoModal');
                        if (modal) {
                            modal.style.display = 'block';
                            modal.classList.add('show');
                            document.body.classList.add('modal-open');
                            console.log('Modal shown with vanilla JS');
                        }
                    }

                    // Reset opacity when image loads
                    photoDisplay.onload = function() {
                        this.style.opacity = '1';
                        console.log('Image loaded successfully');
                    };
                    photoDisplay.onerror = function() {
                        console.error('Image failed to load:', photoUrl);
                        this.src = '/images/placeholder-image.png'; // Fallback if image fails to load
                        document.getElementById('photoInfo').textContent = 'Foto tidak dapat dimuat';
                    };
                } catch (error) {
                    console.error('Error in showPhotoModal:', error);
                    alert('Error: ' + error.message);
                }
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
    @endpush
@endsection
