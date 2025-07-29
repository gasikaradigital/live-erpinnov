<?php
namespace App\Services;

use App\Models\DolibarrCredential;
use App\Models\InstanceQuota;
use App\Events\InstanceCreatedEvent;
use Illuminate\Support\Facades\Hash;
use App\Notifications\InstanceCreated;
use App\Notifications\SendInstanceCreatedEmail;
use App\Services\CreateUserDolibarr;
use App\Services\InstanceProvisioningService;
use App\Models\Entreprise;
use App\Services\CpanelService;
use App\Services\CreateUsersDolibarr;
use Illuminate\Support\Str;

class FastInstanceProvisioningService {
    public function createInstance($instanceData, $user, $instance, $source, $entrepriseId) {
        try {
            //Rechèrche la prémière instance libre
            $instance_free = InstanceQuota::where('statut', 'libre')->first();
            
            //Rechèrche l'entreprise
            $entreprise = Entreprise::find($entrepriseId);

            if($source === 'erpinnov'){
                // Provisionnement synchrone
                $provisioningService = new InstanceProvisioningService();
                $instanceDetails = $provisioningService->provisionInstance(
                    $instanceData['name'],
                    $instanceData['password_dolibarr'],
                    $instanceData['login_dolibarr'],
                    $instanceData['url_suffix'],
                    $instanceData['api_key_dolibarr'],
                    $user->email,
                    $instance['subscription_id'],
                    $instance_free
                );

                if ($instanceDetails) {
                    // Création des credentials
                    DolibarrCredential::create([
                        'user_id' => $user->id,
                        'username' => $instanceData['login_dolibarr'],
                        'password' => Hash::make($instanceData['password_dolibarr']),
                    ]);

                    // Mise à jour instance
                    $instance->update([
                        'status' => 'active',
                        'url' => $instanceDetails['url']
                    ]);

                    // Création utilisateur Innov
                    $newUsersInnov = new CreateUsersInnov();
                    $newUsersInnov->insertIntoOtherDb(
                        $instanceDetails['db_name'],
                        $instanceData['name'],
                        $user->email,
                        $instance_free->api_key,
                        $instanceData['password_dolibarr'],
                        "http://" . $instance_free->url,
                        $instance['subscription_id']
                    );

                    //Mise à jours du statut de l'instance après assignation à un client
                    $instance_free->statut = 'atrribué';

                    // Enregistrer les modifications dans la base de données
                    $instance_free->save();

                    // Notifications
                    broadcast(new InstanceCreatedEvent($instance));
                    SendInstanceCreatedEmail::dispatch($user, [
                        'name' => $instanceData['name'],
                        'login' => $user->email,
                        'password' => $instanceData['password_dolibarr'],
                        'url' => "http://" . $instanceData['name'] . "erpinnov.com",
                    ]);

                    return true;
                }
            } else {
                //Création de l'utilisateur Dolibarr
                $createUserDolibarr = new CreateUserDolibarr($instanceData['name'], $entreprise, $instance_free);
                $createUserDolibarr->createUser();
                //Mise à jours de son mot de passe
               /* $password = Str::random(12);
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                $createUserDolibarr->setPassword($instance_free, $passwordHash);*/

                //Création sous-domaine
                $this->cpanelService->createSubdomainSass($instanceData['name'], $instance_free->url);

                //Mise à jours du statut de l'instance après assignation à un client
                //$instance_free->statut = 'atrribué';

                // Enregistrer les modifications dans la base de données
                //$instance_free->save();

                return $passwordHash;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Erreur création instance: ' . $e->getMessage());
            $instance->update(['status' => 'failed']);
            throw $e;
        }
    }
}
