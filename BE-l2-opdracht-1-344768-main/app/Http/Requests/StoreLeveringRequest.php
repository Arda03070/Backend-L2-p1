<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeveringRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('access-medewerker-dashboard') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'aantal_producteenheden' => ['required', 'integer', 'min:1'],
            'datum_eerstvolgende_levering' => ['required', 'date'],
        ];
    }
}
