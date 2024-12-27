<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileExigence extends Model
{
    protected $table = 'file_exigence';

    public function fileType()
    {
        return $this->belongsTo(FileType::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['file_type'] = $this->fileType;
        $array['user'] = $this->user;
        return $array;
    }
}