<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentAcquisitionToolkit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'talent_acquisition_toolkit'; // <-- This is the new table name

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id'; // Assuming it's 'id'

    protected $fillable = [
        'user_id',
        'sales_title',
        'form_url',
        'response_url',
        'icon',
        'type',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Get the user that owns the toolkit.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Scope to get approved toolkits or user's own toolkits
     */
    public function scopeAccessible($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('is_approved', true);
        });
    }

    /**
     * Scope to get toolkits by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get pending toolkits
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Scope to get approved toolkits
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}