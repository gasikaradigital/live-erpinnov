<?php

namespace App\Services;


use Illuminate\Support\Facades\Log;
use RuntimeException;

class ClientCodeGeneratorService
{
    private const PREFIX = 'CU';
    private const FORMAT = '%s%s-%06d';
    private const MAX_ATTEMPTS = 5;
    private const RETRY_DELAY_MS = 200;

    public function __construct(
        private DolibarrApiService $apiClient
    ) {}

    /**
     * Génère un code client unique selon le format CU2504-000123
     * 
     * @throws RuntimeException Après plusieurs tentatives infructueuses
     */
    public function generate(): string
    {
        $attempt = 0;
        $yearMonth = date('ym');

        do {
            $increment = $this->getNextIncrement($yearMonth);

            $proposedCode = $this->formatCode($yearMonth, $increment);

            if ($this->verifyCodeUniqueness($proposedCode)) {
                return $proposedCode;
            }

            $attempt++;
            usleep(self::RETRY_DELAY_MS * 1000);

            Log::warning('Collision de code client', [
                'code' => $proposedCode,
                'attempt' => $attempt
            ]);
        } while ($attempt < self::MAX_ATTEMPTS);

        throw new RuntimeException("Échec de génération après " . self::MAX_ATTEMPTS . " tentatives");
    }

    private function getNextIncrement(string $yearMonth): int
    {
        $lastCode = $this->findLatestCode($yearMonth);

        return $lastCode
            ? (int)explode('-', $lastCode)[1] + 1
            : 1;
    }

    private function findLatestCode(string $yearMonth): ?string
    {
        try {
            $response = $this->apiClient->fetch(endpoint: 'thirdparties', query: [
                'sortfield' => 't.code_client',
                'sortorder' => 'DESC',
                'limit' => 1,
                'sqlfilters' => sprintf("(t.code_client:like:'%s%s%%')", self::PREFIX, $yearMonth)
            ]);

            return $response[0]['code_client'] ?? null;
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), '404')) {
                return sprintf("%s%s-000000", self::PREFIX, $yearMonth);
            }
            return null;
        }
    }

    private function verifyCodeUniqueness(string $code): bool
    {
        try {
            $response = $this->apiClient->fetch('thirdparties', [
                'sqlfilters' => sprintf("(t.code_client:like:'%s')", $code),
                'limit' => 1
            ]);

            return empty($response);
        } catch (\Exception $e) {
            if(str_contains($e->getMessage(), '404')){
                return true;
            }
            Log::error('Erreur vérification unicité', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function formatCode(string $yearMonth, int $increment): string
    {
        return sprintf(self::FORMAT, self::PREFIX, $yearMonth, $increment);
    }
}
