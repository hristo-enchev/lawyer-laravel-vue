<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['title'];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'created_at', 'updated_at',
    ];

    const LAWYER = 'lawyer';
    const CLIENT = 'client';
     
    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function scopeLawyerId(Builder $query)
    {
        return $query->where('title', '=', Role::LAWYER)->first()->id;
    }

    public function scopeClientId(Builder $query)
    {
        return $query->where('title', '=', Role::CLIENT)->first()->id;
    }
}
