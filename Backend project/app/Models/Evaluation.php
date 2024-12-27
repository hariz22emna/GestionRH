<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'resourceId',
        'status',
        'note',
        'total',
        'technologyId',
        'isAccepted',
        'isDeleted',
        'isArchived',
    ];
    public function resource()
    {
        return $this->belongsTo(User::class, 'resourceId');
    }

    public function technology()
    {
        return $this->belongsTo(Technology::class, 'technologyId');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

}