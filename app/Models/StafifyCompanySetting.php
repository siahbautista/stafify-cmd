<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StafifyCompanySetting extends Model
{
    use HasFactory;

    protected $table = 'stafify_settings';
    protected $primaryKey = 'setting_id';

    protected $fillable = [
        'company',
        'setting_key',
        'setting_value',
        'updated_by',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(StafifyUser::class, 'updated_by', 'user_id');
    }
}
