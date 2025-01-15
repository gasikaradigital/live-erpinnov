<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Carbon\Carbon;

class SubscriptionSeeder extends Seeder
{
    /**
     * Exécute les seeds de la table subscriptions.
     *
     * @return void
     */
    public function run()
    {
        // Récupérer tous les utilisateurs ayant le rôle 'client' et tous les plans existants
        $users = User::role('client')->get(); // Utilisation de la méthode 'role' de Spatie
        $plans = Plan::all();

        // Vérifier qu'il y a des utilisateurs et des plans disponibles
        if ($users->isEmpty()) {
            $this->command->warn('Aucun utilisateur avec le rôle "client" trouvé. Veuillez créer des utilisateurs clients avant de lancer ce seeder.');
            return;
        }

        if ($plans->isEmpty()) {
            $this->command->warn('Aucun plan trouvé. Veuillez créer des plans avant de lancer ce seeder.');
            return;
        }

        // Pour chaque utilisateur client, créer une ou plusieurs souscriptions
        foreach ($users as $user) {
            // Par exemple, créer entre 1 et 3 abonnements par utilisateur
            $numberOfSubscriptions = rand(1, 3);

            for ($i = 0; $i < $numberOfSubscriptions; $i++) {
                // Choisir un plan aléatoire
                $plan = $plans->random();

                // Définir les dates de début et de fin
                $start_date = Carbon::now()->subMonths(rand(1, 12)); // Date de début aléatoire dans les 12 derniers mois
                $end_date = (clone $start_date)->addMonths($plan->duration_in_months); // Supposant que le plan a une durée en mois

                // Déterminer le statut
                $statusOptions = ['active', 'expired', 'cancelled'];
                $status = $statusOptions[array_rand($statusOptions)];

                // Créer l'abonnement
                Subscription::create([
                    'user_id'    => $user->id,
                    'plan_id'    => $plan->id,
                    'start_date' => $start_date,
                    'end_date'   => $end_date,
                    'status'     => $status,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $this->command->info('Les abonnements pour les utilisateurs clients ont été créés avec succès.');
    }
}
