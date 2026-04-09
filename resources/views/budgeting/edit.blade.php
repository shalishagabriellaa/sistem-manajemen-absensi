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
<a href="{{ url('/budgeting') }}" class="btn btn-danger btn-sm ms-2">Back</a>
</div>

</div>
</div>
</div>


<div class="col-md-12">
<div class="card">

<form method="post" class="p-4" action="{{ url('/budgeting/update/'.$budgeting->id) }}" enctype="multipart/form-data">
@csrf

<div class="form-group">
<label>Tanggal</label>
<input type="date"
class="form-control"
name="tanggal"
value="{{ old('tanggal',$budgeting->tanggal) }}">
</div>


<div class="form-group">
<label>Nama</label>

<select class="form-control" name="user_id">

<option value="">-- Pilih --</option>

@foreach ($user as $us)

<option value="{{ $us->id }}"
{{ old('user_id',$budgeting->user_id)==$us->id ? 'selected':'' }}>

{{ $us->name }}

</option>

@endforeach

</select>

</div>


<div class="form-group">
<label>Event</label>

<input type="text"
class="form-control"
name="event"
value="{{ old('event',$budgeting->event) }}">
</div>



<div class="form-group">

<label>Kategori</label>

<select class="form-control" id="kategori_id" name="kategori_id">

<option value="">-- Pilih --</option>

@foreach ($kategori as $kat)

<option value="{{ $kat->id }}"
{{ old('kategori_id',$budgeting->kategori_id)==$kat->id ? 'selected':'' }}>

{{ $kat->name }}

</option>

@endforeach

</select>

</div>



<div class="form-group">
<label>Jumlah</label>

<input type="text"
class="form-control money"
id="jumlah"
name="jumlah"
value="{{ old('jumlah',number_format($budgeting->jumlah,0,',','.' )) }}">
</div>



<div class="form-group">
<label>Qty</label>

<input type="number"
class="form-control"
id="qty"
name="qty"
value="{{ old('qty',$budgeting->qty) }}">
</div>



<div class="form-group">

<label>Total</label>

<input type="text"
readonly
class="form-control money"
id="total"
name="total"
value="{{ old('total',number_format($budgeting->total,0,',','.' )) }}">

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

@foreach ($budgeting->items as $key => $item)

<tr id="multiple{{ $key }}">

<td>

<select class="user_id_item form-control" name="user_id_item[]">

<option value="">-- Pilih --</option>

@foreach ($user as $us)

<option value="{{ $us->id }}"
{{ $item->user_id==$us->id ? 'selected':'' }}>

{{ $us->name }}

</option>

@endforeach

</select>

</td>


<td>

<input type="text"
class="form-control money fee"
name="fee[]"
value="{{ number_format($item->fee,0,',','.') }}">

</td>


<td class="text-center">

<a class="btn btn-sm btn-danger delete">

<i class="fa fa-trash"></i>

</a>

</td>

</tr>

@endforeach

</tbody>

</table>

<a id="add_row" class="btn btn-sm btn-success float-end mt-2">+ Tambah</a>

</div>



<div class="form-group">
<label>Sisa</label>

<input type="text"
readonly
class="form-control money"
id="sisa"
name="sisa"
value="{{ old('sisa',number_format($budgeting->sisa,0,',','.' )) }}">
</div>



<div class="form-group">

<label>Status</label>

<input type="text"
readonly
class="form-control"
name="status"
value="{{ old('status',$budgeting->status) }}">

</div>



<div class="form-group">

<label>File</label>

<input type="file" class="form-control" name="file_path">

@if($budgeting->file_name)

<small class="text-muted">
File sekarang : {{ $budgeting->file_name }}
</small>

@endif

</div>



<button type="submit" class="btn btn-primary float-right mt-3">
Update
</button>

</form>

</div>
</div>
</div>


@push('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

<script>

function replaceCurrency(n){
return n ? n.replace(/\,/g,''):'';
}

$(document).ready(function(){

$('.money').mask('000,000,000,000,000',{reverse:true});

var row_number = 1;


$("#add_row").click(function(e){

e.preventDefault();

var table = document.getElementById("tablemultiple");

var tbodyRowCount = table.tBodies[0].rows.length;

var new_row = $('#tablemultiple tbody tr:last').clone();

new_row.find("input").val("");

new_row.find("select").val("");

$('#tablemultiple').append(new_row);

$('#tablemultiple tbody tr:last').attr('id','multiple'+tbodyRowCount);

row_number++;

$('.money').mask('000,000,000,000,000',{reverse:true});

});


$('body').on('click','.delete',function(){

var tbodyRowCount = document.getElementById("tablemultiple").tBodies[0].rows.length;

if(tbodyRowCount<=1){

alert('Cannot delete if only 1 row!');

}else{

$(this).closest('tr').remove();

recalcSisa();

}

});


$('body').on('keyup click','.fee',function(){

recalcSisa();

});


function recalcSisa(){

let total = $('#total').val() ? parseFloat(replaceCurrency($('#total').val())) : 0;

let sum_fee = 0;

$('.fee').each(function(){

let v = $(this).val();

sum_fee += v ? parseFloat(replaceCurrency(v)) : 0;

});

$('#sisa').val(total - sum_fee);

}


$('#jumlah').on('keyup',function(){

let jumlah = $(this).val() ? parseFloat(replaceCurrency($(this).val())) : 0;

let qty = $('#qty').val() ? parseFloat($('#qty').val()) : 0;

let total = jumlah * qty;

$('#total').val(total);

});


$('#qty').on('keyup change',function(){

let jumlah = $('#jumlah').val() ? parseFloat(replaceCurrency($('#jumlah').val())) : 0;

let qty = $(this).val() ? parseFloat($(this).val()) : 0;

let total = jumlah * qty;

$('#total').val(total);

});

});

</script>

@endpush

@endsection