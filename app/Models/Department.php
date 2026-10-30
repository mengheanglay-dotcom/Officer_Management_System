<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $primaryKey = 'dept_id';
    protected $fillable = ['dept_name', 'location', 'manager_id'];

    public function officers(): HasMany
    {
        return $this->hasMany(Officer::class, 'dept_id', 'dept_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Officer::class, 'manager_id', 'officer_id');
    }
}
