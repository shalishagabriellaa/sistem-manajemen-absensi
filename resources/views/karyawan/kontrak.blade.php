@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

* {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

:root {
    --dash-blue: #3b4cca;
    --dash-blue-dk: #2d3db4;
    --dash-blue-lt: #5c6ed4;

    --slate-50: #f8fafc;
    --slate-100: #f1f5f9;
    --slate-200: #e2e8f0;
    --slate-300: #cbd5e1;
    --slate-500: #64748b;
    --slate-700: #334155;
}

.table-card {
    border-radius: 16px;
    border: 1px solid var(--slate-200);
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid var(--slate-200);
    background: linear-gradient(135deg, #fff, #f8fafc);
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--slate-700);
    display: flex;
    align-items: center;
    gap: 8px;
}

.employee-name {
    font-size: 16px;
    font-weight: 600;
    color: var(--slate-500);
    display: flex;
    align-items: center;
    gap: 6px;
}

.action-btn {
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    border: 1px solid var(--slate-200);
    background: white;
    color: var(--slate-700);
    transition: .2s;
    text-decoration: none;
}

.action-btn:hover {
    background: var(--dash-blue);
    color: white;
    border-color: var(--dash-blue);
}

.table-body-wrap {
    padding: 20px;
}

.table thead th {
    background: var(--slate-100);
    color: var(--slate-700);
    font-weight: 600;
    font-size: 13px;
    border-bottom: 1px solid var(--slate-200);
    white-space: nowrap;
    text-align: center;
}

.table tbody td {
    font-size: 13px;
    color: var(--slate-700);
    vertical-align: middle;
}

.table-responsive {
    border-radius: 10px;
    border: 1px solid var(--slate-200);
}

.sticky-left-0 {
    position: sticky;
    left: 0;
    background-color: var(--slate-100);
    z-index: 2;
}

.sticky-left-40 {
    position: sticky;
    left: 40px;
    background-color: var(--slate-100);
    z-index: 2;
    min-width: 170px;
}

.sticky-right {
    position: sticky;
    right: 0;
    background-color: var(--slate-100);
    z-index: 2;
    min-width: 230px;
}

tbody .sticky-left-0,
tbody .sticky-left-40,
tbody .sticky-right {
    background-color: var(--slate-50);
}

.download-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    color: var(--dash-blue);
    text-decoration: none;
    padding: 4px 10px;
    border-radius: 6px;
    border: 1px solid var(--dash-blue);
    transition: .2s;
}

.download-link:hover {
    background: var(--dash-blue);
    color: white;
}

.empty-row td {
    padding: 40px;
    color: var(--slate-500);
    font-size: 14px;
}
</style>

<br>

<div class="row">
    <div class="col-md-12">
        <div class="card table-card">

            {{-- HEADER --}}
            <div class="table-header">
                <div>
                    <div class="form-title">
                        <i class="fas fa-file-contract" style="color:#3b4cca"></i>
                        {{ $title }}
                    </div>
                    <div class="employee-name mt-1">
                        <i class="fas fa-user" style="color:#64748b; font-size:13px;"></i>
                        {{ $user->name }}
                    </div>
                </div>
                <a href="{{ url('/pegawai') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            {{-- TABLE --}}
            <div class="table-body-wrap">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="sticky-left-0">No.</th>
                                <th class="sticky-left-40">Tanggal</th>
                                <th style="min-width:350px;">Jenis Kontrak</th>
                                <th style="min-width:170px;">Tanggal Mulai</th>
                                <th style="min-width:170px;">Tanggal Selesai</th>
                                <th style="min-width:200px;">Periode</th>
                                <th style="min-width:400px;">Keterangan</th>
                                <th class="sticky-right">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($kontraks) <= 0)
                                <tr class="empty-row">
                                    <td colspan="8" class="text-center">
                                        <i class="fas fa-inbox" style="font-size:24px; color:#cbd5e1; display:block; margin-bottom:8px;"></i>
                                        Tidak Ada Data
                                    </td>
                                </tr>
                            @else
                                @foreach ($kontraks as $key => $kontrak)
                                    <tr>
                                        <td class="text-center sticky-left-0">
                                            {{ ($kontraks->currentpage() - 1) * $kontraks->perpage() + $key + 1 }}.
                                        </td>
                                        <td class="sticky-left-40">
                                            @if ($kontrak->tanggal)
                                                @php
                                                    Carbon\Carbon::setLocale('id');
                                                    $new_tanggal = \Carbon\Carbon::parse($kontrak->tanggal)->translatedFormat('d F Y');
                                                @endphp
                                                {{ $new_tanggal }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $kontrak->jenis_kontrak ?? '-' }}</td>
                                        <td class="text-center">
                                            @if ($kontrak->tanggal_mulai)
                                                @php
                                                    Carbon\Carbon::setLocale('id');
                                                    $new_tanggal_mulai = \Carbon\Carbon::parse($kontrak->tanggal_mulai)->translatedFormat('d F Y');
                                                @endphp
                                                {{ $new_tanggal_mulai }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($kontrak->tanggal_selesai)
                                                @php
                                                    Carbon\Carbon::setLocale('id');
                                                    $new_tanggal_selesai = \Carbon\Carbon::parse($kontrak->tanggal_selesai)->translatedFormat('d F Y');
                                                @endphp
                                                {{ $new_tanggal_selesai }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                if ($kontrak->tanggal_mulai && $kontrak->tanggal_selesai) {
                                                    $startDate = \Carbon\Carbon::parse($kontrak->tanggal_mulai);
$currentDate = \Carbon\Carbon::parse($kontrak->tanggal_selesai);
                                                    if ($startDate->greaterThan($currentDate)) {
                                                        $periode = "0 Tahun, 0 Bulan, 0 Hari.";
                                                    } else {
                                                        $employmentDuration = $currentDate->diff($startDate);
                                                        $periode = "{$employmentDuration->y} Tahun, {$employmentDuration->m} Bulan, {$employmentDuration->d} Hari.";
                                                    }
                                                } else {
                                                    $periode = '-';
                                                }
                                            @endphp
                                            {{ $periode }}
                                        </td>
                                        <td>{!! $kontrak->keterangan ? nl2br(e($kontrak->keterangan)) : '-' !!}</td>
                                        <td class="sticky-right">
                                            @if ($kontrak->kontrak_file_path)
                                                <a href="{{ asset('storage/'.$kontrak->kontrak_file_path) }}" class="download-link">
                                                    <i class="fa fa-download"></i>
                                                    {{ $kontrak->kontrak_file_name }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $kontraks->links() }}
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