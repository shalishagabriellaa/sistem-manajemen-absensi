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
                        <a href="{{ url('/pegawai/tambah-pegawai') }}" class="btn btn-primary btn-sm ms-2">+ Tambah</a>
                        <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#exampleModal"><i class="fa fa-table me-2"></i> Import</button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Import Users</h5>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ url('/pegawai/import') }}" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            @csrf
                                            <div class="form-group mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label for="file_excel" class="mb-0">File Excel</label>
                                                    <a href="{{ asset('DataUser/userimport.xlsx') }}" class="btn btn-sm btn-outline-info" download>
                                                        <i class="fa fa-download me-1"></i> Download Template
                                                    </a>
                                                </div>
                                                <input type="file" name="file_excel" id="file_excel" class="form-control @error('file_excel') is-invalid @enderror" accept=".xlsx,.xls">
                                                @error('file_excel')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <small class="text-muted">Format file: .xlsx atau .xls</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-secondary" type="submit">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <a href="{{ url('/pegawai/export') }}{{ $_GET?'?'.$_SERVER['QUERY_STRING']: '' }}" class="btn btn-sm btn-success me-2"><i class="fa fa-file-excel me-2"></i> Export</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <form action="{{ url('/pegawai') }}">
                        <div class="row mb-3">
                            <div class="col-3">
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
                            <div class="col-7">
                                <input type="text" placeholder="Search...." class="form-control" value="{{ request('search') }}" name="search">
                            </div>
                            <div class="col-2">
                                <button type="submit" id="search"class="border-0 mt-3" style="background-color: transparent;"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="border-radius: 10px; overflow-x: auto; max-width: 100%; width: 100%; scrollbar-width: thin; scrollbar-color: #ccc transparent;">
                        <table class="table table-bordered" style="vertical-align: middle; min-width: 2500px; width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-center" style="position: sticky; left: 0; background-color: rgb(215, 215, 215); z-index: 2;">No.</th>
                                    <th style="position: sticky; left: 40px; background-color: rgb(215, 215, 215); z-index: 2; min-width: 280px;" class="text-center">Nama</th>
                                    <th style="min-width: 120px; background-color:rgb(243, 243, 243);" class="text-center">Foto</th>
                                    <th style="min-width: 150px; background-color:rgb(243, 243, 243);" class="text-center">Username</th>
                                    <th style="min-width: 180px; background-color:rgb(243, 243, 243);" class="text-center">Lokasi</th>
                                    <th style="min-width: 180px; background-color:rgb(243, 243, 243);" class="text-center">Divisi</th>
                                    <th style="min-width: 200px; background-color:rgb(243, 243, 243);" class="text-center">Role</th>
                                    <th style="min-width: 150px; background-color:rgb(243, 243, 243);" class="text-center">Dashboard</th>
                                    <th style="min-width: 200px; background-color:rgb(243, 243, 243);" class="text-center">Masa Berlaku</th>
                                    <th style="min-width: 150px; background-color:rgb(243, 243, 243);" class="text-center">Kartu</th>
                                    <th class="text-center" style="position: sticky; right: 0; background-color: rgb(215, 215, 215); z-index: 2; width: 120px; min-width: 120px; max-width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($data_user) <= 0)
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak Ada Data</td>
                                    </tr>
                                @else
                                    @foreach ($data_user as $key => $du)
                                        <tr>
                                            <td class="text-center" style="position: sticky; left: 0; background-color: rgb(235, 235, 235); z-index: 1;">{{ ($data_user->currentpage() - 1) * $data_user->perpage() + $key + 1 }}.</td>
                                            <td style="position: sticky; left: 40px; background-color: rgb(235, 235, 235); z-index: 1;">{{ $du->name }}</td>
                                            <td class="text-center">
                                                @if($du->foto_karyawan == null)
                                                    <img style="width: 80px; border-radius: 50px" src="{{ url('assets/img/foto_default.jpg') }}" alt="{{ $du->name ?? '-' }}">
                                                @else
                                                   <img 
                                        style="width: 80px; border-radius: 50px; cursor:pointer"
                                        src="{{ asset($du->foto_karyawan) }}" 
                                        alt="{{ $du->name ?? '-' }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#fotoModal"
                                        onclick="showImage('{{ asset($du->foto_karyawan) }}')"
                                    >
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $du->username ?? '-' }}</td>
                                            <td>{{ $du->Lokasi->nama_lokasi ?? '-' }}</td>
                                            <td>{{ $du->Jabatan->nama_jabatan ?? '-' }}</td>
                                            <td class="text-center">
                                                @if (count($du->roles) > 0)
                                                    @foreach ($du->roles as $role)
                                                        <div class="badge" style="color: rgb(21, 47, 118); background-color:rgba(192, 218, 254, 0.889); border-radius:10px;">{{ $role->name ?? '-' }}</div>
                                                        <br>
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $du->is_admin ?? '-' }}</td>
                                            <td class="text-center">
                                                @if ($du->masa_berlaku)
                                                    @php
                                                        Carbon\Carbon::setLocale('id');
                                                        $masa_berlaku = Carbon\Carbon::createFromFormat('Y-m-d', $du->masa_berlaku);
                                                        $new_masa_berlaku = $masa_berlaku->translatedFormat('d F Y');
                                                    @endphp
                                                    @if ($du->masa_berlaku <= date('Y-m-d'))
                                                        <span class="btn btn-xs"  style="color: rgba(78, 26, 26, 0.889); background-color:rgb(242, 170, 170); border-radius:10px;">{{ $new_masa_berlaku  }}</span> <br> <span class="btn btn-xs mt-2"  style="color: rgba(78, 26, 26, 0.889); background-color:rgb(242, 170, 170); border-radius:10px;">Non-Aktif</span>
                                                    @else
                                                        <span class="btn btn-xs" style="color: rgba(20, 78, 7, 0.889); background-color:rgb(186, 238, 162); border-radius:10px;">{{ $new_masa_berlaku }}</span> <br> <span class="btn btn-xs mt-2" style="color: rgba(20, 78, 7, 0.889); background-color:rgb(186, 238, 162); border-radius:10px;">Aktif</span>
                                                    @endif
                                                @else
                                                    <span style="font-size: 30px">♾️</span> <br> <span class="btn btn-xs mt-2" style="color: rgba(20, 78, 7, 0.889); background-color:rgb(186, 238, 162); border-radius:10px;">Aktif</span>
                                                @endif
                                            </td>
                                            <td><a href="{{ url('/pegawai/qrcode/'.$du->id) }}" class="btn" style="width: 150px; background-color:rgb(196, 196, 196)"><i class="fas fa-qrcode"></i> Qrcode</a></td>
                                            <td style="position: sticky; right: 0; background-color: rgb(235, 235, 235); z-index: 1;"z>
                                                <ul class="action">
                                                    <li class="edit me-2"><a href="{{ url('/pegawai/detail/'.$du->id) }}" title="Edit Pegawai"><i class="icon-pencil-alt"></i></a></li>

                                                    <li class="me-2"><a href="{{ url('/pegawai/edit-password/'.$du->id) }}" title="Ganti Password"><i class="fa fa-solid fa-key" style="color: rgb(11, 18, 222)"></i></a></li>

                                                    <li class="me-2"> <a href="{{ url('/pegawai/shift/'.$du->id) }}" title="Input Shift Pegawai"><i style="color:coral" class="fa fa-solid fa-clock"></i></a></li>

                                                    <li class="me-2"> <a href="{{ url('/pegawai/dinas-luar/'.$du->id) }}" title="Input Dinas Luar Pegawai"><i style="color:rgb(43, 198, 203)" class="fa fa-solid fa-route"></i></a></li>

                                                    <li class="me-2"> <a href="{{ url('/pegawai/kontrak/'.$du->id) }}" title="Kontrak Kerja"><i data-feather="trending-up"> </i></a></li>

                                                    <li class="me-2">
                                                        @if ($du->foto_face_recognition)
                                                            <a href="javascript:void(0)" onclick="confirmRekamUlang({{ $du->id }})" title="Rekam Ulang Face Recognition">
                                                                <i style="color: green" class="fa fa-solid fa-camera-retro" title="Sudah direkam - Klik untuk rekam ulang"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ url('/pegawai/face/'.$du->id) }}" title="Rekam Face Recognition">
                                                                <i style="color: black" class="fa fa-solid fa-camera"></i>
                                                            </a>
                                                    @endif
                                                    </li>

                                                    <li class="delete">
                                                        @if ($du->hasRole('admin'))
                                                            <button title="Delete Admin" class="border-0" style="background-color: transparent;" onclick="confirmDeleteAdmin({{ $du->id }}, '{{ $du->name }}')"><i class="icon-trash"></i></button>
                                                        @else
                                                        <form action="{{ url('/pegawai/delete/'.$du->id) }}" method="post">
                                                            @method('delete')
                                                            @csrf
                                                            <button title="Delete Pegawai" class="border-0" style="background-color: transparent;" onClick="return confirm('Are You Sure')"><i class="icon-trash"></i></button>
                                                        </form>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end me-4 mt-4">
                        {{ $data_user->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="fotoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-body">
        <img id="previewFoto" src="" style="width:100%; border-radius:10px;">
      </div>
    </div>
  </div>
</div>

    @push('style')
    <style>
        /* Custom scrollbar untuk tabel user */
        .table-responsive::-webkit-scrollbar {
            height: 10px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border-radius: 10px;
            border: 2px solid #f1f1f1;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
        }

        /* Firefox scrollbar */
        .table-responsive {
            scrollbar-width: thin;
            scrollbar-color: #007bff #f1f1f1;
        }

        /* Memperbaiki sticky columns untuk scroll horizontal yang lebih baik */
        .table-responsive .table th[style*="position: sticky"],
        .table-responsive .table td[style*="position: sticky"] {
            z-index: 10;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            padding: 12px 8px !important;
        }

        /* Styling untuk tabel yang lebih lebar */
        .table-responsive .table {
            margin-bottom: 0;
            font-size: 14px;
        }

        .table-responsive .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .table-responsive .table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        /* Sticky column kanan (Actions) dengan shadow yang lebih baik */
        .table-responsive .table th[style*="right: 0"],
        .table-responsive .table td[style*="right: 0"] {
            box-shadow: -2px 0 5px rgba(0,0,0,0.1);
        }

        /* Memperbaiki background untuk sticky columns saat scroll */
        .table-responsive .table th[style*="left:"],
        .table-responsive .table td[style*="left:"] {
            background-color: #d7d7d7 !important;
        }

        .table-responsive .table th[style*="right:"],
        .table-responsive .table td[style*="right:"] {
            background-color: #ebebeb !important;
        }

        /* Hover effect untuk tabel rows */
        .table-responsive .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        /* Scroll feedback visual */
        .table-responsive.scrolled .table th[style*="left:"],
        .table-responsive.scrolled .table td[style*="left:"] {
            box-shadow: 2px 0 8px rgba(0,0,0,0.15);
        }

        .table-responsive.scrolled-full .table th[style*="right:"],
        .table-responsive.scrolled-full .table td[style*="right:"] {
            box-shadow: -2px 0 8px rgba(0,0,0,0.15);
        }

        /* Indicator untuk scroll */
        .table-responsive::after {
            content: "⟷ Scroll untuk melihat semua kolom ⟷";
            position: absolute;
            bottom: -25px;
            right: 10px;
            font-size: 11px;
            color: #6c757d;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .table-responsive.scrolled::after {
            opacity: 0.7;
        }

        .table-responsive {
            position: relative;
            padding-bottom: 10px;
        }

        /* Smooth scrolling behavior */
        .table-responsive {
            scroll-behavior: smooth;
        }

        /* Memperbaiki tampilan saat print */
        @media print {
            .table-responsive {
                overflow-x: visible !important;
            }

            .table {
                min-width: auto !important;
                width: 100% !important;
            }
        }

        /* Responsive untuk mobile */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 12px;
            }

            .table-responsive .table th,
            .table-responsive .table td {
                padding: 8px 4px;
            }

            .table-responsive .table th[style*="min-width"],
            .table-responsive .table td[style*="min-width"] {
                min-width: 120px;
            }

            /* Sembunyikan beberapa kolom di mobile untuk efisiensi */
            .table-responsive .table th:nth-child(3),
            .table-responsive .table td:nth-child(3),
            .table-responsive .table th:nth-child(8),
            .table-responsive .table td:nth-child(8) {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .table-responsive .table th[style*="min-width"],
            .table-responsive .table td[style*="min-width"] {
                min-width: 100px;
            }

            /* Sembunyikan kolom tambahan di layar kecil */
            .table-responsive .table th:nth-child(4),
            .table-responsive .table td:nth-child(4),
            .table-responsive .table th:nth-child(5),
            .table-responsive .table td:nth-child(5) {
                display: none;
            }
        }
    </style>
    @endpush

    @push('script')
    <script>
        $(document).ready(function() {
            // Function untuk konfirmasi rekam ulang face recognition
            window.confirmRekamUlang = function(userId) {
                Swal.fire({
                    title: 'Rekam Ulang Face Recognition?',
                    text: 'Face Recognition sudah pernah direkam. Data sebelumnya akan diganti jika Anda melanjutkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Rekam Ulang',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/pegawai/face/' + userId;
                    }
                });
            };

            // Function untuk konfirmasi hapus admin dengan pengecekan admin terakhir
            window.confirmDeleteAdmin = function(userId, userName) {
                // Cek apakah ini admin terakhir via AJAX
                $.ajax({
                    url: '/pegawai/check-admin-count',
                    type: 'GET',
                    success: function(response) {
                        if (response.total_admins <= 1) {
                            // Admin terakhir, tampilkan popup peringatan
                            Swal.fire({
                                title: 'Tidak Dapat Menghapus Admin Terakhir',
                                html: `
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-shield-alt fa-4x text-danger"></i>
                                        </div>
                                        <p class="mb-2"><strong>${userName}</strong> adalah admin terakhir di sistem.</p>
                                        <p class="text-muted">Sistem minimal harus memiliki satu admin untuk menjaga keamanan dan fungsionalitas sistem.</p>
                                        <div class="alert alert-danger mt-3">
                                            <small><i class="fas fa-exclamation-triangle"></i> Penghapusan dibatalkan untuk melindungi sistem.</small>
                                        </div>
                                    </div>
                                `,
                                icon: 'error',
                                confirmButtonText: 'Mengerti',
                                confirmButtonColor: '#dc3545',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });
                        } else {
                            // Ada admin lain, lanjutkan konfirmasi penghapusan
                            Swal.fire({
                                title: 'Hapus Admin?',
                                html: `
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-user-times fa-3x text-warning"></i>
                                        </div>
                                        <p>Yakin ingin menghapus admin <strong>${userName}</strong>?</p>
                                        <p class="text-muted">Admin lain masih tersedia di sistem (${response.total_admins - 1} admin tersisa).</p>
                                    </div>
                                `,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#dc3545',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Ya, Hapus Admin',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Submit form penghapusan
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = `/pegawai/delete/${userId}`;

                                    const methodInput = document.createElement('input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    methodInput.value = 'DELETE';

                                    const csrfInput = document.createElement('input');
                                    csrfInput.type = 'hidden';
                                    csrfInput.name = '_token';
                                    csrfInput.value = '{{ csrf_token() }}';

                                    form.appendChild(methodInput);
                                    form.appendChild(csrfInput);
                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memeriksa status admin.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            };

            // Smooth scroll behavior untuk tabel
            $('.table-responsive').on('scroll', function() {
                const scrollLeft = $(this).scrollLeft();
                const scrollWidth = $(this)[0].scrollWidth;
                const clientWidth = $(this)[0].clientWidth;

                // Menambahkan class saat scroll ke kanan penuh
                if (scrollLeft > 0) {
                    $(this).addClass('scrolled');
                } else {
                    $(this).removeClass('scrolled');
                }

                // Menambahkan class saat scroll ke kanan maksimal
                if (scrollLeft >= scrollWidth - clientWidth - 1) {
                    $(this).addClass('scrolled-full');
                } else {
                    $(this).removeClass('scrolled-full');
                }
            });

            // Auto-scroll ke kanan saat tabel dimuat (opsional, bisa dihapus jika tidak diinginkan)
            // setTimeout(function() {
            //     $('.table-responsive').animate({scrollLeft: 100}, 500);
            // }, 1000);

            // Memperbaiki positioning sticky columns saat resize window
            $(window).on('resize', function() {
                // Reset scroll position jika perlu
                $('.table-responsive').scrollLeft(0);
            });
        });
        
        function showImage(src){
    document.getElementById('previewFoto').src = src;
}
    </script>
    @endpush
@endsection




