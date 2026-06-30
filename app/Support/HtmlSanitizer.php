<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Sanitização leve (defesa em profundidade) para o HTML do editor rico antes
 * de persistir. Remove os vetores clássicos de XSS armazenado sem afetar o
 * conteúdo legítimo do Quill (formatação, imagens, vídeos do YouTube/Vimeo):
 *
 *  - tags <script>/<style>/<object>/<embed> (e seu conteúdo);
 *  - atributos de evento inline (on*="...");
 *  - URIs javascript: em href/src.
 *
 * Não substitui o controle principal (apenas admins autenticados escrevem),
 * mas protege visitantes públicos caso uma conta admin seja comprometida.
 */
class HtmlSanitizer
{
    public static function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        // Remove tags perigosas com seu conteúdo.
        $html = preg_replace('#<\s*(script|style|object|embed)\b[^>]*>.*?<\s*/\s*\1\s*>#is', '', $html) ?? $html;
        // Remove tags perigosas sem fechamento (auto-fechadas/abertas).
        $html = preg_replace('#<\s*/?\s*(script|style|object|embed)\b[^>]*>#i', '', $html) ?? $html;
        // Remove handlers de evento inline (onclick, onerror, onload, ...).
        $html = preg_replace('#\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $html) ?? $html;
        // Neutraliza javascript: em href/src.
        $html = preg_replace('#\b(href|src)\s*=\s*("|\')\s*javascript:[^"\']*("|\')#i', '$1=$2#$2', $html) ?? $html;

        return $html;
    }
}
