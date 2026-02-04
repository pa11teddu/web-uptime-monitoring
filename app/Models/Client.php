<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'email'
    ];

    /**
     * Get all websites monitored for this client
     */
    public function monitoredSites(): HasMany
    {
        return $this->hasMany(Website::class, 'client_id');
    }

    // Alias method for backward compatibility
    public function websites()
    {
        return $this->monitoredSites();
    }
}
