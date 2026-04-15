<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budgeting extends Model
{

        protected $fillable = [
    'project_id', 'user_id', 'kategori_id', 'tanggal', 'event',
    'status', 'jumlah', 'qty', 'total', 'jumlah_disetujui',
    'alasan', 'sisa', 'file_path', 'file_name',
];


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
        return $this->hasMany(BudgetingsItem::class);
    }

public function project()
{
    return $this->belongsTo(Project::class);
}
}