<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockLedger extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'stock_ledger';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'date',
        'project_id',
        'product_id',
        'invoice_id',
        'party_title_en',
        'party_title_ur',
        'description_en',
        'description_ur',
        'document_number',
        'stock_in_quantity',
        'stock_out_quantity',
    ];

    /**
     * Relationship: each stock ledger entry belongs to a project.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
