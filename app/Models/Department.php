<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $primaryKey = 'dept_id';
    protected $fillable = ['dept_name', 'building_id', 'floor_id', 'hod_id'];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    public function officers(): HasMany
    {
        return $this->hasMany(Officer::class, 'dept_id', 'dept_id');
    }

    public function hod(): BelongsTo
    {
        return $this->belongsTo(Officer::class, 'hod_id', 'officer_id');
    }
}
