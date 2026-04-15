@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root{
    --dash-blue:#3b4cca;
    --dash-blue-dk:#2d3db4;
    --dash-blue-lt:#5c6ed4;

    --slate-50:#f8fafc;
    --slate-100:#f1f5f9;
    --slate-200:#e2e8f0;
    --slate-300:#cbd5e1;
    --slate-500:#64748b;
    --slate-700:#334155;
}

.notification-card{
    border-radius:16px;
    border:1px solid var(--slate-200);
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
}

.notification-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px;
    border-bottom:1px solid var(--slate-200);
}

.notification-title{
    font-size:20px;
    font-weight:700;
    color:var(--slate-700);
}

.action-group{
    display:flex;
    gap:8px;
}

.action-btn{
    padding:7px 14px;
    font-size:13px;
    font-weight:600;
    border-radius:8px;
    border:1px solid var(--slate-200);
    background:white;
    color:var(--slate-700);
    transition:.2s;
}

.action-btn:hover{
    background:var(--dash-blue);
    color:white;
    border-color:var(--dash-blue);
}

.notification-list{
    padding:10px 0;
}

.notification-item{
    display:flex;
    gap:16px;
    padding:16px 20px;
    border-bottom:1px solid var(--slate-100);
    transition:.25s;
}

.notification-item:hover{
    background:#f8f9ff;
}

.notification-unread{
    background:#f2f4ff;
}

.notification-avatar img{
    width:46px;
    height:46px;
    border-radius:50%;
    object-fit:cover;
}

.notification-content{
    flex:1;
}

.notification-date{
    font-size:12px;
    color:var(--slate-500);
    margin-bottom:4px;
}

.notification-user{
    font-weight:700;
    color:var(--slate-700);
    font-size:14px;
}

.notification-message{
    font-size:14px;
    color:var(--slate-500);
    margin-top:2px;
}

.notification-footer{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:6px;
}

.detail-btn{
    font-size:12px;
    font-weight:600;
    padding:6px 12px;
    border-radius:6px;
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:white;
    text-decoration:none;
}

.detail-btn:hover{
    opacity:.9;
    color:white;
}

.action-btn.active{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:white;
    border-color:var(--dash-blue);
}
</style>


<div class="container-fluid">

<div class="card notification-card">

<div class="notification-header">

<div class="notification-title">
<i class="fas fa-bell" style="color:#3b4cca;margin-right:6px;"></i>
{{ $title }}
</div>

<div class="action-group">

<a href="{{ url('/notifications/read') }}"
class="action-btn {{ request()->is('notifications/read*') ? 'active' : '' }}">
<i class="fas fa-check-circle"></i> Read
</a>

<a href="{{ url('/notifications/unread') }}"
class="action-btn {{ request()->is('notifications/unread*') ? 'active' : '' }}">
<i class="fas fa-envelope"></i> Unread
</a>

</div>
</div>


<div class="notification-list">

@foreach ($inboxs as $inbox)

@php
$user = App\Models\User::find($inbox->data['user_id']);
@endphp

<div class="notification-item {{ !$inbox->read_at ? 'notification-unread' : '' }}">

<div class="notification-avatar">

@if ($user->foto_karyawan == null)
<img src="{{ url('assets/img/foto_default.jpg') }}">
@else
<img src="{{ url('/storage/'.$user->foto_karyawan) }}">
@endif

</div>


<div class="notification-content">

<div class="notification-date">

{{ date('d M Y',strtotime($inbox->created_at)) }}
• 
{{ date('H:i',strtotime($inbox->created_at)) }}

</div>

<div class="notification-user">

{{ $user->name }}

</div>

<div class="notification-message">

{{ $inbox->data['message'] }}

</div>


<div class="notification-footer">

<span></span>

<a href="{!! !$inbox->read_at ? url('/notifications/read-message/'.$inbox->id) : url($inbox->data['action']) !!}" 
class="detail-btn">

Detail

</a>

</div>

</div>

</div>

@endforeach


<div class="d-flex justify-content-end p-3">
{{ $inboxs->links() }}
</div>


</div>

</div>

</div>

@endsection