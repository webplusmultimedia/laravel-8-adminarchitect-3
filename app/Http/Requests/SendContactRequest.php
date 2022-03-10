<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $services = Article::services()->publie()->with('media')->latest()->get()->pluck('titre');

        return [
            'nom'       => 'required|min:2',
            'email'     => 'required|email:rfc,dns',
            'telephone' => 'required',
            'subject'   => ['required', Rule::in($services)],
            'msg'       => 'required'
        ];
    }
}
