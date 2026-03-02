<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';

    protected $fillable = [
        'trigger',
        'recipient',
        'name',
        'subject',
        'body',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Scope: Only enabled templates
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * Find template by trigger & recipient
     */
    public static function findByTrigger(string $trigger, string $recipient)
    {
        return self::where('trigger', $trigger)
            ->where('recipient', $recipient)
            ->where('enabled', true)
            ->first();
    }
}







