<?php
// app/Models/LandRegistration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandRegistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'party_account_id',
        'khawat_number',
        'kanal',
        'merla',
        'square_feet',
        'total_merla',
        'remarks',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'kanal' => 'decimal:2',
        'merla' => 'decimal:2',
        'square_feet' => 'decimal:2',
        'total_merla' => 'decimal:4',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function partyAccount()
    {
        return $this->belongsTo(SubSubSubHead::class, 'party_account_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Calculate total merla based on the formulas
    public function calculateTotalMerla()
    {
        $project = $this->project;
        $projectSquareFeetPerMerla = $project->square_feet_per_merla ?? 272.25; // Default 1 marla = 272.25 sq ft

        $kanalToMerla = $this->kanal * 20; // 1 kanal = 20 marlas
        $squareFeetToMerla = $this->square_feet / $projectSquareFeetPerMerla;

        return $kanalToMerla + $this->merla + $squareFeetToMerla;
    }

    // Automatically calculate total merla before saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->total_merla = $model->calculateTotalMerla();
        });
    }
}
