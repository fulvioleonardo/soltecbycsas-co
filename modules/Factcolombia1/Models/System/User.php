<?php

namespace Modules\Factcolombia1\Models\System;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, UsesSystemConnection;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
}
