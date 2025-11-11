<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StafifyUser extends Model
{
    use HasFactory;

    protected $table = 'stafify_users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_name',
        'full_name',
        'user_email',
        'phone_number',
        'user_pin',
        'company',
        'user_dept',
        'user_position',
        'access_level',
        'is_admin',
        'address',
        'country',
        'country_code',
        'employment_date',
        'branch_location',
        'engagement_status',
        'user_status',
        'user_type',
        'wage_type',
        'sil_status',
        'statutory_benefits',
    ];

    protected $casts = [
        'employment_date' => 'date',
        'sil_status' => 'boolean',
        'statutory_benefits' => 'boolean',
    ];

    public function timeTracking()
    {
        return $this->hasMany(StafifyTimeTracking::class, 'user_id', 'user_id');
    }

    public function overtimeRequests()
    {
        return $this->hasMany(StafifyOvertime::class, 'user_id', 'user_id');
    }
}
