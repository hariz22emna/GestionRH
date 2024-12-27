<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
 
    /**
     * Route notifications for the Slack channel.
     */
    public function routeNotificationForSlack(): string
    {
        return 'https://hooks.slack.com/services/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';
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'immat',
        'name',
        'email',
        'password',
        'c_password',
        'function',
        'phoneNumber',
        'dateOfBirth',
        'status',
        'access',
        'image',
        'isAccepted',
        'isDeleted',
        'isArchived',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rappels(){
        return $this->hasMany(Rappel::class);
    }

    public function evaluations(){
        return $this->hasMany(Evaluation::class, 'resourceId');
    }
    public function technology(){
        return $this->hasMany(Technology::class, 'technology');
    }
}
