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
            'category',
            'slug',
            'question',
            'answer',
            'tag'
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
                
                Log::info($payload['tag']);

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
            'category' => $faq->category,
            'slug' => $faq->slug,
            'question' => $faq->question,
            'answer' => $faq->answer,
            'tag' => $faq->tag
        ];
    }
    /**
     * Normalise les données entrantes en payload compatible avec le modèle FAQs
     */
    private function normalizeFaqData($data)
    {
        return [
            'category' => $data['category'],
            'slug' => $data['slug'],
            'question' => $data['question'],
            'answer' => $data['answer'],
            'visible' => $data['visible'] ?? 'Non',
            'tag' => $data['tag'],
        ];
    }



    public function getAll()
    {
        $savedFaqs = FAQs::all()->keyBy('row');
        $response = [];
        foreach ($savedFaqs as $faq) {
            if ($faq->visible == "Oui") {
                array_push(
                    $response,
                    $this->formatFaqData($faq)
                );
            }
        }
        return response()->json($response, 200);
    }
}
