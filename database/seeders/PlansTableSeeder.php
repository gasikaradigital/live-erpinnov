<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('plans')->insert([
            [
                'uuid' => Str::uuid(),
                'name' => 'Gratuit',
                'description' => 'Découvrez notre ERP gratuitement pour une entreprise',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'price_local' => 0,
                'is_free' => true,
                'is_default' => true,
                'instance_limit' => 1,
                'duration_days' => null, // Pas de limite de temps pour le plan gratuit
                'features' => json_encode([
                    'Gestion des ventes basique',
                    'Gestion des stocks limitée',
                    'CRM basique',
                    'Accès à la documentation complète',
                    'Mises à jour régulières'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Solo Basic',
                'description' => 'Idéal pour les indépendants ou petites entreprises avec des besoins simples.',
                'price_monthly' => 5.25, // euro
                'price_yearly' => 60.00, // euro
                'price_local' => 25500.00, // Ar
                'is_free' => false,
                'is_default' => false,
                'instance_limit' => 2,
                'duration_days' => null,
                'features' => json_encode([
                    'Gestion des tiers',
                    'Gestion des produits',
                    'Gestion des stocks',
                    'CRM',
                    'Devis et facturation',
                    'Comptabilité'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Solo Standard',
                'description' => 'Pour les petites entreprises nécessitant une gestion avancée.',
                'price_monthly' => 15.50,
                'price_yearly' => 186.00,
                'price_local' => 77500.00,
                'is_free' => false,
                'is_default' => false,
                'instance_limit' => 5,
                'duration_days' => null,
                'features' => json_encode([
                    'Modules Artisan',
                    'Gestion avancée des utilisateurs',
                    'Rapports et statistiques'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Premium',
                'description' => 'Offre complète pour les entreprises ayant des besoins avancés et une gestion d\'équipe.',
                'price_monthly' => 45.00,
                'price_yearly' => 540.00,
                'price_local' => 225000.00,
                'is_free' => false,
                'is_default' => false,
                'instance_limit' => 1000,
                'duration_days' => null,
                'features' => json_encode([
                    'Modules Standard',
                    'Email intégré',
                    'Calculs automatisés des coûts et marges',
                    'Suivi de temps',
                    'Gestion multi-devises'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
