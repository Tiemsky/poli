<?php

namespace App\Http\Controllers;

use App\Models\Enrollement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    /**
     * Afficher un document (inline dans le navigateur)
     * GET /api/documents/{enrollementKey}/{filename}
     */
    public function show(string $enrollementKey, string $filename, Request $request): StreamedResponse
    {

    $enrollment = Enrollement::where('key', $enrollementKey)->firstOrFail(); // Vérifie que ton modèle s'appelle bien Enrollement ou Enrollment

    // CORRIGÉ : Orthographe du dossier correspondant à ton terminal
    $path = "enrollements/{$enrollementKey}/{$filename}";

    if (!Storage::disk('public')->exists($path)) {
        Log::warning('Document not found', [
            'path' => $path,
            'user_id' => Auth::id()
        ]);
        abort(404, 'Document non trouvé');
    }

    $mimeType = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';

    // Optimisation : La méthode response() de Laravel gère déjà les headers de base.
    // L'astuce du $request->boolean('download') que tu avais mise est parfaite, on va l'utiliser côté Vue.
    $disposition = $request->boolean('download') ? 'attachment' : 'inline';

    return Storage::disk('public')->response($path, $filename, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
        'Cache-Control' => 'private, max-age=3600',
    ]);

    }

    /**
     * Forcer le téléchargement
     * GET /api/documents/{enrollementKey}/{filename}/download
     */
    public function download(string $enrollementKey, string $filename): StreamedResponse
    {
        return $this->show($enrollementKey, $filename, request()->merge(['download' => true]));
    }

    /**
     * Prévisualisation image (cache optimisé)
     * GET /api/documents/{enrollementKey}/{filename}/preview
     */
    public function preview(string $enrollementKey, string $filename): StreamedResponse
    {
        $enrollement = Enrollement::where('key', $enrollementKey)->firstOrFail();
        // $this->authorize('viewDocuments', $enrollement);

        $path = "enrollements/{$enrollementKey}/{$filename}";

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->response($path, $filename, [
            'Content-Type' => Storage::disk('public')->mimeType($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400', // 24h cache
        ]);
    }
}
