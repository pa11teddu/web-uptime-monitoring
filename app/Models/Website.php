<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'url',
        'is_active'
    ];

    /**
     * Get the client that owns this website
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get all monitoring records for this website
     */
    public function monitoringRecords(): HasMany
    {
        return $this->hasMany(MonitoringLog::class, 'website_id');
    }

    /**
     * Get the most recent monitoring log entry
     */
    public function mostRecentLog(): HasOne
    {
        return $this->hasOne(MonitoringLog::class, 'website_id')->latestOfMany();
    }

    // Alias methods for backward compatibility
    public function client()
    {
        return $this->owner();
    }

    public function logs()
    {
        return $this->monitoringRecords();
    }

    public function latestLog()
    {
        return $this->mostRecentLog();
    }
}
