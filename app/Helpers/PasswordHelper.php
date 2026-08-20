<?php

namespace App\Helpers;

class PasswordHelper {

public static function hash_password($password) {
    return password_hash($password,PASSWORD_BCRYPT); //hashing algo
}

public static function verify_password($password,$hash) {

    return password_verify($password, $hash);

}
}