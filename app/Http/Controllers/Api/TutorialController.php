<?php

namespace App\Http\Controllers\Api;

use App\Events\TutorialUpdated;
use App\Http\Controllers\Controller;
use App\Models\Tutoriel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TutorialController extends Controller
{
    public function receive(Request $request)
    {
        $savedTutorials = Tutoriel::all()->keyBy('row');
        $fetchedTutorials = collect($request->sheetData)->keyBy('row');

        $notifications = [
            'added' => [],
            'updated' => [],
            'deleted' => []
        ];

        $fieldsToCheck = [
            'title',
            'category',
            'Titre',
            'slug',
            'description',
            'video_url',
            'type',
            'tag'
        ];

        $rowsToDelete = $savedTutorials->keys()->diff($fetchedTutorials->keys());

        // Suppression des lignes
        foreach ($rowsToDelete as $row) {
            Tutoriel::where('row', $row)->delete();
            $notifications['deleted'][] = $row;
        }

        foreach ($fetchedTutorials as $row => $faqData) {
            $payload = $this->normalizeFaqData($faqData);

            if (!$savedTutorials->has($row)) {
                // Nouvelle entrée
                $newFaq = Tutoriel::create(array_merge($payload, ['row' => $row]));
                $notifications['added'][] = $this->formatFaqData($newFaq);
            } else {
                $existing = $savedTutorials[$row];

                if ($this->hasChanged($existing, $payload, $fieldsToCheck)) {
                    // Mise à jour
                    $existing->update($payload);
                    $notifications['updated'][] = $this->formatFaqData($existing->fresh());
                }
            }
        }

        Log::info($notifications['updated']);
        event(new TutorialUpdated($notifications));
    }

    // Vérifie si un des champs a changé
    protected function hasChanged($existing, $payload, $fieldsToCheck)
    {
        foreach ($fieldsToCheck as $field) {
            if (($existing->$field ?? null) != ($payload[$field] ?? null)) {
                return true;
            }
        }
        return false;
    }

    // Formatte les données de la FAQ
    protected function formatFaqData($tutorial)
    {
        return [
            'row' => $tutorial->row,
            'title' => $tutorial->title,
            'category' => $tutorial->category,
            'Titre' => $tutorial->Titre,
            'slug' => $tutorial->slug,
            'description' => $tutorial->description,
            'video_url' => $tutorial->video_url ?? null,
            'type' => $tutorial->type ?? null,
            'tag' => $tutorial->tag ?? null
        ];
    }
    /**
     * Normalise les données entrantes en payload compatible avec le modèle FAQs
     */
    private function normalizeFaqData($data)
    {
        return [
            'title' => $data['title'],
            'category' => $data['category'],
            'Titre' => $data['Titre'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'video_url' => $data['video_url'] ?? null,
            'type' => $data['type'] ?? null,
            'author' => $data['author'] ?? null,
            'visible' => $data['visible'] ?? 'Non',
            'tag' => $data['tag'],
        ];
    }



    public function getAll()
    {
        $savedTutorials = Tutoriel::all()->keyBy('row');
        $response = [];
        foreach ($savedTutorials as $tutorial) {
            if ($tutorial->visible == "Oui") {
                array_push(
                    $response,
                    $this->formatFaqData($tutorial)
                );
            }
        }
        return response()->json($response, 200);
    }
}
