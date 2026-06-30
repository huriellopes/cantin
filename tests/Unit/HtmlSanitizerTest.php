<?php

declare(strict_types=1);

use App\Support\HtmlSanitizer;

it('strips script/style tags, event handlers and javascript: URIs', function (): void {
    $dirty = '<p>ok</p>'
        . '<script>alert(1)</script>'
        . '<style>body{}</style>'
        . '<img src="x" onerror="alert(1)">'
        . '<a href="javascript:alert(1)">x</a>';

    $clean = HtmlSanitizer::clean($dirty);

    expect($clean)->not->toContain('<script')
        ->and($clean)->not->toContain('<style')
        ->and(mb_strtolower($clean))->not->toContain('onerror')
        ->and(mb_strtolower($clean))->not->toContain('javascript:')
        ->and($clean)->toContain('<p>ok</p>');
});

it('keeps legitimate Quill content (formatting, image and video embed)', function (): void {
    $html = '<p><strong>Olá</strong></p>'
        . '<img src="/storage/editor-attachments/a.png">'
        . '<iframe class="ql-video" src="https://www.youtube.com/embed/abc"></iframe>';

    expect(HtmlSanitizer::clean($html))->toBe($html);
});

it('returns empty string for null/empty input', function (): void {
    expect(HtmlSanitizer::clean(null))->toBe('')
        ->and(HtmlSanitizer::clean(''))->toBe('');
});
