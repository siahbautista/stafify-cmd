<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $table = 'company_profiles';

    /**
     * Use 'id' as the primary key if 'company_id' is not the PK.
     * Your branches.php file seems to use both 'company_id' and 'id'.
     * If your primary key is 'company_id', change 'id' to 'company_id' below.
     */
    protected $primaryKey = 'company_id'; // or 'company_id'

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
        // Change 'company_id' if your foreign key is different
        return $this->hasMany(CompanyBranch::class, 'company_id', $this->primaryKey);
    }

    /**
     * Get the headquarters branch for this company.
     */
    public function headquartersBranch()
    {
        // Change 'company_id' if your foreign key is different
        return $this->hasOne(CompanyBranch::class, 'company_id', $this->primaryKey)
                    ->where('is_headquarters', 1);
    }
}