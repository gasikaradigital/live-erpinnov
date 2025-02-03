<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansTableSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'uuid' => Str::uuid(),
                'name' => 'Solo',
                'description' => 'Idéal pour les indépendants et micro-entrepreneurs.',
                'price_monthly' => 5.00,
                'price_yearly' => 60.00,
                'price_local' => 25000.00,
                'is_free' => false,
                'is_default' => false,
                'instance_limit' => 1,
                'duration_days' => null,
                'has_sub_plans' => true,
                'features' => json_encode([
                    '2 Go de stockage',
                    '1000 appels API'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'TPE',
                'description' => 'Idéal pour les petites entreprises.',
                'price_monthly' => 14.50,
                'price_yearly' => 174.00,
                'price_local' => 72500.00,
                'is_free' => false,
                'is_default' => false,
                'instance_limit' => 4,
                'duration_days' => null,
                'has_sub_plans' => true,
                'features' => json_encode([
                    '4 Go de stockage',
                    '2000 appels API'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Association',
                'description' => 'Conçu pour la gestion des associations.',
                'price_monthly' => 19.00,
                'price_yearly' => 228.00,
                'price_local' => 95000.00,
                'is_free' => false,
                'is_default' => false,
                'instance_limit' => 10,
                'duration_days' => null,
                'has_sub_plans' => true,
                'features' => json_encode([
                    '4 Go de stockage',
                    '2000 appels API'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Entreprise',
                'description' => 'Offre complète pour les grandes entreprises.',
                'price_monthly' => 39.90,
                'price_yearly' => 478.80,
                'price_local' => 195000.00,
                'is_free' => false,
                'is_default' => false,
                'instance_limit' => null, // Utilisateurs illimités
                'duration_days' => null,
                'has_sub_plans' => true,
                'features' => json_encode([
                    '100 Go de stockage',
                    '10 000 appels API'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($plans as $plan) {
            $planId = DB::table('plans')->insertGetId($plan);

            // Sous-offres pour Solo
            if ($plan['name'] === 'Solo') {
                DB::table('sub_plans')->insert([
                    [
                        'plan_id' => $planId,
                        'name' => 'Basic',
                        'price_monthly' => 5.00,
                        'price_yearly' => 60.00,
                        'features' => json_encode(['Gestion des tiers', 'Produits', 'Stocks', 'Devis', 'Facturation', 'Comptabilité simple']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'plan_id' => $planId,
                        'name' => 'Standard',
                        'price_monthly' => 8.00,
                        'price_yearly' => 96.00,
                        'features' => json_encode(['Modules Basic', 'Rapports et statistiques', 'CRM', 'Email intégré']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'plan_id' => $planId,
                        'name' => 'Premium',
                        'price_monthly' => 12.00,
                        'price_yearly' => 144.00,
                        'features' => json_encode(['Modules Standard', 'Comptabilité analytique', 'Suivi de temps', 'KPI automatisés', 'Multi-devises']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }

            // Sous-offres pour TPE
            if ($plan['name'] === 'TPE') {
                DB::table('sub_plans')->insert([
                    [
                        'plan_id' => $planId,
                        'name' => 'Basic',
                        'price_monthly' => 14.50,
                        'price_yearly' => 174.00,
                        'features' => json_encode(['Modules Basic']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'plan_id' => $planId,
                        'name' => 'Standard',
                        'price_monthly' => 18.00,
                        'price_yearly' => 216.00,
                        'features' => json_encode(['Modules Basic', 'Modules Standard']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'plan_id' => $planId,
                        'name' => 'Premium',
                        'price_monthly' => 22.00,
                        'price_yearly' => 264.00,
                        'features' => json_encode(['Modules Basic', 'Modules Standard', 'Modules Premium']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }

            // Sous-offres pour Association
            if ($plan['name'] === 'Association') {
                DB::table('sub_plans')->insert([
                    [
                        'plan_id' => $planId,
                        'name' => 'Basic',
                        'price_monthly' => 19.00,
                        'price_yearly' => 228.00,
                        'features' => json_encode(['Gestion des adhésions']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'plan_id' => $planId,
                        'name' => 'PME Standard',
                        'price_monthly' => 25.00,
                        'price_yearly' => 300.00,
                        'features' => json_encode(['Paiement, suivi, relance des cotisations']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }

            // Sous-offres pour Entreprise
            if ($plan['name'] === 'Entreprise') {
                DB::table('sub_plans')->insert([
                    [
                        'plan_id' => $planId,
                        'name' => 'RH',
                        'price_monthly' => 39.90,
                        'price_yearly' => 478.80,
                        'features' => json_encode(['Gestion des RH']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'plan_id' => $planId,
                        'name' => 'GPEC',
                        'price_monthly' => 45.00,
                        'price_yearly' => 540.00,
                        'features' => json_encode(['GPEC']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'plan_id' => $planId,
                        'name' => 'Paie',
                        'price_monthly' => 50.00,
                        'price_yearly' => 600.00,
                        'features' => json_encode(['Paie']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }
        }
    }
}
