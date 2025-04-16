<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateContactDolibarr;
use App\Livewire\Client\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Mise à jour d'un profile
     * 
     * @return Response
     */
    public function update(Request $request)
    {
       
        $user = auth()->user();
        $profile = $user->profile;

        try {
            $validator = Validator::make($request->all(), $profile->rules());
    
            if($validator->fails()){
                return response()->json($validator->errors(), 422);
            }

            $profile->update($validator->validate());

            if ($profile->isComplete()) {
                // Lancer le Job de mise à jours du contact dans dolibarr
                try{
                    (new UpdateContactDolibarr($profile, $user->email))->handle();
                } catch(\Exception $e){
                    return response()->json(["message"=>"Erreur de serveur"],500);
                }
            }
            return response()->json($profile,200);

        } catch (\Exception $e) {
            Log::error('Erreur de mise à jour du profil: ' . $e->getMessage());

            return response()->json(["erruer"=>$e->getmessage()],500);
        }
    }
}
