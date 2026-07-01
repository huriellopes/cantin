<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Form;

/**
 * Form object do cadastro público de terreiros (multi-step). Reúne os campos
 * do terreiro + endereço + questionário, com regras e mensagens; o componente
 * mantém apenas o estado de UI (passo atual, listas de opções, CEP/cascata).
 *
 * Os campos tipados recebem default para não acessar propriedade não
 * inicializada durante a (de)hidratação do Form.
 */
class TerreiroForm extends Form
{
    public string $name = '';

    public ?int $nation_terreiro_id = null;

    public string $phone = '';

    public $leadership_orunko;

    public string $color_of_leadership = '';

    public string $zipcode = '';

    public string $street = '';

    public string $complement = '';

    public ?int $state_id = null;

    public ?int $city_id = null;

    public string $neighborhood = '';

    public $type_people_id;

    public $number_of_children_of_saint;

    public $number_of_children_of_saint_trans;

    public $trans_men_and_women;

    public $name_gender;

    public $fully_welcomes;

    public $respect_for_trans_people;

    public $suffered_aggregation;

    public $inclusion_of_the_name_of_the_land;

    public $suggestion_id;

    public $suggestion_text;

    /**
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'nation_terreiro_id' => 'required|integer',
            'phone' => 'required|string',
            'leadership_orunko' => 'required|string',
            'color_of_leadership' => 'required|string',
            'zipcode' => 'required|string',
            'street' => 'required|string',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'type_people_id' => 'required|integer',
            'number_of_children_of_saint' => 'required|integer',
            'number_of_children_of_saint_trans' => 'required|integer',
            'trans_men_and_women' => 'required|string',
            'name_gender' => 'required|string',
            'fully_welcomes' => 'required|string',
            'respect_for_trans_people' => 'required|string',
            'suffered_aggregation' => 'required|string',
            'inclusion_of_the_name_of_the_land' => 'required|string',
            'suggestion_id' => 'nullable|integer',
            'suggestion_text' => 'nullable|string',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'name.required' => __('The name field is required.'),
            'name.string' => __('The name field only allows characters.'),
            'nation_terreiro_id.required' => __('The nation field is required.'),
            'nation_terreiro_id.integer' => __('The nation field is only allowed numeric characters.'),
            'phone.required' => __('The phone field is required.'),
            'phone.string' => __('The phone field only allows characters.'),
            'leadership_orunko.required' => __('The leadership_orunko field is required.'),
            'leadership_orunko.string' => __('The leadership_orunko field only allows characters.'),
            'color_of_leadership.required' => __('The color_of_leadership field is required.'),
            'color_of_leadership.string' => __('The color_of_leadership field only allows characters.'),
            'zipcode.required' => __('The zipcode field is required.'),
            'zipcode.string' => __('The zipcode field only allows characters.'),
            'street.required' => __('The address field is required.'),
            'street.string' => __('The address field only allows characters.'),
            'complement.string' => __('The complement field only allows characters.'),
            'neighborhood.required' => __('The neighborhood field is required.'),
            'neighborhood.string' => __('The neighborhood field only allows characters.'),
            'state_id.required' => __('The state field is required.'),
            'state_id.integer' => __('The state field is only allowed numeric characters.'),
            'city_id.required' => __('The city field is required.'),
            'city_id.integer' => __('The city field is only allowed numeric characters.'),
            'type_people_id.required' => __('The type_people field is required.'),
            'type_people_id.integer' => __('The type_people field is only allowed numeric characters.'),
            'number_of_children_of_saint.required' => __('The number_of_children_of_saint field is required.'),
            'number_of_children_of_saint.integer' => __('The number_of_children_of_saint field only allowed numeric characters.'),
            'number_of_children_of_saint_trans.required' => __('The number_of_children_of_saint_trans field is required.'),
            'number_of_children_of_saint_trans.integer' => __('The number_of_children_of_saint_trans field only allowed numeric characters.'),
            'trans_men_and_women.required' => __('The trans_men_and_women field is required.'),
            'trans_men_and_women.string' => __('The trans_men_and_women field only allows characters.'),
            'name_gender.required' => __('The name_gender field is required.'),
            'name_gender.string' => __('The name_gender field only allows characters.'),
            'fully_welcomes.required' => __('The fully_welcomes field is required.'),
            'fully_welcomes.string' => __('The fully_welcomes field only allows characters.'),
            'respect_for_trans_people.required' => __('The respect_for_trans_people field is required.'),
            'respect_for_trans_people.string' => __('The respect_for_trans_people field only allows characters.'),
            'suffered_aggregation.required' => __('The suffered_aggregation field is required.'),
            'suffered_aggregation.string' => __('The suffered_aggregation field only allows characters.'),
            'inclusion_of_the_name_of_the_land.required' => __('The inclusion_of_the_name_of_the_land field is required.'),
            'inclusion_of_the_name_of_the_land.string' => __('The inclusion_of_the_name_of_the_land field only allows characters.'),
            'suggestion_id.integer' => __('The suggestion field is only allowed numeric characters.'),
            'suggestion_text.string' => __('The suggestion_text field only allows characters.'),
        ];
    }
}
