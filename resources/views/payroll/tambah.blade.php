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
                    <a href="{{ url('/payroll') }}" class="btn btn-secondary">← Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ url('/payroll/tambah-proses') }}">
                    @csrf

                    {{-- ===== INFORMASI DASAR ===== --}}
                    <h5 class="mt-3 mb-3 text-primary border-bottom pb-2">Informasi Dasar</h5>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Pegawai <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-control selectpicker @error('user_id') is-invalid @enderror" data-live-search="true">
                                <option value="">Pilih Pegawai</option>
                                @foreach ($data_user as $du)
                                    <option value="{{ $du->id }}" {{ old('user_id') == $du->id ? 'selected' : '' }}>{{ $du->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Nomor Gaji <span class="text-danger">*</span></label>
                            <input type="text" name="no_gaji" class="form-control @error('no_gaji') is-invalid @enderror" value="{{ old('no_gaji') }}" placeholder="Contoh: GJ-2025-001">
                            @error('no_gaji')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Persentase Kehadiran (%) <span class="text-danger">*</span></label>
                            <input type="number" name="persentase_kehadiran" class="form-control @error('persentase_kehadiran') is-invalid @enderror" value="{{ old('persentase_kehadiran', 100) }}" min="0" max="100" step="0.01">
                            @error('persentase_kehadiran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-row">
                        @php
                            $bulanArr = [
                                1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                                7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                            ];
                        @endphp
                        <div class="col-md-3 mb-3">
                            <label>Bulan <span class="text-danger">*</span></label>
                            <select name="bulan" class="form-control selectpicker @error('bulan') is-invalid @enderror" data-live-search="true">
                                <option value="">Pilih Bulan</option>
                                @foreach ($bulanArr as $id => $nama)
                                    <option value="{{ $id }}" {{ old('bulan') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                            @error('bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Tahun <span class="text-danger">*</span></label>
                            <select name="tahun" class="form-control selectpicker @error('tahun') is-invalid @enderror">
                                <option value="">Pilih Tahun</option>
                                @for ($i = date('Y'); $i >= date('Y')-10; $i--)
                                    <option value="{{ $i }}" {{ old('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Tanggal Mulai Periode <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}">
                            @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Tanggal Akhir Periode <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_akhir" class="form-control @error('tanggal_akhir') is-invalid @enderror" value="{{ old('tanggal_akhir') }}">
                            @error('tanggal_akhir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ===== PENDAPATAN ===== --}}
                    <h5 class="mt-4 mb-3 text-success border-bottom pb-2">Pendapatan / Penjumlahan</h5>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Gaji Pokok</label>
                            <input type="text" name="gaji_pokok" class="form-control money @error('gaji_pokok') is-invalid @enderror" value="{{ old('gaji_pokok', '0') }}">
                            @error('gaji_pokok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Reimbursement</label>
                            <input type="text" name="total_reimbursement" class="form-control money @error('total_reimbursement') is-invalid @enderror" value="{{ old('total_reimbursement', '0') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Jumlah Hari Tunjangan Transport</label>
                            <input type="number" name="jumlah_tunjangan_transport" class="form-control" value="{{ old('jumlah_tunjangan_transport', 0) }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Uang Tunjangan Transport / Hari</label>
                            <input type="text" name="uang_tunjangan_transport" class="form-control money" value="{{ old('uang_tunjangan_transport', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Tunjangan Transport</label>
                            <input type="text" name="total_tunjangan_transport" id="total_tunjangan_transport" class="form-control money" value="{{ old('total_tunjangan_transport', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Jumlah Hari Tunjangan Makan</label>
                            <input type="number" name="jumlah_tunjangan_makan" class="form-control" value="{{ old('jumlah_tunjangan_makan', 0) }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Uang Tunjangan Makan / Hari</label>
                            <input type="text" name="uang_tunjangan_makan" class="form-control money" value="{{ old('uang_tunjangan_makan', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Tunjangan Makan</label>
                            <input type="text" name="total_tunjangan_makan" id="total_tunjangan_makan" class="form-control money" value="{{ old('total_tunjangan_makan', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Total Tunjangan BPJS Kesehatan</label>
                            <input type="text" name="total_tunjangan_bpjs_kesehatan" class="form-control money" value="{{ old('total_tunjangan_bpjs_kesehatan', '0') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Total Tunjangan BPJS Ketenagakerjaan</label>
                            <input type="text" name="total_tunjangan_bpjs_ketenagakerjaan" class="form-control money" value="{{ old('total_tunjangan_bpjs_ketenagakerjaan', '0') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Jumlah Lembur (Jam)</label>
                            <input type="number" name="jumlah_lembur" class="form-control" value="{{ old('jumlah_lembur', 0) }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Uang Lembur / Jam</label>
                            <input type="text" name="uang_lembur" class="form-control money" value="{{ old('uang_lembur', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Lembur</label>
                            <input type="text" name="total_lembur" id="total_lembur" class="form-control money" value="{{ old('total_lembur', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Jumlah Kehadiran 100%</label>
                            <input type="number" name="jumlah_kehadiran" class="form-control" value="{{ old('jumlah_kehadiran', 0) }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Uang Bonus Kehadiran</label>
                            <input type="text" name="uang_kehadiran" class="form-control money" value="{{ old('uang_kehadiran', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Bonus Kehadiran</label>
                            <input type="text" name="total_kehadiran" id="total_kehadiran" class="form-control money" value="{{ old('total_kehadiran', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Bonus Pribadi</label>
                            <input type="text" name="bonus_pribadi" class="form-control money" value="{{ old('bonus_pribadi', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Bonus Team</label>
                            <input type="text" name="bonus_team" class="form-control money" value="{{ old('bonus_team', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Bonus Jackpot</label>
                            <input type="text" name="bonus_jackpot" class="form-control money" value="{{ old('bonus_jackpot', '0') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Jumlah THR</label>
                            <input type="number" name="jumlah_thr" class="form-control" value="{{ old('jumlah_thr', 0) }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Uang THR</label>
                            <input type="text" name="uang_thr" class="form-control money" value="{{ old('uang_thr', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total THR</label>
                            <input type="text" name="total_thr" id="total_thr" class="form-control money" value="{{ old('total_thr', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-success">Total Penjumlahan (Otomatis)</label>
                            <input type="text" name="total_penjumlahan" id="total_penjumlahan" class="form-control money font-weight-bold" value="{{ old('total_penjumlahan', '0') }}" readonly style="background:#e8f5e9;">
                        </div>
                    </div>

                    {{-- ===== POTONGAN ===== --}}
                    <h5 class="mt-4 mb-3 text-danger border-bottom pb-2">Potongan / Pengurangan</h5>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Total Potongan BPJS Kesehatan</label>
                            <input type="text" name="total_potongan_bpjs_kesehatan" class="form-control money" value="{{ old('total_potongan_bpjs_kesehatan', '0') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Total Potongan BPJS Ketenagakerjaan</label>
                            <input type="text" name="total_potongan_bpjs_ketenagakerjaan" class="form-control money" value="{{ old('total_potongan_bpjs_ketenagakerjaan', '0') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Jumlah Keterlambatan (Kali)</label>
                            <input type="number" name="jumlah_terlambat" class="form-control" value="{{ old('jumlah_terlambat', 0) }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Uang Keterlambatan / Kali</label>
                            <input type="text" name="uang_terlambat" class="form-control money" value="{{ old('uang_terlambat', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Potongan Keterlambatan</label>
                            <input type="text" name="total_terlambat" id="total_terlambat" class="form-control money" value="{{ old('total_terlambat', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Jumlah Mangkir (Hari)</label>
                            <input type="number" name="jumlah_mangkir" class="form-control" value="{{ old('jumlah_mangkir', 0) }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Uang Mangkir / Hari</label>
                            <input type="text" name="uang_mangkir" class="form-control money" value="{{ old('uang_mangkir', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Potongan Mangkir</label>
                            <input type="text" name="total_mangkir" id="total_mangkir" class="form-control money" value="{{ old('total_mangkir', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Jumlah Izin (Hari)</label>
                            <input type="number" name="jumlah_izin" class="form-control" value="{{ old('jumlah_izin', 0) }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Uang Izin / Hari</label>
                            <input type="text" name="uang_izin" class="form-control money" value="{{ old('uang_izin', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Potongan Izin</label>
                            <input type="text" name="total_izin" id="total_izin" class="form-control money" value="{{ old('total_izin', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Saldo Kasbon</label>
                            <input type="text" name="saldo_kasbon" class="form-control money" value="{{ old('saldo_kasbon', '0') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Bayar Kasbon (Potongan Bulan Ini)</label>
                            <input type="text" name="bayar_kasbon" class="form-control money" value="{{ old('bayar_kasbon', '0') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Loss</label>
                            <input type="text" name="loss" class="form-control money" value="{{ old('loss', '0') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-danger">Total Pengurangan (Otomatis)</label>
                            <input type="text" name="total_pengurangan" id="total_pengurangan" class="form-control money font-weight-bold" value="{{ old('total_pengurangan', '0') }}" readonly style="background:#ffebee;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold" style="font-size:1.1rem;">GRAND TOTAL GAJI DITERIMA</label>
                            <input type="text" name="grand_total" id="grand_total" class="form-control money font-weight-bold" value="{{ old('grand_total', '0') }}" readonly style="background:#fff3e0; font-size:1.1rem; border:2px solid #ff9800;">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fa fa-save"></i> Simpan Data Payroll
                        </button>
                        <a href="{{ url('/payroll') }}" class="btn btn-secondary ml-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script>
$(document).ready(function(){
    // Terapkan mask ke semua field money
    $('.money').mask('000,000,000,000,000', { reverse: true });

    // Fungsi ambil nilai numeric dari field money
    function getVal(id) {
        var v = $(id).val().replace(/,/g, '');
        return parseInt(v) || 0;
    }

    // Hitung total = jumlah * uang
    function hitungTotal(jumlahId, uangId, totalId) {
        var jml = parseInt($(jumlahId).val()) || 0;
        var uang = getVal(uangId);
        var total = jml * uang;
        // Set nilai ke field total (tanpa mask dulu, lalu trigger mask)
        $(totalId).val(total).trigger('input');
        hitung();
    }

    // Binding event jumlah * uang
    function bindHitung(jumlahSel, uangSel, totalSel) {
        $(jumlahSel + ', ' + uangSel).on('input keyup', function(){
            hitungTotal(jumlahSel, uangSel, totalSel);
        });
    }

    bindHitung('[name=jumlah_tunjangan_transport]', '[name=uang_tunjangan_transport]', '[name=total_tunjangan_transport]');
    bindHitung('[name=jumlah_tunjangan_makan]',     '[name=uang_tunjangan_makan]',     '[name=total_tunjangan_makan]');
    bindHitung('[name=jumlah_lembur]',              '[name=uang_lembur]',              '[name=total_lembur]');
    bindHitung('[name=jumlah_kehadiran]',           '[name=uang_kehadiran]',           '[name=total_kehadiran]');
    bindHitung('[name=jumlah_terlambat]',           '[name=uang_terlambat]',           '[name=total_terlambat]');
    bindHitung('[name=jumlah_mangkir]',             '[name=uang_mangkir]',             '[name=total_mangkir]');
    bindHitung('[name=jumlah_izin]',                '[name=uang_izin]',                '[name=total_izin]');
    bindHitung('[name=jumlah_thr]',                 '[name=uang_thr]',                 '[name=total_thr]');

    // Hitung grand total
    function hitung() {
        var penjumlahan =
            getVal('[name=gaji_pokok]') +
            getVal('[name=total_reimbursement]') +
            getVal('[name=total_tunjangan_transport]') +
            getVal('[name=total_tunjangan_makan]') +
            getVal('[name=total_tunjangan_bpjs_kesehatan]') +
            getVal('[name=total_tunjangan_bpjs_ketenagakerjaan]') +
            getVal('[name=total_lembur]') +
            getVal('[name=total_kehadiran]') +
            getVal('[name=bonus_pribadi]') +
            getVal('[name=bonus_team]') +
            getVal('[name=bonus_jackpot]') +
            getVal('[name=total_thr]');

        var pengurangan =
            getVal('[name=total_potongan_bpjs_kesehatan]') +
            getVal('[name=total_potongan_bpjs_ketenagakerjaan]') +
            getVal('[name=total_terlambat]') +
            getVal('[name=total_mangkir]') +
            getVal('[name=total_izin]') +
            getVal('[name=bayar_kasbon]') +
            getVal('[name=loss]');

        $('#total_penjumlahan').val(penjumlahan).trigger('input');
        $('#total_pengurangan').val(pengurangan).trigger('input');
        $('#grand_total').val(Math.max(0, penjumlahan - pengurangan)).trigger('input');
    }

    // Trigger hitung saat field money berubah
    $(document).on('input keyup', '.money', hitung);

    // Inisialisasi awal
    hitung();
});
</script>
@endpush
@endsection
