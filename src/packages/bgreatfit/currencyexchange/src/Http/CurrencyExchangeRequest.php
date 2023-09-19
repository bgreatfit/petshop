<?php

namespace BgreatFit\CurrencyExchange\Http;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CurrencyExchangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'amount'         => 'required',
            'currency_to_exchange'      => 'required',
        ];
    }

    public function failedValidation(Validator $validator)

    {

        throw new HttpResponseException(response()->badRequest('Failed Validation', $validator->errors()));

    }
}
