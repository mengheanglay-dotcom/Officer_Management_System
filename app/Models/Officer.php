<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Officer extends Model
{
    protected $primaryKey = 'officer_id';
    protected $fillable = [
        'officer_name', 'email', 'gender', 'dob', 'phone',
        'position', 'hire_date', 'description', 'dept_id',
    ];
    protected $casts = ['dob' => 'date', 'hire_date' => 'date'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }

    /** Years of service, e.g. "3.9 yrs" */
    public function getServiceYearsAttribute(): string
    {
        return $this->hire_date
            ? number_format($this->hire_date->floatDiffInYears(now()), 1) . ' yrs'
            : 'Not assigned';
    }
}
