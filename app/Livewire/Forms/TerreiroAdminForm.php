<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

/**
 * Form object do CRUD admin de terreiros: dados do terreiro + endereço +
 * questionário. questionData() devolve os campos do questionário para o
 * updateOrCreate da relação.
 */
class TerreiroAdminForm extends AddressForm
{
    public ?int $editingId = null;

    public string $name = '';

    public string $phone = '';

    public ?int $nation_terreiro_id = null;

    public string $leadership_orunko = '';

    public string $color_of_leadership = '';

    public ?int $type_people_id = null;

    public ?string $number_of_children_of_saint = null;

    public ?string $number_of_children_of_saint_trans = null;

    public ?string $trans_men_and_women = null;

    public ?string $name_gender = null;

    public ?string $fully_welcomes = null;

    public ?string $respect_for_trans_people = null;

    public ?string $suffered_aggregation = null;

    public ?string $inclusion_of_the_name_of_the_land = null;

    public ?int $suggestion_id = null;

    public ?string $suggestion_text = null;

    /**
     * Dados do questionário (relação question) para o updateOrCreate.
     *
     * @return array<string, mixed>
     */
    public function questionData(): array
    {
        return [
            'type_people_id' => $this->type_people_id,
            'number_of_children_of_saint' => $this->number_of_children_of_saint,
            'number_of_children_of_saint_trans' => $this->number_of_children_of_saint_trans,
            'trans_men_and_women' => $this->trans_men_and_women,
            'name_gender' => $this->name_gender,
            'fully_welcomes' => $this->fully_welcomes,
            'respect_for_trans_people' => $this->respect_for_trans_people,
            'suffered_aggregation' => $this->suffered_aggregation,
            'inclusion_of_the_name_of_the_land' => $this->inclusion_of_the_name_of_the_land,
            'suggestion_id' => $this->suggestion_id,
            'suggestion_text' => $this->suggestion_text,
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string'],
            'nation_terreiro_id' => ['required', 'exists:nations_terreiros,id'],
            'leadership_orunko' => ['required', 'string', 'max:255'],
            'color_of_leadership' => ['required', 'string'],
            'type_people_id' => ['required', 'exists:type_peoples,id'],
            'number_of_children_of_saint' => ['required'],
            'number_of_children_of_saint_trans' => ['required'],
            'trans_men_and_women' => ['required'],
            'name_gender' => ['required'],
            'fully_welcomes' => ['required'],
            'respect_for_trans_people' => ['required'],
            'suffered_aggregation' => ['required'],
            'inclusion_of_the_name_of_the_land' => ['required'],
            'suggestion_id' => ['required'],
            'suggestion_text' => ['nullable', 'string', 'max:255'],
        ], $this->addressRules());
    }
}
