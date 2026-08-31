<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ["username", "email", "password", 'email_verified_at','is_verified','email_verification_token', 'email_verification_expires_at','password_reset_token','password_reset_expires_at',];
    protected $casts = ['email_verified_at'=>'datetime','is_verified'=>'boolean',];

    } //allows mass assignment

