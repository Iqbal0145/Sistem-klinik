<?php

namespace App\Models;

use Database\Factories\HealthCheckupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthCheckup extends Model
{
    /** @use HasFactory<HealthCheckupFactory> */
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'checked_on',
        'clinic',
        'diagnosis',
        'treatment',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'checked_on' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
