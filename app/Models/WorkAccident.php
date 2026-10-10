<?php

namespace App\Models;

use Database\Factories\WorkAccidentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkAccident extends Model
{
    /** @use HasFactory<WorkAccidentFactory> */
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'company_id',
        'name',
        'age',
        'department',
        'occurred_on',
        'chronology',
        'injured_body_part',
        'action_taken',
        'therapy',
    ];

    protected function casts(): array
    {
        return [
            'occurred_on' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
