<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyAppsScript
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $apiSecret = env('APPS_SCRIPT_SECRET',false);
        Log::info("api secret  : ".$apiSecret);
        Log::info("send : ".$request->header('X-API-KEY'));
        $timestamp = $request->header('X-TIMESTAMP');
        $authToken = $request->header('X-AUTH-TOKEN');

        /**if (abs(time() - (int)$timestamp) > 300) {
            Log::info("time : ".abs(time()));
            Log::info("timestamp : ".(int)$timestamp);
            Log::info(abs(time()-(int)$timestamp));
            Log::warning('Timestamp invalide ou trop ancien');
            return response('Unauthorized', 401);
        }**/

        // Régénération du token pour vérification
        $expectedToken = base64_encode(hash_hmac(
            'sha256',
            $apiSecret,
            true
        ));

        
        // Vérification des en-têtes
        if ($request->header('X-API-KEY') !== $apiSecret # || !hash_equals($expectedToken, $authToken)
        ) {
            Log::warning('Tentative d\'accès non autorisée à l\'endpoint webhook');
            return response('Unauthorized', 401);
        }

        return $next($request);
    }
}
