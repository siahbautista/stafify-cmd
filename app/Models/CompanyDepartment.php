<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDepartment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * We will create this table in the next step.
     *
     * @var string
     */
    protected $table = 'company_departments';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'department_name',
    ];

    /**
     * Get the company that this department belongs to.
     */
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id', 'company_id');
    }
}