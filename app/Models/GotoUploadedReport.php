<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GotoUploadedReport extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'merchant_id',
        'event_type',
        'event_created_at',
        'source_file',
        'uploaded_by',
    ];
}
