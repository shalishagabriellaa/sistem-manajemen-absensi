<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function items()
    {
        return $this->hasMany(ReimbursementsItem::class);
    }

    // tambah 'project_id' ke $fillable
protected $fillable = [
    'project_id', 'user_id', 'kategori_id', 'tanggal', 'event',
    'status', 'jumlah', 'qty', 'total', 'sisa', 'file_path', 'file_name',
];

// tambah relasi
public function project()
{
    return $this->belongsTo(Project::class);
}
}
