<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ["username", "email", "password", 'email_verified_at','is_verified','email_verification_token'];
    protected $casts = ['email_verified_at'=>'datetime','is_verified'=>'boolean',];

    } //allows mass assignment

