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
                            <select name="user_id" id="user_id" class="form-control selectpicker @error('user_id') is-invalid @enderror" data-live-search="true">
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

                    {{-- Info pegawai otomatis (readonly, untuk referensi) --}}
                    <div class="form-row" id="info-pegawai-wrap" style="display:none!important">
                        <div class="col-md-12">
                            <div class="alert alert-info py-2 px-3 mb-3" id="info-pegawai-box" style="font-size:13px;"></div>
                        </div>
                    </div>

                    {{-- ===== PENDAPATAN ===== --}}
                    <h5 class="mt-4 mb-3 text-success border-bottom pb-2">Pendapatan / Penjumlahan</h5>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Gaji Pokok</label>
                            <input type="text" name="gaji_pokok" id="gaji_pokok" class="form-control money @error('gaji_pokok') is-invalid @enderror" value="{{ old('gaji_pokok', '0') }}">
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
                            <input type="text" name="uang_tunjangan_transport" id="uang_tunjangan_transport" class="form-control money" value="{{ old('uang_tunjangan_transport', '0') }}">
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
                            <input type="text" name="uang_tunjangan_makan" id="uang_tunjangan_makan" class="form-control money" value="{{ old('uang_tunjangan_makan', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Tunjangan Makan</label>
                            <input type="text" name="total_tunjangan_makan" id="total_tunjangan_makan" class="form-control money" value="{{ old('total_tunjangan_makan', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Total Tunjangan BPJS Kesehatan</label>
                            <input type="text" name="total_tunjangan_bpjs_kesehatan" id="total_tunjangan_bpjs_kesehatan" class="form-control money" value="{{ old('total_tunjangan_bpjs_kesehatan', '0') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Total Tunjangan BPJS Ketenagakerjaan</label>
                            <input type="text" name="total_tunjangan_bpjs_ketenagakerjaan" id="total_tunjangan_bpjs_ketenagakerjaan" class="form-control money" value="{{ old('total_tunjangan_bpjs_ketenagakerjaan', '0') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Jumlah Lembur (Jam)</label>
                            <input type="number" name="jumlah_lembur" class="form-control" value="{{ old('jumlah_lembur', 0) }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Uang Lembur / Jam</label>
                            <input type="text" name="uang_lembur" id="uang_lembur" class="form-control money" value="{{ old('uang_lembur', '0') }}">
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
                            <input type="text" name="uang_kehadiran" id="uang_kehadiran" class="form-control money" value="{{ old('uang_kehadiran', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Bonus Kehadiran</label>
                            <input type="text" name="total_kehadiran" id="total_kehadiran" class="form-control money" value="{{ old('total_kehadiran', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Bonus Pribadi (KPI)</label>
                            <input type="text" name="bonus_pribadi" id="bonus_pribadi" class="form-control money" value="{{ old('bonus_pribadi', '0') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Bonus Team (KPI)</label>
                            <input type="text" name="bonus_team" id="bonus_team" class="form-control money" value="{{ old('bonus_team', '0') }}">
                        </div>
                    </div>
                    {{-- bonus_jackpot hidden (tetap dikirim sebagai 0 agar kompatibel dengan DB) --}}
                    <input type="hidden" name="bonus_jackpot" value="0">

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Jumlah THR</label>
                            <input type="number" name="jumlah_thr" class="form-control" value="{{ old('jumlah_thr', 0) }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Uang THR</label>
                            <input type="text" name="uang_thr" id="uang_thr" class="form-control money" value="{{ old('uang_thr', '0') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total THR</label>
                            <input type="text" name="total_thr" id="total_thr" class="form-control money" value="{{ old('total_thr', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-success">Total Penjumlahan (Otomatis)</label>
                            <input type="text" id="total_penjumlahan" class="form-control money font-weight-bold" value="{{ old('total_penjumlahan', '0') }}" readonly style="background:#e8f5e9;">
                        </div>
                    </div>

                    {{-- ===== POTONGAN ===== --}}
                    <h5 class="mt-4 mb-3 text-danger border-bottom pb-2">Potongan / Pengurangan</h5>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Total Potongan BPJS Kesehatan</label>
                            <input type="text" name="total_potongan_bpjs_kesehatan" id="total_potongan_bpjs_kesehatan" class="form-control money" value="{{ old('total_potongan_bpjs_kesehatan', '0') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Total Potongan BPJS Ketenagakerjaan</label>
                            <input type="text" name="total_potongan_bpjs_ketenagakerjaan" id="total_potongan_bpjs_ketenagakerjaan" class="form-control money" value="{{ old('total_potongan_bpjs_ketenagakerjaan', '0') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Jumlah Keterlambatan (Kali)</label>
                            <input type="number" name="jumlah_terlambat" class="form-control" value="{{ old('jumlah_terlambat', 0) }}" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Uang Potongan Keterlambatan / Kali</label>
                            <input type="text" name="uang_terlambat" id="uang_terlambat" class="form-control money" value="{{ old('uang_terlambat', '0') }}">
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
                            <label>Uang Potongan Mangkir / Hari</label>
                            <input type="text" name="uang_mangkir" id="uang_mangkir" class="form-control money" value="{{ old('uang_mangkir', '0') }}">
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
                            <label>Uang Potongan Izin / Hari</label>
                            <input type="text" name="uang_izin" id="uang_izin" class="form-control money" value="{{ old('uang_izin', '0') }}">
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
                            <input type="text" name="bayar_kasbon" id="bayar_kasbon" class="form-control money" value="{{ old('bayar_kasbon', '0') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Loss</label>
                            <input type="text" name="loss" id="loss" class="form-control money" value="{{ old('loss', '0') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-danger">Total Pengurangan (Otomatis)</label>
                            <input type="text" id="total_pengurangan" class="form-control money font-weight-bold" value="{{ old('total_pengurangan', '0') }}" readonly style="background:#ffebee;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold" style="font-size:1.1rem;">GRAND TOTAL GAJI DITERIMA</label>
                            <input type="text" id="grand_total" class="form-control money font-weight-bold" value="{{ old('grand_total', '0') }}" readonly style="background:#fff3e0; font-size:1.1rem; border:2px solid #ff9800;">
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

    // Flag agar hitung() tidak memanggil dirinya sendiri saat set nilai readonly
    var isCalculating = false;

    // Ambil nilai numerik dari field (hapus koma mask)
    function getVal(sel) {
        return parseInt($(sel).val().replace(/,/g, '')) || 0;
    }

    // Set nilai ke field readonly (tanpa trigger agar tidak loop)
    function setReadonly(id, val) {
        // Gunakan jquery.mask setVal jika tersedia, fallback ke val()
        var $el = $(id);
        $el.val(val);
        // Paksa mask memformat ulang nilai
        if (typeof $el.data('mask') !== 'undefined') {
            $el.trigger('blur');
        }
    }

    // Hitung semua total — dipanggil setiap kali ada perubahan input
    function hitung() {
        if (isCalculating) return;
        isCalculating = true;

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
            getVal('[name=total_thr]');

        var pengurangan =
            getVal('[name=total_potongan_bpjs_kesehatan]') +
            getVal('[name=total_potongan_bpjs_ketenagakerjaan]') +
            getVal('[name=total_terlambat]') +
            getVal('[name=total_mangkir]') +
            getVal('[name=total_izin]') +
            getVal('[name=bayar_kasbon]') +
            getVal('[name=loss]');

        var grandTotal = Math.max(0, penjumlahan - pengurangan);

        // Set langsung ke field readonly — TANPA .trigger('input')
        $('#total_penjumlahan').val(penjumlahan.toLocaleString('id-ID'));
        $('#total_pengurangan').val(pengurangan.toLocaleString('id-ID'));
        $('#grand_total').val(grandTotal.toLocaleString('id-ID'));

        isCalculating = false;
    }

    // Hitung jumlah * uang lalu update field total-nya, kemudian hitung grand
    function hitungSubtotal(jumlahSel, uangSel, totalSel) {
        var jml  = parseInt($(jumlahSel).val()) || 0;
        var uang = getVal(uangSel);
        var total = jml * uang;
        // Set ke field readonly subtotal tanpa trigger
        $(totalSel).val(total.toLocaleString('id-ID'));
        hitung();
    }

    function bindSubtotal(jumlahSel, uangSel, totalSel) {
        $(jumlahSel + ', ' + uangSel).on('input keyup', function(){
            hitungSubtotal(jumlahSel, uangSel, totalSel);
        });
    }

    bindSubtotal('[name=jumlah_tunjangan_transport]', '[name=uang_tunjangan_transport]', '[name=total_tunjangan_transport]');
    bindSubtotal('[name=jumlah_tunjangan_makan]',     '[name=uang_tunjangan_makan]',     '[name=total_tunjangan_makan]');
    bindSubtotal('[name=jumlah_lembur]',              '[name=uang_lembur]',              '[name=total_lembur]');
    bindSubtotal('[name=jumlah_kehadiran]',           '[name=uang_kehadiran]',           '[name=total_kehadiran]');
    bindSubtotal('[name=jumlah_terlambat]',           '[name=uang_terlambat]',           '[name=total_terlambat]');
    bindSubtotal('[name=jumlah_mangkir]',             '[name=uang_mangkir]',             '[name=total_mangkir]');
    bindSubtotal('[name=jumlah_izin]',                '[name=uang_izin]',                '[name=total_izin]');
    bindSubtotal('[name=jumlah_thr]',                 '[name=uang_thr]',                 '[name=total_thr]');

    // Trigger hitung saat field money yang BUKAN readonly berubah
    // (exclude field readonly agar tidak loop)
    $(document).on('input keyup', '.money:not([readonly])', function(){
        hitung();
    });

    // ── AJAX: isi data pegawai otomatis saat pilih pegawai ──────────────────
    $('#user_id').on('change', function(){
        var userId = $(this).val();
        if (!userId) return;

        $.get('/payroll/get-user-data/' + userId, function(res){
            if (!res) return;

            // Set field langsung dengan nilai integer (mask akan format saat blur)
            var fields = {
                '[name=gaji_pokok]':                           res.gaji_pokok || 0,
                '[name=uang_tunjangan_transport]':             res.tunjangan_transport || 0,
                '[name=uang_tunjangan_makan]':                 res.tunjangan_makan || 0,
                '[name=total_tunjangan_bpjs_kesehatan]':       res.tunjangan_bpjs_kesehatan || 0,
                '[name=total_tunjangan_bpjs_ketenagakerjaan]': res.tunjangan_bpjs_ketenagakerjaan || 0,
                '[name=uang_lembur]':                          res.lembur || 0,
                '[name=uang_kehadiran]':                       res.kehadiran || 0,
                '[name=bonus_pribadi]':                        res.bonus_pribadi || 0,
                '[name=bonus_team]':                           res.bonus_team || 0,
                '[name=uang_thr]':                             res.thr || 0,
                '[name=total_potongan_bpjs_kesehatan]':        res.potongan_bpjs_kesehatan || 0,
                '[name=total_potongan_bpjs_ketenagakerjaan]':  res.potongan_bpjs_ketenagakerjaan || 0,
                '[name=uang_terlambat]':                       res.terlambat || 0,
                '[name=uang_mangkir]':                         res.mangkir || 0,
                '[name=uang_izin]':                            res.izin || 0,
                '[name=saldo_kasbon]':                         res.saldo_kasbon || 0,
            };

            $.each(fields, function(sel, val){
                $(sel).val(val).trigger('blur'); // blur agar mask format ulang
            });

            hitung();
        });
    });

    // Inisialisasi awal
    hitung();
});
</script>
@endpush
@endsection