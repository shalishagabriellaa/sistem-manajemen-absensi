<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetingsItem extends Model
{
    protected $fillable = [
        'budgeting_id', 'user_id', 'fee',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}