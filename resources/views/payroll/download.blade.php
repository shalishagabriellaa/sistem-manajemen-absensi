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
      padding: 15px 20px;
      border: 2px solid #000;
    }

    /* ===== HEADER ===== */
    .header-wrap {
      display: table;
      width: 100%;
      border-bottom: 2px solid #000;
      padding-bottom: 8px;
      margin-bottom: 6px;
    }
    .header-logo {
      display: table-cell;
      width: 180px;
      vertical-align: middle;
    }
    .header-logo img {
      max-width: 160px;
      max-height: 60px;
    }
    .header-logo .company-name {
      font-size: 20px;
      font-weight: bold;
      color: #003366;
      letter-spacing: 1px;
    }
    .header-logo .company-tagline {
      font-size: 9px;
      color: #666;
      font-style: italic;
    }
    .header-title {
      display: table-cell;
      text-align: center;
      vertical-align: middle;
    }
    .header-title h1 {
      font-size: 22px;
      font-weight: bold;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #003366;
    }

    /* ===== INFO KARYAWAN ===== */
    .info-wrap {
      display: table;
      width: 100%;
      margin: 8px 0;
      border-bottom: 1px solid #999;
      padding-bottom: 6px;
    }
    .info-left, .info-right {
      display: table-cell;
      width: 50%;
      vertical-align: top;
    }
    .info-right { text-align: right; }
    .info-row {
      margin-bottom: 2px;
      font-size: 11px;
    }
    .info-label { font-weight: bold; }

    /* ===== MAIN TABLE ===== */
    .main-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 8px;
    }
    .main-table th {
      background: #003366;
      color: #fff;
      text-align: center;
      padding: 5px 4px;
      font-size: 11px;
      border: 1px solid #003366;
    }
    .main-table td {
      padding: 3px 5px;
      font-size: 10.5px;
      border: 1px solid #ccc;
      vertical-align: top;
    }
    .col-absen  { width: 30%; }
    .col-pend   { width: 35%; }
    .col-pot    { width: 35%; }

    .item-label { display: inline-block; min-width: 120px; }
    .item-jumlah { display: inline-block; min-width: 30px; text-align: right; }
    .item-nilai { float: right; text-align: right; }
    .row-item { display: block; clear: both; margin-bottom: 1px; overflow: hidden; }

    .subtotal-row td {
      font-weight: bold;
      background: #f0f0f0;
      border-top: 1px solid #999;
    }

    /* ===== FOOTER TOTAL ===== */
    .grand-total-wrap {
      margin-top: 6px;
      border: 2px solid #003366;
      background: #003366;
      color: #fff;
      display: table;
      width: 100%;
    }
    .grand-total-label {
      display: table-cell;
      padding: 8px 12px;
      font-weight: bold;
      font-size: 13px;
      letter-spacing: 1px;
    }
    .grand-total-value {
      display: table-cell;
      text-align: right;
      padding: 8px 12px;
      font-weight: bold;
      font-size: 15px;
      letter-spacing: 1px;
    }

    .sisa-cuti {
      margin-top: 6px;
      font-size: 10px;
      color: #555;
    }

    /* Print-only button hidden */
    @media print {
      .no-print { display: none; }
    }
  </style>
</head>
<body>

{{-- TOMBOL AKSI (tidak akan tercetak) --}}
<div class="no-print" style="text-align:center; padding:10px; background:#f5f5f5;">
  <button onclick="window.print()" style="padding:8px 20px; margin:4px; background:#003366; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:13px;">
    🖨️ Cetak / Simpan PDF
  </button>
  <a href="{{ url('/payroll') }}" style="padding:8px 20px; margin:4px; background:#666; color:#fff; border-radius:4px; text-decoration:none; font-size:13px; display:inline-block;">
    ← Kembali
  </a>
</div>

