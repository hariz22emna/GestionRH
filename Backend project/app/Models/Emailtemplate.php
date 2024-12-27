<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emailtemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'template',
        'isAccepted',
        'isDeleted',
        'isArchived',
    ];

    public function emails()
    {
        return $this->hasMany(Email::class);
    }
}
