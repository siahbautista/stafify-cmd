<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_name',
        'first_name',
        'middle_name',
        'last_name',
        'full_name',
        'user_email',
        'phone_number',
        'address',
        'country',
        'country_code',
        'user_pin',
        'company',
        'user_dept',
        'user_position',
        'user_password',
        'is_archived',
        'access_level',
        'profile_picture',
        'temp_name',
        'employment_date',
        'branch_location',
        'engagement_status',
        'user_status',
        'user_type',
        'wage_type',
        'sil_status',
        'statutory_benefits',
        'drive_folder_id',
        'drive_folder_link',
        'is_verified',
        'hourly_rate',
        'daily_rate',
        'monthly_rate',
        'resume_file',
        'nbi_file',
        'license_file',
        'health_file',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'user_password' => 'hashed',
            'employment_date' => 'date',
            'is_archived' => 'boolean',
            'sil_status' => 'boolean',
            'statutory_benefits' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->user_password;
    }
}
