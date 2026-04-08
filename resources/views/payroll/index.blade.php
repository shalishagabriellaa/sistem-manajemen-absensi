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
                        {{-- PERBAIKAN: arahkan ke /payroll/tambah bukan /rekap-data --}}
                        <a href="{{ url('/payroll/tambah') }}" class="btn btn-primary">+ Tambah Payroll</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ url('/payroll') }}">
                        @php
                            $bulan = [
                                ['id'=>'1','bulan'=>'Januari'],['id'=>'2','bulan'=>'Februari'],
                                ['id'=>'3','bulan'=>'Maret'],['id'=>'4','bulan'=>'April'],
                                ['id'=>'5','bulan'=>'Mei'],['id'=>'6','bulan'=>'Juni'],
                                ['id'=>'7','bulan'=>'Juli'],['id'=>'8','bulan'=>'Agustus'],
                                ['id'=>'9','bulan'=>'September'],['id'=>'10','bulan'=>'Oktober'],
                                ['id'=>'11','bulan'=>'November'],['id'=>'12','bulan'=>'Desember'],
                            ];
                            $last = date('Y')-10;
                            $now  = date('Y');
                        @endphp
                        <div class="row">
                            <div class="col-3">
                                <select name="tahun" class="form-control selectpicker" data-live-search="true">
                                    <option value="">Tahun</option>
                                    @for ($i = $now; $i >= $last; $i--)
                                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-3">
                                <select name="bulan" class="form-control selectpicker" data-live-search="true">
                                    <option value="">Bulan</option>
                                    @foreach($bulan as $bul)
                                        <option value="{{ $bul['id'] }}" {{ request('bulan') == $bul['id'] ? 'selected' : '' }}>{{ $bul['bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="border-0 mt-3" style="background:transparent;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @if(session('success'))
                    <div class="alert alert-success mx-3 mt-3">{{ session('success') }}</div>
                @endif

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nomor Gaji</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Periode</th>
                                    <th>Grand Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $key => $d)
                                    @php
                                        $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                                            'Juli','Agustus','September','Oktober','November','Desember'];
                                    @endphp
                                    <tr>
                                        <td>{{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}.</td>
                                        <td>{{ $d->no_gaji }}</td>
                                        <td>{{ $d->user->name }}</td>
                                        <td>{{ optional(optional($d->user)->Jabatan)->nama_jabatan ?? '-' }}</td>
                                        <td>{{ $namaBulan[$d->bulan] ?? '-' }} {{ $d->tahun }}</td>
                                        <td>Rp {{ number_format($d->grand_total) }}</td>
                                        <td>
                                            <ul class="action">
                                                <li class="me-2">
                                                    <a href="{{ url('/payroll/'.$d->id.'/download') }}" target="_blank" title="Cetak Slip Gaji">
                                                        <i style="color:blue" class="fa fa-solid fa-print"></i>
                                                    </a>
                                                </li>
                                                @can('admin')
                                                    <li>
                                                        <a href="{{ url('/payroll/'.$d->id.'/edit') }}" title="Edit">
                                                            <i style="color:brown" class="fa fa-solid fa-edit"></i>
                                                        </a>
                                                    </li>
                                                    <li class="delete">
                                                        <form action="{{ url('/payroll/'.$d->id.'/delete') }}" method="post" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button class="border-0" style="background:transparent" onclick="return confirm('Yakin hapus data ini?')">
                                                                <i class="fa fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada data payroll.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection