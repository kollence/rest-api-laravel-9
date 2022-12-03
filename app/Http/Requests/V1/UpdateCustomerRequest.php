<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        //CURENT METHOD
        $method = $this->method();
        if($method == 'PUT'){
            return [
                "name" => ['required'],
                "type" => ['required', Rule::in(['I','B','i','b'])], // I = individual , B = business
                "email" => ['required','email'],
                "address" => ['required'],
                "city" => ['required'],
                "state" => ['required'],
                "postalCode" => ['required']
            ];
        }else {
            return [ // rule sometimes = if name is not there its not validated
                "name" => ['sometimes','required'],
                "type" => ['sometimes','required', Rule::in(['I','B','i','b'])], // I = individual , B = business
                "email" => ['sometimes','required','email'],
                "address" => ['sometimes','required'],
                "city" => ['sometimes','required'],
                "state" => ['sometimes','required'],
                "postalCode" => ['sometimes','required']
            ];
        }
    }

    protected function  prepareForValidation()
    {
        if(isset($this->postalCode)){
            $this->merge([
                'postal_code' => $this->postalCode
            ]);
        }
    }
}
