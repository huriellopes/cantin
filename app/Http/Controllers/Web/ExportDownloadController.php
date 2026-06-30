<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Export;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportDownloadController extends Controller
{
    public function download(Export $export): BinaryFileResponse
    {
        abort_unless($export->user_id === Auth::id(), 403);
        abort_unless($export->status === 'ready', 404);

        $disk = Storage::disk($export->disk);
        abort_unless($disk->exists($export->path), 404);

        $absolute = $disk->path($export->path);
        $downloadName = Str::slug($export->name) . '.xlsx';

        // O arquivo é removido do servidor após o envio; o registro também.
        $response = response()->download($absolute, $downloadName)->deleteFileAfterSend(true);
        $export->delete();

        return $response;
    }
}
