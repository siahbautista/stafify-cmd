<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StafifyPayroll extends Model
{
    use HasFactory;

    protected $table = 'stafify_payrolls';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'template_id'
    ];

    public $timestamps = false;
}