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
                    <form action="{{ url('/laporan-kinerja') }}">
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
                            <div class="col-3">
                                <input type="datetime" class="form-control" name="mulai" placeholder="Tanggal Mulai" id="mulai" value="{{ request('mulai') }}">
                            </div>
                            <div class="col-3">
                                <input type="datetime" class="form-control" name="akhir" placeholder="Tanggal Akhir" id="akhir" value="{{ request('akhir') }}">
                            </div>
                            <div class="col-3">
                                <button type="submit" id="search"class="border-0 mt-3" style="background-color: transparent;"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="mytable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Pegawai</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Kinerja</th>
                                    <th>Bobot Penilaian</th>
                                    <th>Penilaian Berjalan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporan_kinerja as $key => $lk)
                                    <tr>
                                        <td>{{ ($laporan_kinerja->currentpage() - 1) * $laporan_kinerja->perpage() + $key + 1 }}.</td>
                                        <td>{{ $lk->user->name ?? '-' }}</td>
                                        <td>{{ $lk->tanggal ?? '-' }}</td>
                                        <td>{{ $lk->jenis->nama ?? '-' }}</td>
                                        <td>{{ $lk->nilai ?? '-' }}</td>
                                        <td>{{ $lk->penilaian_berjalan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        {{ $laporan_kinerja->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    @push('script')
        <script>
            $(document).ready(function() {
                $('#mulai').change(function(){
                    var mulai = $(this).val();
                $('#akhir').val(mulai);
                });
            });
        </script>
    @endpush
@endsection
