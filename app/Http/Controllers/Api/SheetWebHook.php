<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SheetWebHook extends Controller
{
    public function getUpdate(Request $request){
    $apiData = $request->all();
    
    if (app()->runningInConsole()) {
        echo "Données reçues:\n";
        print_r($apiData);
    }
    
    \Log::info('Données API:', $apiData);
}
}
