<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\BrowserLanguageService;
use Inertia\Inertia;

class InjectLocaleData
{
    public function handle(Request $request, Closure $next): Response
    {
        $browserLanguageService = new BrowserLanguageService();
        $languageCode = $browserLanguageService->detectLanguage($request);

        // Specify the path to the language JSON files
        $localesPath = base_path('lang');
        $languageFilePath = "{$localesPath}/{$languageCode}";

        if (is_dir($languageFilePath)) {
            $files = glob($languageFilePath . "/*.php");
            $data = [];
            foreach ($files as $filename) {
                $pathParts = pathinfo($filename);
                $data[ $pathParts[ 'filename']] = include $filename;
            }
        } else {
            // Fallback to default language if the language file does not exist
            $languageCode = config('app.locale');
            $languageFilePath = "{$localesPath}/{$languageCode}";
            $files = glob($languageFilePath . "/*.php");
            $data = [];
            foreach ($files as $filename) {
                $pathParts = pathinfo($filename);
                $data[ $pathParts[ 'filename']] = include $filename;
            }
        }

        // Inject data into Inertia
        inertia()->share('localeData', [
            'data' => $data,
            'languageCode' => $languageCode,
        ]);

        return $next($request);
    }
}
