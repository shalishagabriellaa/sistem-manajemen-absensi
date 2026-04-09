<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Slip Gaji - {{ $data->no_gaji }}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      color: #000;
      background: #fff;
    }
    .page {
      width: 750px;
      margin: 0 auto;
      padding: 18px 22px;
      border: 2px solid #000;
    }

    /* ===== HEADER ===== */
    .header-wrap {
      display: table;
      width: 100%;
      border-bottom: 2px solid #003366;
      padding-bottom: 10px;
      margin-bottom: 14px;
    }
    .header-left {
      display: table-cell;
      width: 60%;
      vertical-align: middle;
    }
    .header-left .pt-name {
      font-size: 18px;
      font-weight: bold;
      color: #003366;
      letter-spacing: 0.5px;
      line-height: 1.2;
    }
    .header-left .pt-location {
      font-size: 10px;
      color: #555;
      margin-top: 2px;
    }
    .header-left .slip-title {
      font-size: 12px;
      font-weight: bold;
      color: #003366;
      margin-top: 6px;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    .header-right {
      display: table-cell;
      width: 40%;
      text-align: right;
      vertical-align: middle;
    }
    .header-right img {
      max-width: 140px;
      max-height: 70px;
    }

    /* ===== INFO KARYAWAN ===== */
    .info-wrap {
      display: table;
      width: 100%;
      margin: 0 0 14px 0;
      border: 1px solid #ccc;
      border-radius: 3px;
      background: #f8f9fc;
    }
    .info-left, .info-right {
      display: table-cell;
      width: 50%;
      vertical-align: top;
      padding: 10px 14px;
    }
    .info-right {
      border-left: 1px solid #ddd;
    }
    .info-row {
      margin-bottom: 5px;
      font-size: 11px;
      display: table;
      width: 100%;
    }
    .info-label {
      display: table-cell;
      font-weight: bold;
      width: 115px;
      color: #333;
    }
    .info-colon {
      display: table-cell;
      width: 10px;
    }
    .info-value {
      display: table-cell;
    }
    /* Kanan — label lebih pendek */
    .info-right .info-label {
      width: 105px;
    }

    /* ===== SECTION TITLE ===== */
    .section-title {
      font-size: 10.5px;
      font-weight: bold;
      color: #fff;
      background: #003366;
      padding: 4px 8px;
      margin-bottom: 0;
      letter-spacing: 0.5px;
    }

    /* ===== ABSENSI TABLE ===== */
    .absen-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
      font-size: 10.5px;
    }
    .absen-table th {
      background: #dce8f5;
      color: #003366;
      font-weight: bold;
      padding: 4px 8px;
      border: 1px solid #aac4e0;
      text-align: left;
    }
    .absen-table td {
      padding: 3px 8px;
      border: 1px solid #d0d0d0;
    }
    .absen-table td.dur {
      text-align: right;
      font-weight: bold;
    }
    .absen-table tr:nth-child(even) td { background: #f5f9ff; }

    /* ===== PENDAPATAN / POTONGAN TABLE ===== */
    .keu-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
      font-size: 10.5px;
    }
    .keu-table th {
      background: #dce8f5;
      color: #003366;
      font-weight: bold;
      padding: 4px 8px;
      border: 1px solid #aac4e0;
      text-align: left;
    }
    .keu-table th.right { text-align: right; }
    .keu-table td {
      padding: 3px 8px;
      border: 1px solid #d0d0d0;
    }
    .keu-table td.nilai {
      text-align: right;
      font-weight: bold;
    }
    .keu-table tr:nth-child(even) td { background: #f5f9ff; }
    .keu-table tr.subtotal td {
      background: #e8f0fb;
      font-weight: bold;
      border-top: 1.5px solid #aac4e0;
    }
    .keu-table tr.subtotal td.nilai { text-align: right; }

    /* ===== GRAND TOTAL ===== */
    .grand-total-wrap {
      display: table;
      width: 100%;
      background: #003366;
      color: #fff;
      margin-top: 4px;
      border-radius: 2px;
    }
    .grand-total-label {
      display: table-cell;
      padding: 9px 14px;
      font-weight: bold;
      font-size: 13px;
      letter-spacing: 1px;
    }
    .grand-total-value {
      display: table-cell;
      text-align: right;
      padding: 9px 14px;
      font-weight: bold;
      font-size: 15px;
      letter-spacing: 0.5px;
    }

    /* ===== FOOTER INFO ===== */
    .footer-info {
      margin-top: 10px;
      font-size: 9.5px;
      color: #444;
      border-top: 1px solid #ccc;
      padding-top: 8px;
    }
    .footer-info .rek-line {
      margin-bottom: 4px;
    }
    .footer-info .masa-kerja {
      margin-bottom: 4px;
    }
    .footer-info .system-note {
      color: #888;
      font-size: 9px;
      margin-top: 4px;
    }

    @media print {
      .no-print { display: none; }
    }

    .absen-table,
    .absen-table th,
    .absen-table td,
    .keu-table,
    .keu-table th,
    .keu-table td {
      border: none !important;
    }
  </style>
</head>
<body>

@php
  /* ── Hitung data yang dibutuhkan ─────────────────────────── */
  $user     = $data->user;
  $jabatan  = optional(optional($user)->Jabatan)->nama_jabatan ?? '-';

  $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'];
  $bulanStr  = $namaBulan[$data->bulan] ?? $data->bulan;

  /* Logo MET */
  $logo_path = public_path('assets/img/met.png');
  $logo_data = null;
  $logo_mime = null;
  if (file_exists($logo_path)) {
      $logo_mime = mime_content_type($logo_path);
      $logo_data = base64_encode(file_get_contents($logo_path));
  }

  /* Masa kerja dari tgl_join */
  $masaKerja = '';
  if ($user->tgl_join) {
      try {
          $join = \Carbon\Carbon::parse($user->tgl_join);
          $now  = \Carbon\Carbon::now();
          $diff = $join->diff($now);
          $masaKerja = $diff->y . ' tahun, ' . $diff->m . ' bulan, ' . $diff->d . ' hari';
      } catch(\Exception $e) {
          $masaKerja = '-';
      }
  }

  /* Sisa cuti */
  $sisaCuti = $user->izin_cuti ?? 0;

  /* Data absensi */
  $hariMasuk  = $data->jumlah_tunjangan_makan ?? 0;
  $hariAbsen  = $data->jumlah_mangkir ?? 0;
  $jumlahIzin = $data->jumlah_izin ?? 0;
  $jumlahLembur    = $data->jumlah_lembur ?? 0;
  $jumlahTerlambat = $data->jumlah_terlambat ?? 0;
@endphp

<div class="page">

  {{-- HEADER --}}
  <div class="header-wrap">
    <div class="header-left">
      <div class="pt-name">PT Multi Engineering Technologies</div>
      <div class="pt-location">Jambi, Indonesia</div>
      <div class="slip-title">Slip Gaji Karyawan</div>
    </div>
    <div class="header-right">
      @if($logo_data)
        <img src="data:{{ $logo_mime }};base64,{{ $logo_data }}" alt="MET Logo">
      @endif
    </div>
  </div>

  {{-- INFO KARYAWAN --}}
  <div class="info-wrap">
    {{-- Kiri --}}
    <div class="info-left">
      <div class="info-row">
        <span class="info-label">Nama Pegawai</span>
        <span class="info-colon">:</span>
        <span class="info-value"><strong>{{ strtoupper($user->name) }}</strong></span>
      </div>
      <div class="info-row">
        <span class="info-label">Departemen</span>
        <span class="info-colon">:</span>
        <span class="info-value">{{ $jabatan }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Nomor Slip</span>
        <span class="info-colon">:</span>
        <span class="info-value">{{ $data->no_gaji }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Dicetak Tanggal</span>
        <span class="info-colon">:</span>
        <span class="info-value">{{ date('d-M-Y') }}</span>
      </div>
    </div>
    {{-- Kanan --}}
    <div class="info-right">
      <div class="info-row">
        <span class="info-label">Bulan</span>
        <span class="info-colon">:</span>
        <span class="info-value">{{ $bulanStr }} {{ $data->tahun }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Periode</span>
        <span class="info-colon">:</span>
        <span class="info-value">{{ $data->tanggal_mulai }} s/d {{ $data->tanggal_akhir }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Kehadiran</span>
        <span class="info-colon">:</span>
        <span class="info-value">{{ $data->persentase_kehadiran }}%</span>
      </div>
    </div>
  </div>

  {{-- DATA ABSENSI --}}
  <div class="section-title">DATA ABSENSI</div>
  <table class="absen-table">
    <thead>
      <tr>
        <th style="width:60%">Status</th>
        <th style="width:40%; text-align:right">Durasi / Jumlah</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Hari Masuk</td>
        <td class="dur">{{ $hariMasuk }} Hari</td>
      </tr>
      <tr>
        <td>Hari Absen (Mangkir)</td>
        <td class="dur">{{ $hariAbsen }} Hari</td>
      </tr>
      <tr>
        <td>Izin</td>
        <td class="dur">{{ $jumlahIzin }} Hari</td>
      </tr>
      <tr>
        <td>Keterlambatan</td>
        <td class="dur">{{ $jumlahTerlambat }} Kali</td>
      </tr>
      <tr>
        <td>Overtime Hour</td>
        <td class="dur">{{ $jumlahLembur }} Jam</td>
      </tr>
      @if($sisaCuti > 0)
      <tr>
        <td>Sisa Cuti</td>
        <td class="dur">{{ $sisaCuti }} Hari</td>
      </tr>
      @endif
    </tbody>
  </table>

  {{-- PENDAPATAN --}}
  <div class="section-title">PENDAPATAN</div>
  <table class="keu-table">
    <thead>
      <tr>
        <th style="width:65%">Status</th>
        <th class="right" style="width:35%">Nilai (Rp)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Gaji Pokok</td>
        <td class="nilai">{{ number_format($data->gaji_pokok) }}</td>
      </tr>
      <tr>
        <td>Tunjangan Transport</td>
        <td class="nilai">{{ number_format($data->total_tunjangan_transport) }}</td>
      </tr>
      <tr>
        <td>Uang Makan</td>
        <td class="nilai">{{ number_format($data->total_tunjangan_makan) }}</td>
      </tr>
      <tr>
        <td>Tunjangan BPJS Kesehatan</td>
        <td class="nilai">{{ number_format($data->total_tunjangan_bpjs_kesehatan) }}</td>
      </tr>
      <tr>
        <td>Tunjangan BPJS Ketenagakerjaan</td>
        <td class="nilai">{{ number_format($data->total_tunjangan_bpjs_ketenagakerjaan) }}</td>
      </tr>
      <tr>
        <td>Lembur</td>
        <td class="nilai">{{ number_format($data->total_lembur) }}</td>
      </tr>
      <tr>
        <td>Bonus Kehadiran</td>
        <td class="nilai">{{ number_format($data->total_kehadiran) }}</td>
      </tr>
      <tr>
        <td>Bonus Pribadi (KPI)</td>
        <td class="nilai">{{ number_format($data->bonus_pribadi) }}</td>
      </tr>
      <tr>
        <td>Bonus Team (KPI)</td>
        <td class="nilai">{{ number_format($data->bonus_team) }}</td>
      </tr>
      <tr>
        <td>THR</td>
        <td class="nilai">{{ number_format($data->total_thr) }}</td>
      </tr>
      <tr>
        <td>Reimbursement</td>
        <td class="nilai">{{ number_format($data->total_reimbursement) }}</td>
      </tr>
      <tr class="subtotal">
        <td>Total Pendapatan</td>
        <td class="nilai">{{ number_format($data->total_penjumlahan) }}</td>
      </tr>
    </tbody>
  </table>

  {{-- POTONGAN --}}
  <div class="section-title">POTONGAN</div>
  <table class="keu-table">
    <thead>
      <tr>
        <th style="width:65%">Status</th>
        <th class="right" style="width:35%">Nilai (Rp)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Potongan BPJS Kesehatan</td>
        <td class="nilai">{{ number_format($data->total_potongan_bpjs_kesehatan) }}</td>
      </tr>
      <tr>
        <td>Potongan BPJS Ketenagakerjaan</td>
        <td class="nilai">{{ number_format($data->total_potongan_bpjs_ketenagakerjaan) }}</td>
      </tr>
      <tr>
        <td>Potongan Absen (Mangkir)</td>
        <td class="nilai">{{ number_format($data->total_mangkir) }}</td>
      </tr>
      <tr>
        <td>Potongan Izin</td>
        <td class="nilai">{{ number_format($data->total_izin) }}</td>
      </tr>
      <tr>
        <td>Potongan Keterlambatan</td>
        <td class="nilai">{{ number_format($data->total_terlambat) }}</td>
      </tr>
      <tr>
        <td>Kasbon</td>
        <td class="nilai">{{ number_format($data->bayar_kasbon) }}</td>
      </tr>
      <tr>
        <td>Loss</td>
        <td class="nilai">{{ number_format($data->loss) }}</td>
      </tr>
      <tr class="subtotal">
        <td>Total Potongan</td>
        <td class="nilai">{{ number_format($data->total_pengurangan) }}</td>
      </tr>
    </tbody>
  </table>

  {{-- GRAND TOTAL --}}
  <div class="grand-total-wrap">
    <div class="grand-total-label">JUMLAH GAJI DITERIMA</div>
    <div class="grand-total-value">Rp {{ number_format($data->grand_total) }}</div>
  </div>

  {{-- FOOTER INFO --}}
  <div class="footer-info">
    <div class="rek-line">
      Gaji Anda telah ditransfer ke Rekening
      <strong>{{ $user->rekening ?? '-' }}</strong>
      atas nama
      <strong>{{ $user->nama_rekening ?? $user->name }}</strong>
      oleh Sistem HRD Metech | jambimetech@gmail.com
    </div>
    @if($masaKerja)
    <div class="masa-kerja">
      Masa Kerja &nbsp;: <strong>{{ $masaKerja }}</strong>
    </div>
    @endif
    @if($sisaCuti > 0)
    <div>
      Sisa Cuti &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <strong>{{ $sisaCuti }} Hari / Tahun</strong>
    </div>
    @endif
    <div class="system-note">
      Slip gaji ini diterbitkan secara otomatis oleh sistem &mdash; PT Multi Engineering Technologies | jambimetech@gmail.com
    </div>
  </div>

</div>{{-- end .page --}}

</body>
</html>