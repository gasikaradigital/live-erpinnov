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
                'is_free' => true,
                'is_default' => true,
                'instance_limit' => 1,
                'duration_days' => null, // Pas de limite de temps pour le plan gratuit
                'features' => json_encode([
                    'Gestion des ventes basique',
                    'Gestion des stocks limitée',
                    'CRM basique',
                    'Limite de 50 transactions par mois',
                    'Support communautaire'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Essentiel',
                'description' => 'Idéal pour les micro-entreprises et les startups',
                'price_monthly' => 22000,
                'price_yearly' => 240000,
                'is_free' => false,
                'is_default' => false,
                'instance_limit' => 5,
                'duration_days' => null,
                'features' => json_encode([
                    'Gestion des ventes complète',
                    'Gestion des stocks avancée',
                    'CRM avec pipelines de vente',
                    'Comptabilité de base',
                    'Rapports personnalisables',
                    'Support par email'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Pro',
                'description' => 'Pour les petites entreprises en croissance',
                'price_monthly' => 44000,
                'price_yearly' => 480000,
                'is_free' => false,
                'is_default' => false,
                'instance_limit' => 10,
                'duration_days' => null,
                'features' => json_encode([
                    'Toutes les fonctionnalités Essentiel',
                    'Gestion multi-entreprises (jusqu\'à 3)',
                    'Comptabilité avancée',
                    'Gestion de projet',
                    'Intégrations API',
                    'Support prioritaire'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Entreprise',
                'description' => 'Solution complète pour les moyennes entreprises',
                'price_monthly' => 88000,
                'price_yearly' => 960000,
                'is_free' => false,
                'is_default' => false,
                'instance_limit' => null, // illimité
                'duration_days' => null,
                'features' => json_encode([
                    'Toutes les fonctionnalités Pro',
                    'Nombre illimité d\'entreprises',
                    'Gestion RH complète',
                    'Business Intelligence',
                    'Personnalisation avancée',
                    'Intégrations sur mesure',
                    'Support dédié 24/7'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
