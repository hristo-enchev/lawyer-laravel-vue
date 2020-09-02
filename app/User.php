<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function appointments() 
    {
        return $this->hasMany(Appointment::class);
    }
    
    public function lawyerAppointments() 
    {
        return $this->hasMany(Appointment::class, 'lawyer_id');
    }

    public function scopeLawyers(Builder $query)
    {
        return $query->whereHas(
            'role', function($q){
                $q->where('title', Role::LAWYER);
            }
        );
    }

    public function scopeClients(Builder $query)
    {
        return $query->whereHas(
            'role', function($q){
                $q->where('title', Role::CLIENT);
            }
        );
    }
}
