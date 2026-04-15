<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Kontrak extends Model
{
    protected $table = 'kontraks';
 
    protected $fillable = [
        'user_id',
        'tanggal',
        'jenis_kontrak',
        'tanggal_mulai',
        'tanggal_selesai',
        'masa_berlaku_sebelumnya',
        'keterangan',
        'no_surat',           // ← tambah ini
        'kontrak_file_path',
        'kontrak_file_name',
    ];
 
    protected $casts = [
        'tanggal'         => 'date',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];
 
    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}