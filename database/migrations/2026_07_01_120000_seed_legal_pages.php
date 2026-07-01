<?php

declare(strict_types=1);

use App\Enum\Status;
use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Cadastra as páginas "Diretrizes" e "Privacidade" como conteúdo editável
 * (modelo Page), com o texto atual (pt-BR) montado a partir das chaves de
 * tradução — mantendo o layout, que é renderizado no mesmo wrapper estilizado.
 *
 * Idempotente (firstOrCreate por slug): não sobrescreve edições feitas depois.
 * O "/sobre" NÃO é incluído de propósito — seu layout é rico (imagem, cards,
 * CTA) e não sobrevive a um bloco de conteúdo em prosa.
 */
return new class() extends Migration
{
    public function up(): void
    {
        $t = static fn (string $key): string => (string) __($key, [], 'pt_BR');

        Page::query()->firstOrCreate(
            ['slug' => 'diretrizes'],
            ['name' => $t('page_guidelines.title'), 'content' => $this->guidelines($t), 'status' => Status::ACTIVE],
        );

        Page::query()->firstOrCreate(
            ['slug' => 'privacidade'],
            ['name' => $t('page_privacy.title'), 'content' => $this->privacy($t), 'status' => Status::ACTIVE],
        );
    }

    public function down(): void
    {
        Page::query()->whereIn('slug', ['diretrizes', 'privacidade'])->delete();
    }

    /**
     * @param  callable(string): string  $t
     */
    private function guidelines(callable $t): string
    {
        return implode("\n", [
            '<p>' . $t('page_guidelines.intro') . '</p>',
            '<h2>' . $t('page_guidelines.section_respect_title') . '</h2>',
            '<ul><li>' . $t('page_guidelines.section_respect_item_1') . '</li><li>' . $t('page_guidelines.section_respect_item_2') . '</li></ul>',
            '<h2>' . $t('page_guidelines.section_registration_title') . '</h2>',
            '<ul><li>' . $t('page_guidelines.section_registration_item_1') . '</li><li>' . $t('page_guidelines.section_registration_item_2') . '</li><li>' . $t('page_guidelines.section_registration_item_3') . '</li></ul>',
            '<h2>' . $t('page_guidelines.section_content_title') . '</h2>',
            '<ul><li>' . $t('page_guidelines.section_content_item_1') . '</li><li>' . $t('page_guidelines.section_content_item_2') . '</li></ul>',
            '<h2>' . $t('page_guidelines.section_usage_title') . '</h2>',
            '<ul><li>' . $t('page_guidelines.section_usage_item_1') . '</li><li>' . $t('page_guidelines.section_usage_item_2') . '</li></ul>',
            '<h2>' . $t('page_guidelines.section_moderation_title') . '</h2>',
            '<p>' . $t('page_guidelines.section_moderation_text_before') . ' <a href="mailto:seggvg@gmail.com">seggvg@gmail.com</a>.</p>',
        ]);
    }

    /**
     * @param  callable(string): string  $t
     */
    private function privacy(callable $t): string
    {
        return implode("\n", [
            '<p>' . $t('page_privacy.intro') . '</p>',
            '<h2>' . $t('page_privacy.controller_title') . '</h2>',
            '<p>' . $t('page_privacy.controller_text') . '</p>',
            '<h2>' . $t('page_privacy.data_title') . '</h2>',
            '<ul><li>' . $t('page_privacy.data_registration') . '</li><li>' . $t('page_privacy.data_navigation') . '</li><li>' . $t('page_privacy.data_cookies') . '</li></ul>',
            '<h2>' . $t('page_privacy.purpose_title') . '</h2>',
            '<ul><li>' . $t('page_privacy.purpose_1') . '</li><li>' . $t('page_privacy.purpose_2') . '</li><li>' . $t('page_privacy.purpose_3') . '</li></ul>',
            '<h2>' . $t('page_privacy.cookies_title') . '</h2>',
            '<p>' . $t('page_privacy.cookies_text') . '</p>',
            '<h2>' . $t('page_privacy.sharing_title') . '</h2>',
            '<p>' . $t('page_privacy.sharing_text') . '</p>',
            '<h2>' . $t('page_privacy.rights_title') . '</h2>',
            '<p>' . $t('page_privacy.rights_intro') . '</p>',
            '<ul><li>' . $t('page_privacy.rights_1') . '</li><li>' . $t('page_privacy.rights_2') . '</li><li>' . $t('page_privacy.rights_3') . '</li><li>' . $t('page_privacy.rights_4') . '</li><li>' . $t('page_privacy.rights_5') . '</li></ul>',
            '<h2>' . $t('page_privacy.retention_title') . '</h2>',
            '<p>' . $t('page_privacy.retention_text') . '</p>',
            '<h2>' . $t('page_privacy.changes_title') . '</h2>',
            '<p>' . $t('page_privacy.changes_text') . '</p>',
            '<p>' . $t('page_privacy.disclaimer') . '</p>',
        ]);
    }
};
