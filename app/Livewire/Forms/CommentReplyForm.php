<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Form;

/**
 * Form object da resposta do admin a um comentário do blog.
 */
class CommentReplyForm extends Form
{
    public string $body = '';

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:1', 'max:500'],
        ];
    }
}
