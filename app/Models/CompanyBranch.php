<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBranch extends Model
{
    use HasFactory;

    protected $table = 'company_branches';
    protected $primaryKey = 'branch_id';

    protected $fillable = [
        'company_id',
        'branch_location',
        'branch_address',
        'branch_phone',
        'is_headquarters',
    ];

    /**
     * Get the company that this branch belongs to.
     * FIX: Updated the owner key to 'id' to match CompanyProfile's primary key.
     */
    public function company()
    {
        // belongsTo(RelatedModel, foreign_key_on_this_model, owner_key_on_related_model)
        // This now correctly links this model's 'company_id' to the 'id' column on CompanyProfile
        return $this->belongsTo(CompanyProfile::class, 'company_id', 'id');
    }
}