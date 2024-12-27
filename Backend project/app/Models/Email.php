<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'senderEmail',
        'recipientEmail',
        'object',
        'mailContent',
        'templateId', 
        'isAccepted',
        'isDeleted',
        'isArchived',
    ];

    public function emailtemplate()
    {
        return $this->belongsTo(Emailtemplate::class);
    }
}
