<?php

use Illuminate\Support\Facades\Hash;


class Password{

    public function password($password){
        $password =  Hash::make($password);
        return $password;
    }
}

$password = new Password();

$password->password('12345678');

echo $password;