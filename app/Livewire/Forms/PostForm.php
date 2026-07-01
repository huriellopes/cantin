<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

/**
 * Form object da página de criação/edição de post (com editor rico e upload de
 * imagem). editingId guarda o registro em edição para o unique de slug ignorar
 * o próprio. O componente mantém WithFileUploads e a imagem atual (currentImage).
 */
class PostForm extends Form
{
    public ?int $editingId = null;

    public string $titleField = '';

    public string $slug = '';

    public ?int $category_id = null;

    public string $published_at = '';

    public string $content = '';

    public $image;

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'titleField' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', Rule::unique('posts', 'slug')->ignore($this->editingId)],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'published_at' => ['required', 'date'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
