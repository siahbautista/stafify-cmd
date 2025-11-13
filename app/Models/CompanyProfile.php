<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $table = 'company_profiles';
    protected $primaryKey = 'company_id';

    protected $fillable = [
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'company_logo',
        'timezone',
        'week_start',
        'year_type',
        'fiscal_start_month',
        'fiscal_start_day',
        'fiscal_end_month',
        'fiscal_end_day',
    ];

    /**
     * Get all branches for this company.
     */
    public function branches()
    {
        return $this->hasMany(CompanyBranch::class, 'company_id', 'company_id');
    }

    /**
     * Get the headquarters branch for this company.
     */
    public function headquartersBranch()
    {
        return $this->hasOne(CompanyBranch::class, 'company_id', 'company_id')
                    ->where('is_headquarters', 1);
    }

    /**
     * --- NEW RELATIONSHIP ---
     * Get all departments for this company.
     */
    public function departments()
    {
        return $this->hasMany(CompanyDepartment::class, 'company_id', 'company_id');
    }

    /**
     * --- NEW RELATIONSHIP ---
     * Get all positions for this company.
     */
    public function positions()
    {
        return $this->hasMany(CompanyPosition::class, 'company_id', 'company_id');
    }
}