<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralJournal extends Model
{
    use SoftDeletes;
    protected $table = 'general_journals';

    protected $fillable = [
        'date',
        'project_id',
        'invoice_id',
        'party_id',
        'detail_account_id',
        'description_en',
        'description_ur',
        'document_number',
        'debit',
        'credit',
        'is_fee_entry',
        'transaction_type',
    ];

    // Relationships

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function detailAccount()
    {
        return $this->belongsTo(DetailAccount::class);
    }
}
