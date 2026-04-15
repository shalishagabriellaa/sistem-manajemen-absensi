@extends('templates.dashboard')
@section('isi')
    <div class="row">
        <div class="col-md-12 m project-list">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 p-0 d-flex mt-2">
                        <h4>{{ $title }}</h4>
                    </div>
                    <div class="col-md-6 p-0">
                        <a href="{{ url('/budgeting') }}" class="btn btn-danger btn-sm ms-2">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <form method="post" class="p-4" action="{{ url('/budgeting/store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="project_id">Project</label>
                        <select class="form-control @error('project_id') is-invalid @enderror" id="project_id" name="project_id">
                            <option value="">-- Pilih Project (Opsional) --</option>
                            @foreach ($project as $p)
                                <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>
                                    [{{ $p->no_po }}] {{ $p->nama_po }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal') }}">
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="user_id">Nama</label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                <option value="">-- Pilih --</option>
                                @foreach ($user as $us)
                                    <option value="{{ $us->id }}" {{ old('user_id') == $us->id ? 'selected' : '' }}>{{ $us->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="event">Event</label>
                            <input type="text" class="form-control @error('event') is-invalid @enderror" id="event" name="event" value="{{ old('event') }}">
                            @error('event')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select class="form-control @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id">
                                <option value="">-- Pilih --</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->name }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="text" class="form-control money @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah') }}">
                            @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="qty">Qty</label>
                            <input type="number" class="form-control @error('qty') is-invalid @enderror" id="qty" name="qty" value="{{ old('qty') }}">
                            @error('qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="text" readonly class="form-control money @error('total') is-invalid @enderror" id="total" name="total" value="{{ old('total') }}">
                            @error('total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="table-responsive p-4">
                            <table id="tablemultiple" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Fee</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $old = session()->getOldInput(); @endphp
                                    @if(isset($old['user_id_item']))
                                        @foreach ($old['user_id_item'] as $key => $detailName)
                                            <tr id="multiple{{ $key }}">
                                                <td>
                                                    <select style="width:130px" class="user_id_item form-control" name="user_id_item[]">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach ($user as $us)
                                                            <option value="{{ $us->id }}" {{ old('user_id_item')[$key] == $us->id ? 'selected' : '' }}>{{ $us->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control money fee" name="fee[]" value="{{ old('fee')[$key] }}">
                                                </td>
                                                <td class="text-center">
                                                    <a class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="multiple0">
                                            <td>
                                                <select class="user_id_item form-control" name="user_id_item[]">
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($user as $us)
                                                        <option value="{{ $us->id }}">{{ $us->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control money fee" name="fee[]">
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <a id="add_row" class="btn btn-sm btn-success float-end mt-2">+ Tambah</a>
                        </div>

                        <div class="form-group">
                            <label for="sisa">Sisa</label>
                            <input type="text" readonly class="form-control money @error('sisa') is-invalid @enderror" id="sisa" name="sisa" value="{{ old('sisa') }}">
                            @error('sisa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <input type="text" readonly class="form-control" id="status" name="status" value="{{ old('status', 'Pending') }}">
                        </div>

                        <div class="form-group">
                            <label for="file_path">File</label>
                            <input class="form-control @error('file_path') is-invalid @enderror" type="file" id="file_path" name="file_path">
                            @error('file_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <input type="hidden" name="kategori_name" id="kategori_name" value="{{ old('kategori_name') }}">

                    <button type="submit" class="btn btn-primary float-right mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <script>
            function replaceCurrency(n) {
                return n ? n.replace(/\,/g, '') : '';
            }

            $(document).ready(function(){
                $('.money').mask('000,000,000,000,000', { reverse: true });
                $('#kategori_id').select2();
                $('.user_id_item').select2();

                var row_number = 1;

                $("#add_row").click(function(e) {
                    e.preventDefault();
                    var table = document.getElementById("tablemultiple");
                    var tbodyRowCount = table.tBodies[0].rows.length;
                    $(".user_id_item").select2('destroy');
                    var new_row = $('#tablemultiple tbody tr:last').clone();
                    new_row.find("input").val("").end();
                    new_row.find("select").val("").end();
                    $('#tablemultiple').append(new_row);
                    $('#tablemultiple tbody tr:last').attr('id', 'multiple' + tbodyRowCount);
                    row_number++;
                    $('.user_id_item').select2();
                    $('.money').mask('000,000,000,000,000', { reverse: true });
                });

                $('body').on('click', '.delete', function() {
                    var tbodyRowCount = document.getElementById("tablemultiple").tBodies[0].rows.length;
                    if (tbodyRowCount <= 1) {
                        alert('Cannot delete if only 1 row!');
                    } else if (confirm('Yakin ingin hapus?')) {
                        $(this).closest('tr').remove();
                        recalcSisa();
                    }
                });

                $('body').on('keyup click', '.fee', function() { recalcSisa(); });

                function recalcSisa() {
                    let total = $('#total').val() ? parseFloat(replaceCurrency($('#total').val())) : 0;
                    let sum_fee = 0;
                    $('.fee').each(function() {
                        let v = $(this).val();
                        sum_fee += v ? parseFloat(replaceCurrency(v)) : 0;
                    });
                    $('#sisa').val(accounting.formatMoney(total - sum_fee, '', 0, ",", "."));
                }

                let kategori_name = $('#kategori_name').val();
                if (kategori_name == 'Lain-lain') {
                    $("#jumlah").prop('readonly', false);
                } else {
                    $("#jumlah").prop('readonly', true);
                }

                $('#kategori_id').on('change', function(){
                    let kategori_id = $(this).val();
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/budgeting/getKategori') }}",
                        data: { kategori_id: kategori_id, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function(data) {
                            $('#kategori_name').val(data.name);
                            let qty = $('#qty').val() ? parseFloat($('#qty').val()) : 0;
                            let sum_fee = 0;
                            $('.fee').each(function() {
                                let v = $(this).val();
                                sum_fee += v ? parseFloat(replaceCurrency(v)) : 0;
                            });
                            if (data.name == 'Lain-lain') {
                                $('#jumlah').val(accounting.formatMoney(0, '', 0, ",", "."));
                                $('#total').val(accounting.formatMoney(0, '', 0, ",", "."));
                                $('#sisa').val(accounting.formatMoney(0, '', 0, ",", "."));
                                $("#jumlah").prop('readonly', false);
                            } else {
                                let total = parseFloat(data.jumlah) * qty;
                                $('#jumlah').val(accounting.formatMoney(data.jumlah, '', 0, ",", "."));
                                $('#total').val(accounting.formatMoney(total, '', 0, ",", "."));
                                $('#sisa').val(accounting.formatMoney(total - sum_fee, '', 0, ",", "."));
                                $("#jumlah").prop('readonly', true);
                            }
                        }
                    });
                });

                $('#jumlah').on('keyup', function(){
                    let jumlah = $(this).val() ? parseFloat(replaceCurrency($(this).val())) : 0;
                    let qty = $('#qty').val() ? parseFloat($('#qty').val()) : 0;
                    let total = jumlah * qty;
                    $('#total').val(accounting.formatMoney(total, '', 0, ",", "."));
                    let sum_fee = 0;
                    $('.fee').each(function() {
                        let v = $(this).val();
                        sum_fee += v ? parseFloat(replaceCurrency(v)) : 0;
                    });
                    $('#sisa').val(accounting.formatMoney(total - sum_fee, '', 0, ",", "."));
                });

                $('#qty').on('keyup change', function(){
                    let jumlah = $('#jumlah').val() ? parseFloat(replaceCurrency($('#jumlah').val())) : 0;
                    let qty = $(this).val() ? parseFloat($(this).val()) : 0;
                    let total = jumlah * qty;
                    $('#total').val(accounting.formatMoney(total, '', 0, ",", "."));
                    let sum_fee = 0;
                    $('.fee').each(function() {
                        let v = $(this).val();
                        sum_fee += v ? parseFloat(replaceCurrency(v)) : 0;
                    });
                    $('#sisa').val(accounting.formatMoney(total - sum_fee, '', 0, ",", "."));
                });
            });
        </script>
    @endpush
@endsection