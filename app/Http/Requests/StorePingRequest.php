<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
//    public function authorize(): bool
//    {
//        return false;
//    }

    public function rules(): array
    {
        return [
            'lat' => ['required'],
            'lon' => ['required'],
            'timestamp' => ['required'],
            'acc' => ['required'],
        ];
    }
}
