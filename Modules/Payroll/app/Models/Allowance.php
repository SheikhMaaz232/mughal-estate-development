<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Allowance extends Model implements Auditable
{
    use softDeletes, \OwenIt\Auditing\Auditable;

     /**
     * The table associated with the model.
     *
     * @var string
     */

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['title_en', 'title_ur'];

     /**
     * The attributes that aren't mass assignable.
     */

    protected $guarded = ['id'];

}
