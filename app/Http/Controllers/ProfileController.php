<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile ?? $user->profile()->create([
            'user_id' => $user->id,
            'is_public' => true // valeur par défaut
        ]);

        return view('auth.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        // Utiliser les règles de validation définies dans le modèle
        $validated = $request->validate(Profile::rules($profile->id));

        try {
            $profile->update($validated);

            // Vérifier si le profil est complet
            if ($profile->isComplete()) {
                // Si l'utilisateur n'a pas d'entreprise, rediriger vers la création d'entreprise
                if (!$user->entreprises()->exists()) {
                    return redirect()->route('entreprise.create')
                        ->with('success', 'Profil mis à jour avec succès. Vous pouvez maintenant créer votre entreprise.');
                }

                // Si l'utilisateur n'a pas de plan sélectionné
                if (!session()->has('selected_plan')) {
                    return redirect()->route('plans.selection')
                        ->with('success', 'Profil mis à jour avec succès. Veuillez sélectionner un plan.');
                }

                // Si tout est ok, rediriger vers l'espace client
                return redirect()->route('espaceClient')
                    ->with('success', 'Profil mis à jour avec succès.');
            }

            // Si le profil n'est pas complet, rester sur la page
            return back()->with('warning', 'Veuillez compléter tous les champs obligatoires de votre profil.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du profil : ' . $e->getMessage());
        }
    }

    /**
     * Vérifie l'état de complétion du profil via AJAX
     */
    public function checkCompletion()
    {
        $profile = auth()->user()->profile;

        return response()->json([
            'isComplete' => $profile->isComplete(),
            'missingFields' => [
                'fname' => empty($profile->fname),
                'lname' => empty($profile->lname),
                'telephone' => empty($profile->telephone)
            ]
        ]);
    }
}
