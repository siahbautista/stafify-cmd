<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollSettings extends Model
{
    use HasFactory;

    protected $table = 'payroll_settings';

    protected $fillable = [
        'id',
        'frequency',
        'disbursement',
        'deduction_schedule',
        'benefits_url',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;
}
