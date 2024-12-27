<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Rappel extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titre',
        'resourceId',
        'userId',
        'repeatNumber',
        'periodicity',
        'typeAlerte',
        'rappelDate',
        'expireDate',
        'googleCalendarId',


    ];

    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }
    public function resource(){
        return $this->belongsTo(User::class, 'resourceId');
    }
}
