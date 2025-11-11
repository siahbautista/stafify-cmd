<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentManagementResource extends Model
{
    use HasFactory;

    protected $table = 'talent_management_resources';

    protected $fillable = [
        'resource_key',
        'title',
        'type',
        'url',
        'form_url',
        'icon_path',
        'icon_lordicon',
        'display_order',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Scope to get only active resources
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}