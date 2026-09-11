<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model

{
    protected $table = 'users';
    protected $fillable = ["username", "email", "password", 'avatar_path','bio', 'email_verified_at','is_verified','email_verification_token', 'email_verification_expires_at','password_reset_token','password_reset_expires_at',];
    protected $casts = ['email_verified_at'=>'datetime','is_verified'=>'boolean',];
    protected $hidden = 'password';


    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function documents() {
        return $this->hasMany(Document::class);
    }


    public function getAvatarUrlAttribute() {

        if($this->avatar_path) {
            return asset('storage/avatars/'. $this->avatar_path);
        }

        return asset('images/default-avatar.png');
    }

    public function hasAvatar() {
        return !is_null($this->avatar_path);
    }

    public function documentCount() {

        return $this->documents()->count();
    }

    public function canUploadDocument(){
        return $this->documentCount() <=5;
    }


    } //allows mass assignment

