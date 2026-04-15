<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ComplianceDocument extends Model
{
    protected $fillable = [
        'compliance_record_id',
        'file_path',
        'file_type',
        'uploaded_by',
    ];

    public function record()
    {
        return $this->belongsTo(ComplianceRecord::class, 'compliance_record_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function publicPath(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }
}
