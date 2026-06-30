<?php

declare(strict_types=1);

namespace App\Support;

use App\Jobs\FinalizeExport;
use App\Models\Export;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ExportManager
{
    /**
     * Enfileira uma exportação xlsx e registra para download no painel.
     *
     * @param  class-string  $exportClass
     */
    public static function dispatch(string $exportClass, string $name): Export
    {
        $path = 'exports/' . Str::uuid()->toString() . '.xlsx';

        $export = Export::query()->create([
            'user_id' => Auth::id(),
            'name' => $name,
            'disk' => 'local',
            'path' => $path,
            'status' => 'processing',
        ]);

        Excel::queue(new $exportClass(), $path, 'local')
            ->chain([new FinalizeExport($export->id)]);

        return $export;
    }
}
