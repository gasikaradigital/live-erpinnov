<?php

namespace App\Http\Controllers\Api;

use App\Events\FaqUpdated;
use App\Http\Controllers\Controller;
use App\Models\FAQs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FAQController extends Controller
{
    public function receive(Request $request)
{
    $savedFaqs = FAQs::all()->keyBy('row');
    $fetchedFaqs = collect($request->sheetData)->keyBy('row');

    $notifications = [
        'added' => [],
        'updated' => [],
        'deleted' => []
    ];

    $fieldsToCheck = [
        'title', 'category', 'Titre', 'slug', 
        'description', 'video_url', 'type', 'tag'
    ];

    $rowsToDelete = $savedFaqs->keys()->diff($fetchedFaqs->keys());

    // Suppression des lignes
    foreach ($rowsToDelete as $row) {
        FAQs::where('row', $row)->delete();
        $notifications['deleted'][] = $row;
    }

    foreach ($fetchedFaqs as $row => $faqData) {
        $payload = $this->normalizeFaqData($faqData);

        if (!$savedFaqs->has($row)) {
            // Nouvelle entrée
            $newFaq = FAQs::create(array_merge($payload, ['row' => $row]));
            $notifications['added'][] = $this->formatFaqData($newFaq);
        } else {
            $existing = $savedFaqs[$row];
            
            if ($this->hasChanged($existing, $payload, $fieldsToCheck)) {
                // Mise à jour
                $existing->update($payload);
                $notifications['updated'][] = $this->formatFaqData($existing->fresh());
            }
        }
    }

    event(new FaqUpdated($notifications));
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
protected function formatFaqData($faq)
{
    return [
        'row' => $faq->row,
        'title' => $faq->title,
        'category' => $faq->category,
        'Titre' => $faq->Titre,
        'slug' => $faq->slug,
        'description' => $faq->description,
        'video_url' => $faq->video_url ?? null,
        'type' => $faq->type ?? null,
        'tag' => $faq->tag ?? null
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
            'tag' => $data['tag'] ?? null,
        ];
    }



    public function getAllFaqs()
    {
        $savedFaqs = FAQs::all()->keyBy('row');
        $response = [];
        foreach ($savedFaqs as $faq) {
            if ($faq->visible == "Oui") {
                array_push(
                    $response,
                    [
                        'title' => $faq->title,
                        'category' => $faq->category,
                        'Titre' => $faq->Titre,
                        'slug' => $faq->slug,
                        'description' => $faq->description,
                        'video_url' => $faq->video_url ?? null,
                        'type' => $faq->type ?? null,
                        'tag' => $data->tag ?? null,
                    ]
                );
            }
        }
        return response()->json($response, 200);
    }
}
