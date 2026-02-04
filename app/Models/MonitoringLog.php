<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'website_id',
        'status_code',
        'response_time',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'response_time' => 'float',
        'status_code' => 'integer',
    ];

    /**
     * Get the website associated with this monitoring log entry
     */
    public function monitoredWebsite(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    // Alias method for backward compatibility
    public function website()
    {
        return $this->monitoredWebsite();
    }
}
