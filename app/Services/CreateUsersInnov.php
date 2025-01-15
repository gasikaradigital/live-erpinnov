<?php

namespace App\Services;

use App\Models\OtherTable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CreateUsersInnov
{
    public function insertIntoOtherDb($name, $email, $api_key, $password, $urlDolibarr){
        try{
            OtherTable::create([
                'name' => $name,
                'email' => $email,
                'api_key' => $api_key,
                'url_dolibarr' => $urlDolibarr,
                'password' => Hash::make($password)
                
            ]);

            return true;
        } catch (\Exception $e){
            dd($e->getMessage());
        }
        
    }

}
