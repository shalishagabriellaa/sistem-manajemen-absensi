<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id', 'jenis_project', 'tanggal_po', 'tanggal_kontrak',
        'no_po', 'nama_po', 'nilai_po', 'no_kontrak', 'nama_kontrak', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reimbursements()
    {
        return $this->hasMany(Reimbursement::class);
    }

    public function budgetings()
    {
        return $this->hasMany(Budgeting::class);
    }
}