@php
  $settings = App\Models\settings::first();
  $logo_path = storage_path('app/public/' . ($settings->logo ?? ''));
  $logo_data = null;
  $logo_mime = null;
  if ($settings->logo && file_exists($logo_path)) {
      $logo_mime = mime_content_type($logo_path);
      $logo_data = base64_encode(file_get_contents($logo_path));
  }

  $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'];
  $bulanStr = $namaBulan[$data->bulan] ?? $data->bulan;

  $user = $data->user;
  $jabatan = optional(optional($user)->Jabatan)->nama_jabatan ?? '-';
@endphp

<div class="page">

  {{-- HEADER --}}
  <div class="header-wrap">
    <div class="header-logo">
      @if($logo_data)
        <img src="data:{{ $logo_mime }};base64,{{ $logo_data }}" alt="logo">
      @else
        <div class="company-name">{{ $settings->name ?? 'Perusahaan' }}</div>
        <div class="company-tagline">{{ $settings->alamat ?? '' }}</div>
      @endif
    </div>
    <div class="header-title">
      <h1>Slip Gaji Karyawan</h1>
    </div>
  </div>

  {{-- INFO KARYAWAN --}}
  <div class="info-wrap">
    <div class="info-left">
      <div class="info-row">
        <span class="info-label">NIP &nbsp;&nbsp;:</span>
        {{ $user->username ?? $user->id }}
        &nbsp;&nbsp;&nbsp;
        <strong>{{ strtoupper($user->name) }}</strong>
      </div>
      <div class="info-row">
        <span class="info-label">Periode :</span>
        {{ $data->tanggal_mulai }} s/d {{ $data->tanggal_akhir }}
      </div>
      <div class="info-row">
        <span class="info-label">Dept &nbsp;&nbsp;:</span>
        {{ $jabatan }}
      </div>
    </div>
    <div class="info-right">
      <div class="info-row"><span class="info-label">Slip No. &nbsp;&nbsp;:</span> {{ $data->no_gaji }}</div>
      <div class="info-row"><span class="info-label">Dicetak Tgl :</span> {{ date('d-M-y') }}</div>
      <div class="info-row"><span class="info-label">Bulan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span> {{ $bulanStr }} {{ $data->tahun }}</div>
      <div class="info-row"><span class="info-label">Kehadiran &nbsp;:</span> {{ $data->persentase_kehadiran }}%</div>
    </div>
  </div>

  {{-- TABEL UTAMA: 3 KOLOM --}}
  <table class="main-table">
    <thead>
      <tr>
        <th class="col-absen">DATA ABSENSI</th>
        <th class="col-pend">PENDAPATAN</th>
        <th class="col-pot">POTONGAN</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        {{-- KOLOM 1: ABSENSI --}}
        <td class="col-absen">
          @php
            // Hitung dari data payroll yang tersedia
            $hariMasuk = $data->jumlah_tunjangan_makan ?? 0; // asumsi hari hadir = hari makan
            $hariAbsen = $data->jumlah_mangkir ?? 0;
            $hariIzin  = $data->jumlah_izin ?? 0;
          @endphp
          <div class="row-item">
            <span class="item-label">Hari Masuk</span>
            <span class="item-nilai">{{ $hariMasuk }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Hari Absen</span>
            <span class="item-nilai">{{ $hariAbsen }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Ijin</span>
            <span class="item-nilai">{{ $hariIzin }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Keterlambatan</span>
            <span class="item-nilai">{{ $data->jumlah_terlambat }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">OT Hour</span>
            <span class="item-nilai">{{ $data->jumlah_lembur }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Sisa Cuti</span>
            <span class="item-nilai">{{ $user->izin_cuti ?? 0 }}</span>
          </div>
        </td>

        {{-- KOLOM 2: PENDAPATAN --}}
        <td class="col-pend">
          <div class="row-item">
            <span class="item-label">Lembur</span>
            <span class="item-nilai">{{ number_format($data->total_lembur) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Gaji Pokok</span>
            <span class="item-nilai">{{ number_format($data->gaji_pokok) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Tj. Transport</span>
            <span class="item-nilai">{{ number_format($data->total_tunjangan_transport) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Uang Makan</span>
            <span class="item-nilai">{{ number_format($data->total_tunjangan_makan) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Tj. BPJS Kes.</span>
            <span class="item-nilai">{{ number_format($data->total_tunjangan_bpjs_kesehatan) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Tj. BPJS TK.</span>
            <span class="item-nilai">{{ number_format($data->total_tunjangan_bpjs_ketenagakerjaan) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Bonus Kehadiran</span>
            <span class="item-nilai">{{ number_format($data->total_kehadiran) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Bonus Pribadi</span>
            <span class="item-nilai">{{ number_format($data->bonus_pribadi) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Bonus Team</span>
            <span class="item-nilai">{{ number_format($data->bonus_team) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Bonus Jackpot</span>
            <span class="item-nilai">{{ number_format($data->bonus_jackpot) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">THR</span>
            <span class="item-nilai">{{ number_format($data->total_thr) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Reimbursement</span>
            <span class="item-nilai">{{ number_format($data->total_reimbursement) }}</span>
          </div>
        </td>

        {{-- KOLOM 3: POTONGAN --}}
        <td class="col-pot">
          <div class="row-item">
            <span class="item-label">Pot. BPJS Kes.</span>
            <span class="item-nilai">{{ number_format($data->total_potongan_bpjs_kesehatan) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Pot. BPJS TK.</span>
            <span class="item-nilai">{{ number_format($data->total_potongan_bpjs_ketenagakerjaan) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Potongan Absen</span>
            <span class="item-nilai">{{ number_format($data->total_mangkir) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Potongan Ijin</span>
            <span class="item-nilai">{{ number_format($data->total_izin) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Potongan Telat</span>
            <span class="item-nilai">{{ number_format($data->total_terlambat) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Kasbon</span>
            <span class="item-nilai">{{ number_format($data->bayar_kasbon) }}</span>
          </div>
          <div class="row-item">
            <span class="item-label">Loss</span>
            <span class="item-nilai">{{ number_format($data->loss) }}</span>
          </div>
        </td>
      </tr>

      {{-- SUBTOTAL ROW --}}
      <tr class="subtotal-row">
        <td></td>
        <td>
          <div class="row-item">
            <span class="item-label"><strong>Total Pendapatan</strong></span>
            <span class="item-nilai"><strong>{{ number_format($data->total_penjumlahan) }}</strong></span>
          </div>
        </td>
        <td>
          <div class="row-item">
            <span class="item-label"><strong>Total Potongan</strong></span>
            <span class="item-nilai"><strong>{{ number_format($data->total_pengurangan) }}</strong></span>
          </div>
        </td>
      </tr>
    </tbody>
  </table>

  {{-- GRAND TOTAL --}}
  <div class="grand-total-wrap">
    <div class="grand-total-label">JUMLAH GAJI DITERIMA</div>
    <div class="grand-total-value">Rp {{ number_format($data->grand_total) }}</div>
  </div>

  {{-- Rekening & Sisa Cuti --}}
  <div class="sisa-cuti">
    <table style="width:100%; margin-top:6px; font-size:10px;">
      <tr>
        <td>Rekening &nbsp;: {{ $user->rekening ?? '-' }} ({{ $user->nama_rekening ?? $user->name }})</td>
        <td style="text-align:right">Sisa Cuti : {{ $user->izin_cuti ?? 0 }} Hari / Tahun</td>
      </tr>
      <tr>
        <td colspan="2" style="color:#888; font-size:9px; padding-top:4px;">
          Slip gaji ini diterbitkan secara otomatis oleh sistem. — {{ $settings->name ?? '' }} | {{ $settings->email ?? '' }}
        </td>
      </tr>
    </table>
  </div>

</div>{{-- end .page --}}

</body>
</html>