<?php

namespace App\Services;

use App\Models\OtherTable;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CreateUsersInnov
{
    public function insertIntoOtherDb($name, $email, $api_key, $password, $urlDolibarr, $SubscriptionId){
        try{
            $subscription = Subscription::find($SubscriptionId);
            
            OtherTable::create([
                'name' => $name,
                'email' => $email,
                'api_key' => $api_key,
                'plan_id' => $subscription->plan_id,
                'sub_plan_id' => $subscription->sub_plan_id,
                'status' => $subscription->status,
                'url_dolibarr' => $urlDolibarr,
                'password' => Hash::make($password)
                
            ]);

            return true;
        } catch (\Exception $e){
            dd($e->getMessage());
        }
        
    }

}
