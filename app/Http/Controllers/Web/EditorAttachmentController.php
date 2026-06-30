<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorAttachmentController extends Controller
{
    /**
     * Recebe uploads de imagem/vídeo do editor rico (Quill), grava no disco
     * configurado e devolve a URL pública e o tipo do arquivo.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimetypes:' . implode(',', config('editor.allowed_mimetypes')),
                // Limite vindo de config/editor.php (alinhado ao PHP do servidor).
                'max:' . config('editor.max_upload_kb'),
            ],
        ]);

        $file = $request->file('file');
        $isVideo = str_starts_with((string) $file->getMimeType(), 'video/');

        // Extensão derivada do MIME (não da enviada pelo cliente) para evitar
        // spoofing de extensão (ex.: gravar .php/.svg).
        $extension = $file->extension() ?: 'bin';

        $path = $file->storeAs(
            config('editor.upload_path'),
            Str::uuid() . '.' . $extension,
            config('editor.upload_disk'),
        );

        // URL absoluta evita problemas de caminho relativo na renderização.
        return response()->json([
            'url' => asset('storage/' . $path),
            'type' => $isVideo ? 'video' : 'image',
        ]);
    }
}
